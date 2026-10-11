<?php

namespace App\Http\Controllers;

use App\Models\BoardMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Administration du Bureau exécutif affiché sur le site public.
 */
class BoardMemberController extends Controller
{
    public function index()
    {
        $userConnected = Auth::user();
        $search = trim((string) request('search'));
        $perPage = max(1, min(100, (int) request('perPage', 5)));
        $page = max(1, (int) request('page', 1));

        $query = BoardMember::query()->when($search !== '', function ($q) use ($search) {
            $q->where(fn ($q) => $q->where('full_name', 'LIKE', "%{$search}%")
                ->orWhere('role', 'LIKE', "%{$search}%")
                ->orWhere('organization', 'LIKE', "%{$search}%"));
        });

        $total = $query->count();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $members = $query->ordered()->skip(($page - 1) * $perPage)->take($perPage)->get();
        // Premier / dernier membre (toutes pages) : désactive les flèches de déplacement
        $firstId = BoardMember::query()->ordered()->value('id');
        $lastId = BoardMember::query()->orderByDesc('position')->orderByDesc('id')->value('id');

        return view('pages.board.index', compact('members', 'userConnected', 'page', 'totalPages', 'perPage', 'total', 'firstId', 'lastId'));
    }

    public function create()
    {
        $userConnected = Auth::user();
        $member = new BoardMember(['is_visible' => true, 'organization' => 'ENIMonastir']);

        return view('pages.board.form', compact('member', 'userConnected'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['position'] = (int) BoardMember::max('position') + 1;
        $data['photo'] = $request->hasFile('photo') ? $request->file('photo')->store('bureau', 'public') : null;

        $member = BoardMember::create($data);

        return redirect()->route('board.index')->with('success', "{$member->full_name} a été ajouté(e) au Bureau exécutif.");
    }

    public function edit(int $id)
    {
        $userConnected = Auth::user();
        $member = BoardMember::findOrFail($id);

        return view('pages.board.form', compact('member', 'userConnected'));
    }

    public function update(Request $request, int $id)
    {
        $member = BoardMember::findOrFail($id);
        $data = $this->validated($request);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($member);
            $data['photo'] = $request->file('photo')->store('bureau', 'public');
        } elseif ($request->boolean('remove_photo')) {
            $this->deletePhoto($member);
            $data['photo'] = null;
        }

        $member->update($data);

        return redirect()->route('board.index')->with('success', 'Modifications enregistrées.');
    }

    public function destroy(int $id)
    {
        $member = BoardMember::findOrFail($id);
        $this->deletePhoto($member);
        $member->delete();

        return redirect()->back()->with('success', "{$member->full_name} a été retiré(e) du Bureau exécutif.");
    }

    public function toggle(int $id)
    {
        $member = BoardMember::findOrFail($id);
        $member->update(['is_visible' => !$member->is_visible]);

        return redirect()->back()->with('success', $member->is_visible
            ? "{$member->full_name} est de nouveau affiché(e) sur le site."
            : "{$member->full_name} est masqué(e) sur le site.");
    }

    /**
     * Échange la position avec le membre précédent (up) ou suivant (down).
     */
    public function move(int $id, string $direction)
    {
        BoardMember::findOrFail($id);

        DB::transaction(function () use ($id, $direction) {
            // Ordre actuel, puis échange avec le voisin et renumérotation 1, 2, 3…
            $ids = BoardMember::query()->ordered()->lockForUpdate()->pluck('id')->all();
            $i = array_search($id, $ids, true);
            $j = $direction === 'up' ? $i - 1 : $i + 1;

            if ($i === false || !isset($ids[$j])) {
                return;
            }

            [$ids[$i], $ids[$j]] = [$ids[$j], $ids[$i]];
            foreach ($ids as $index => $memberId) {
                BoardMember::whereKey($memberId)->update(['position' => $index + 1]);
            }
        });

        return redirect()->back();
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'role' => ['required', 'string', 'max:120'],
            'organization' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255'],
            'quote' => ['nullable', 'string', 'max:200'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_visible' => ['nullable', 'boolean'],
        ], [], [
            'full_name' => 'nom complet',
            'role' => 'fonction',
            'organization' => 'établissement',
            'quote' => 'citation',
            'photo' => 'photo',
        ]);

        $data['is_visible'] = $request->boolean('is_visible');
        unset($data['photo']);

        return $data;
    }

    private function deletePhoto(BoardMember $member): void
    {
        if ($member->photo && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }
    }
}
