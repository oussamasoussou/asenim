<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserConnectedRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class usersController extends Controller
{

    public function showStoreUsers()
    {
        $userConnected = Auth::user();
        return view('pages.users.store', compact('userConnected'));
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $userConnected = Auth::user();
        return view('pages.users.edit', compact('user', 'userConnected'));
    }
    public function editConnectedUser()
    {
        $userConnected = Auth::user();
        return view('pages.users.editConnectedUser', compact('userConnected'));
    }
    public function editMembreProfile($id)
    {
        $userConnected = Auth::user();
        $user = User::findOrFail($id);

        return view('pages.users.editConnectedUserMembre', compact('userConnected','user'));
    }
    public function index()
    {
        $userConnected = Auth::user();
        $perPage = request()->get('perPage', 10);
        $page = request()->get('page', 1);

        $sort = request()->get('sort', 'name');
        $order = request()->get('order', 'asc');

        $users = User::where('id', '!=', $userConnected->id)
            ->orderBy($sort, $order)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $totalUsers = User::where('id', '!=', $userConnected->id)->count();
        $totalPages = ceil($totalUsers / $perPage);

        return view('pages.users.index', compact('users', 'userConnected', 'page', 'totalPages', 'perPage'));
    }

    public function listArchives()
    {
        $userConnected = Auth::user();
        $perPage = request()->get('perPage', 10);
        $page = request()->get('page', 1);

        $sort = request()->get('sort', 'name');
        $order = request()->get('order', 'asc');

        $users = User::onlyTrashed()
            ->orderBy($sort, $order)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $totalUsers = User::onlyTrashed()->count();
        $totalPages = ceil($totalUsers / $perPage);

        return view('pages.users.archives', compact('users', 'userConnected', 'page', 'totalPages', 'perPage', 'sort', 'order'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            if (Auth::user()->role !== 'admin') {
                return redirect()->route('users.index')->with('error', 'Vous n\'avez pas la permission de créer un utilisateur.');
            }
            $user = new User();

            $validatedData = $request->validated();

            // Mise en forme des noms
            $validatedData['first_name'] = strtoupper($validatedData['first_name']);
            $validatedData['last_name'] = ucfirst(strtolower($validatedData['last_name']));

            // Génération du champ 'name'
            $name = strtoupper(substr($validatedData['first_name'], 0, 1)) . strtoupper(substr($validatedData['last_name'], 0, 1));

            // Téléchargement de l'image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $validatedData['image'] = $imagePath;
            } else {
                $validatedData['image'] = null;
            }

            $user->first_name = $validatedData['first_name'];
            $user->last_name = $validatedData['last_name'];
            $user->phone = $validatedData['phone'];
            $user->image = $validatedData['image'];
            $user->role = $validatedData['role'];
            $user->email = $validatedData['email'];
            $user->type = $validatedData['type'];
            $user->password = Hash::make('123456789');
            $user->name = $name;
            $user->first_connection = 1;
            $user->save();

            return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Une erreur est survenue lors de la création de l\'utilisateur. Veuillez réessayer.');
        }
    }
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            if (Auth::user()->role !== 'admin') {
                return redirect()->route('users.index')->with('error', 'Vous n\'avez pas la permission de modifier un utilisateur.');
            }

            $user = User::findOrFail($id);

            $validatedData = $request->validated();

            $new_email = $validatedData['email'];
            if ($user->email !== $new_email) {
                $emailExists = User::where('email', $new_email)->exists();
                if ($emailExists) {
                    return redirect()->back()->with('error', 'Cette adresse email est déjà utilisée.');
                }
            }

            $new_phone = $validatedData['phone'];
            if ($user->phone !== $new_phone) {
                $phoneExists = User::where('phone', $new_phone)->exists();
                if ($phoneExists) {
                    return redirect()->back()->with('error', 'Ce téléphone est déjà utilisée.');
                }
            }

            // Mise en forme des noms
            $validatedData['first_name'] = strtoupper($validatedData['first_name'] ?? $user->first_name);
            $validatedData['last_name'] = ucfirst(strtolower($validatedData['last_name'] ?? $user->last_name));

            // Génération du champ 'name'
            $name = strtoupper(substr($validatedData['first_name'], 0, 1)) . strtoupper(substr($validatedData['last_name'], 0, 1));

            // Gestion de l'image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($user->image && Storage::exists('public/' . $user->image)) {
                    Storage::delete('public/' . $user->image);
                }

                // Télécharger la nouvelle image
                $imagePath = $request->file('image')->store('images', 'public');
                $validatedData['image'] = $imagePath;
            } else {
                $validatedData['image'] = $user->image;
            }

            $validatedData['birth_date'] = $request->birth_date
                ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->birth_date)->format('Y-m-d')
                : $user->birth_date;

            if ($request->has('first_name')) {
                $user->first_name = $validatedData['first_name'];
            }
            if ($request->has('last_name')) {
                $user->last_name = $validatedData['last_name'];
            }
            if ($request->has('phone')) {
                $user->phone = $validatedData['phone'];
            }
            if ($request->has('image')) {
                $user->image = $validatedData['image'];
            }
            if ($request->has('role')) {
                $user->role = $validatedData['role'];
            }
            if ($request->has('email')) {
                $user->email = $validatedData['email'];
            }
            if ($request->has('type')) {
                $user->type = $validatedData['type'];
            }
            $user->save();


            return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('users.index')->with('error', 'Utilisateur introuvable.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Une erreur est survenue lors de la mise à jour de l\'utilisateur. Veuillez réessayer.');
        }
    }
    public function updateUserConnected(UpdateUserConnectedRequest $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validated();

            $new_email = $validatedData['email'];
            if ($user->email !== $new_email) {
                $emailExists = User::where('email', $new_email)->exists();
                if ($emailExists) {
                    return redirect()->back()
                        ->withErrors(['email' => 'Cette adresse email est déjà utilisée.'])
                        ->withInput();
                }
            }

            $new_phone = $validatedData['phone'];
            if ($user->phone !== $new_phone) {
                $phoneExists = User::where('phone', $new_phone)->exists();
                if ($phoneExists) {
                    return redirect()->back()
                        ->withErrors(['phone' => 'Ce téléphone est déjà utilisé.'])
                        ->withInput();
                }
            }

            // Mise en forme des noms
            $validatedData['first_name'] = strtoupper($validatedData['first_name'] ?? $user->first_name);
            $validatedData['last_name'] = ucfirst(strtolower($validatedData['last_name'] ?? $user->last_name));

            // Génération du champ 'name'
            $name = strtoupper(substr($validatedData['first_name'], 0, 1)) . strtoupper(substr($validatedData['last_name'], 0, 1));

            // Gestion de l'image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($user->image && Storage::exists('public/' . $user->image)) {
                    Storage::delete('public/' . $user->image);
                }

                // Télécharger la nouvelle image
                $imagePath = $request->file('image')->store('images', 'public');
                $validatedData['image'] = $imagePath;
            } else {
                $validatedData['image'] = $user->image;
            }

            $validatedData['birth_date'] = $request->birth_date
                ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->birth_date)->format('Y-m-d')
                : $user->birth_date;

            if ($request->has('first_name')) {
                $user->first_name = $validatedData['first_name'];
            }
            if ($request->has('last_name')) {
                $user->last_name = $validatedData['last_name'];
            }
            if ($request->has('phone')) {
                $user->phone = $validatedData['phone'];
            }
            if ($request->has('image')) {
                $user->image = $validatedData['image'];
            }
            if ($request->has('role')) {
                $user->role = $validatedData['role'];
            }
            if ($request->has('email')) {
                $user->email = $validatedData['email'];
            }
            $user->save();

            return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('users.index')->with('error', 'Utilisateur introuvable.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Une erreur est survenue lors de la mise à jour de l\'utilisateur. Veuillez réessayer.');
        }
    }


    public function updateFirstConnectionMembre(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validatedData = $request->validated();

            $new_email = $validatedData['email'];
            if ($user->email !== $new_email) {
                $emailExists = User::where('email', $new_email)->exists();
                if ($emailExists) {
                    return redirect()->back()->with('error', 'Cette adresse email est déjà utilisée.');
                }
            }

            $new_phone = $validatedData['phone'];
            if ($user->phone !== $new_phone) {
                $phoneExists = User::where('phone', $new_phone)->exists();
                if ($phoneExists) {
                    return redirect()->back()->with('error', 'Ce téléphone est déjà utilisée.');
                }
            }

            // Mise en forme des noms
            $validatedData['first_name'] = strtoupper($validatedData['first_name'] ?? $user->first_name);
            $validatedData['last_name'] = ucfirst(strtolower($validatedData['last_name'] ?? $user->last_name));

            // Génération du champ 'name'
            $name = strtoupper(substr($validatedData['first_name'], 0, 1)) . strtoupper(substr($validatedData['last_name'], 0, 1));

            // Gestion de l'image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($user->image && Storage::exists('public/' . $user->image)) {
                    Storage::delete('public/' . $user->image);
                }

                // Télécharger la nouvelle image
                $imagePath = $request->file('image')->store('images', 'public');
                $validatedData['image'] = $imagePath;
            } else {
                $validatedData['image'] = $user->image;
            }

            $validatedData['birth_date'] = $request->birth_date
                ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->birth_date)->format('Y-m-d')
                : $user->birth_date;

            if ($request->has('first_name')) {
                $user->first_name = $validatedData['first_name'];
            }
            if ($request->has('last_name')) {
                $user->last_name = $validatedData['last_name'];
            }
            if ($request->has('phone')) {
                $user->phone = $validatedData['phone'];
            }
            if ($request->has('image')) {
                $user->image = $validatedData['image'];
            }
            if ($request->has('role')) {
                $user->role = $validatedData['role'];
            }
            if ($request->has('email')) {
                $user->email = $validatedData['email'];
            }
            if ($request->has('type')) {
                $user->type = $validatedData['type'];
            }
            if ($request->has('institution')) {
                $user->institution = $validatedData['institution'];
            }
            if ($request->has('grade')) {
                $user->grade = $validatedData['grade'];
            }
            if ($request->has('orcid')) {
                $user->orcid = $validatedData['orcid'];
            }
            if ($request->has('function')) {
                $user->function = $validatedData['function'];
            }
            if ($request->has('biography')) {
                $user->biography = $validatedData['biography'];
            }
            if ($request->has('activities')) {
                $user->activities = $validatedData['activities'];
            }
            if ($request->has('level')) {
                $user->level = $validatedData['level'];
            }
            $user->first_connection = 0;
            $user->save();


            return redirect()->route('index')->with('success', 'Utilisateur mis à jour avec succès.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('index')->with('error', 'Utilisateur introuvable.');
        } catch (\Exception $e) {
            return redirect()->route('index')->with('error', 'Une erreur est survenue lors de la mise à jour de l\'utilisateur. Veuillez réessayer.');
        }
    }

    public function destroy($id)
    {
        try {
            if (Auth::user()->role !== 'admin') {
                return redirect()->route('users.index')->with('error', 'Vous n\'avez pas la permission de supprimer un utilisateur.');
            }

            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('users.index')->with('error', 'Utilisateur introuvable.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Une erreur est survenue lors de la suppression de l\'utilisateur. Veuillez réessayer.');
        }
    }
    public function restore($id)
    {
        try {
            if (Auth::user()->role !== 'admin') {
                return redirect()->route('users.index')->with('error', 'Vous n\'avez pas la permission de restaure un utilisateur.');
            }


            $user = User::onlyTrashed()->find($id);
            $user->restore();


            return redirect()->route('users.index')->with('success', 'Utilisateur restore avec succès.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('users.index')->with('error', 'Utilisateur introuvable.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Une erreur est survenue lors de  restore de l\'utilisateur. Veuillez réessayer.');
        }
    }

}
