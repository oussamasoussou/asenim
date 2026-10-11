<?php

namespace App\Http\Controllers;

use App\Models\BoardMember;
use App\Models\ContactMessage;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

/**
 * Site public ASENIM. Les contenus dynamiques proviennent de l'administration :
 * actualités / événements (News), coordonnées (CompanyInfo, via le view composer),
 * messages de contact et demandes d'inscription.
 */
class FrontController extends Controller
{
    public function home()
    {
        $latestNews = $this->publishedNews()->latest('date')->take(6)->get();
        $boardMembers = BoardMember::visible()->ordered()->get();

        return view('front.home', compact('latestNews', 'boardMembers'));
    }

    public function about()
    {
        return view('front.about');
    }

    public function bureau(Request $request)
    {
        $search = trim((string) $request->query('q'));
        $members = BoardMember::visible()->ordered()
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q->where('full_name', 'LIKE', "%{$search}%")
                ->orWhere('role', 'LIKE', "%{$search}%")
                ->orWhere('organization', 'LIKE', "%{$search}%")))
            ->get();

        return view('front.bureau', compact('members'));
    }

    public function gallery()
    {
        return view('front.gallery');
    }

    public function icmic()
    {
        return view('front.icmic');
    }

    // ---------------------------------------------------------------- Actualités

    public function news(Request $request)
    {
        $search = trim((string) $request->query('q'));

        $news = $this->publishedNews()
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(fn ($q) => $q->where('title', 'LIKE', "%{$search}%")->orWhere('content', 'LIKE', "%{$search}%"));
            })
            ->latest('date')
            ->paginate(9)
            ->withQueryString();

        // Première page sans recherche : les 3 plus récentes « à la une »
        $showFeatured = $news->currentPage() === 1 && $search === '';
        $featured = $showFeatured ? $news->getCollection()->take(3) : collect();
        $others = $showFeatured ? $news->getCollection()->slice(3) : $news->getCollection();

        return view('front.news.index', compact('news', 'featured', 'others'));
    }

    public function showNews(int $id)
    {
        $news = $this->publishedNews()->findOrFail($id);
        $related = $this->publishedNews()->whereKeyNot($news->id)->latest('date')->take(3)->get();

        return view('front.news.show', compact('news', 'related'));
    }

    /**
     * Publications visibles sur le site : non archivées, dont l'auteur n'est pas archivé.
     */
    private function publishedNews(): Builder
    {
        return News::query()->with('user')->whereHas('user');
    }

    // ---------------------------------------------------------------- Contact

    public function contact()
    {
        return view('front.contact');
    }

    public function sendContact(Request $request)
    {
        // Champ piège invisible : rempli uniquement par les robots
        if ($request->filled('website')) {
            return $this->respond($request, 'Merci, votre message a bien été envoyé.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ], [], [
            'name' => 'nom complet',
            'subject' => 'objet',
            'message' => 'message',
        ]);

        ContactMessage::create($data + ['ip_address' => $request->ip()]);

        return $this->respond($request, 'Merci, votre message a bien été envoyé. L’équipe ASENIM vous répondra dans les meilleurs délais.');
    }

    // ---------------------------------------------------------------- Inscription

    public function register()
    {
        return view('front.register');
    }

    public function storeRegistration(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms' => ['sometimes', 'accepted'],
        ], [
            'email.unique' => 'Un compte existe déjà avec cette adresse e-mail. Connectez-vous ou utilisez « Mot de passe oublié ».',
        ], [
            'name' => 'nom et prénom',
        ]);

        // « Nom et prénom » saisi en un seul champ : premier mot = prénom, le reste = nom
        $parts = preg_split('/\s+/', trim($data['name']), 2);
        $firstName = mb_strtoupper($parts[0]);
        $lastName = isset($parts[1]) ? Str::ucfirst(mb_strtolower($parts[1])) : '';

        User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'name' => mb_strtoupper(mb_substr($firstName, 0, 1) . mb_substr($lastName ?: $firstName, 0, 1)),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLES['MEMBRE'],
            'type' => 'permanent',
            'first_connection' => 1,
            'approved_at' => null, // en attente de validation par un administrateur
        ]);

        return $this->respond($request, 'Votre demande d’inscription a bien été envoyée. Un administrateur doit la valider : vous pourrez ensuite vous connecter avec votre adresse e-mail et votre mot de passe.', 201);
    }

    private function respond(Request $request, string $message, int $status = 200)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $message], $status)
            : back()->with('success', $message);
    }
}
