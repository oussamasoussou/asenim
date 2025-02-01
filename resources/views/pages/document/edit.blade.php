@extends('layouts.app')

@section('title', 'Mettre à jour un document')

@section('content')
<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Mettre à jour le document</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('documents.update', $documents->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT') 
                <!-- Nom du fichier -->
                <div class="mb-3">
                    <label class="form-label" for="file_name">Nom du fichier</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-file2" class="input-group-text"><i class="bx bx-file"></i></span>
                        <input type="text" class="form-control" id="file_name" name="file_name"
                            value="{{ old('file_name', $documents->file_name) }}" placeholder="Nom du fichier">
                    </div>
                    @error('file_name')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <!-- Type de membre -->
                <div class="mb-3">
                    <label for="member_type" class="form-label">Type de membre</label>
                    <select id="member_type" name="member_type" class="form-control">
                        <option value="all_members" {{ old('member_type', $documents->member_type) == 'all_members' ? 'selected' : '' }}>Tous les membres</option>
                        <option value="permanent" {{ old('member_type', $documents->member_type) == 'permanent' ? 'selected' : '' }}>Permanent</option>
                        <option value="non_permanent" {{ old('member_type', $documents->member_type) == 'non_permanent' ? 'selected' : '' }}>Non Permanent</option>
                    </select>
                    @error('member_type')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <!-- Upload du fichier -->
                <div class="mb-3">
                    <label for="file" class="form-label">Fichier</label>
                    <input class="form-control" type="file" id="file" name="file" accept="application/pdf, image/*">
                    @error('file')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                    <!-- Afficher l'ancien fichier -->
                    @if($documents->file)
                        <p>Ancien fichier : <a href="{{ asset('storage/documents/' . $documents->file) }}" target="_blank">{{ $documents->file }}</a></p>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection
