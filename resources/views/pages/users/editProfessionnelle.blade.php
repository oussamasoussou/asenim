@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('content')
<h4 class="py-3 mb-4">Paramètres du compte </h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('users.edit', $user->id) }}"><i class="bx bx-user me-1"></i>
                    Infos
                    personnelles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);"><i class='bx bx-align-justify'></i>
                    Infos professionnelles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.edit.biography', $user->id) }}"><i
                        class='bx bxs-book-content'></i>
                    Biographie & Activités</a>
            </li>
        </ul>
        <div class="card mb-4">
            <h5 class="card-header">Profile Details</h5>
            <form method="POST" action="{{ route('users.update.professionnelle', $user->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <hr class="my-0" />
                <div class="card-body">


                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label" for="institution">Institution</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="bx bx-buildings"></i></span>
                                <input type="text" class="form-control" id="institution" name="institution"
                                    placeholder="Prénom" value="{{ old('institution', $user->institution) }}">
                            </div>
                            @error('institution')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="orcid">Orcid</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="bx bx-user"></i></span>
                                <input type="text" class="form-control" id="orcid" name="orcid" placeholder="Nom"
                                    value="{{ old('orcid', $user->orcid) }}">
                            </div>
                            @error('orcid')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label class="form-label" for="function">Fonction</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="bx bx-user"></i></span>
                                <input type="text" class="form-control" id="function" name="function" placeholder="Nom"
                                    value="{{ old('function', $user->function) }}">
                            </div>
                            @error('function')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>



                        <div class="mb-3">
                            <label class="form-label" for="grade">Grade</label>
                            <select class="form-select" id="grade" name="grade">
                                <option>Choisir un grade</option>
                                <option value="1" {{ $user->grade == 1 ? 'selected' : '' }}>Grade 1</option>
                                <option value="2" {{ $user->grade == 2 ? 'selected' : '' }}>Grade 2</option>
                                <option value="3" {{ $user->grade == 3 ? 'selected' : '' }}>Grade 3</option>
                            </select>
                            @error('role')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>

             

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Mettre à jour (Infos professionnelles)</button>
                            <button type="reset" class="btn btn-outline-secondary">Annuler</button>
                        </div>
                    </div>
            </form>
        </div>
        <!-- /Account -->
    </div>
</div>
</div>


@endsection