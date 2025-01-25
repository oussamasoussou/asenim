@extends('layouts.app')

@section('title', 'Liste des utilisateurs')

@section('content')
<div>
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 mb-2 mb-sm-0">
            <h5 class="mb-0 me-3">Liste des utilisateurs (Archivés)</h5>
            <a href="#" class="text-decoration-none d-flex align-items-center gap-2">
                <span class="tf-icons bx bx-archive-in"></span>
                <a href="{{ route('users.index') }}">
                    <h6 class="mb-0" style="color:#696cff">Désarchivés</h6>
                </a>
            </a>
        </div>
        <a href="{{ route('store-user') }}" class="btn btn-primary">
            <span class="tf-icons bx bx-user-plus"></span>&nbsp; Ajouter un utilisateur
        </a>
    </div>
</div>


<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header">
            <div class="input-group input-group-merge">
                <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                <input type="text" class="form-control" placeholder="Search..." aria-label="Search..."
                    aria-describedby="basic-addon-search31" />
            </div>
        </h5>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <a href="{{ route('users.archives', ['sort' => 'name', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                            style="color:#566a7f">
                            Nom & Prénom
                            <i
                                class="bx bx-sort @if (request('sort') == 'name' && request('order') == 'asc') bx-sort-alt @elseif (request('sort') == 'name' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('users.archives', ['sort' => 'email', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                            style="color:#566a7f">
                            Email
                            <i
                                class="bx bx-sort @if (request('sort') == 'email' && request('order') == 'asc') bx-sort-alt @elseif (request('sort') == 'email' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('users.archives', ['sort' => 'phone', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                            style="color:#566a7f">
                            Téléphone
                            <i
                                class="bx bx-sort @if (request('sort') == 'phone' && request('order') == 'asc') bx-sort-alt @elseif (request('sort') == 'phone' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('users.archives', ['sort' => 'role', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                            style="color:#566a7f">
                            Rôle
                            <i
                                class="bx bx-sort @if (request('sort') == 'role' && request('order') == 'asc') bx-sort-alt @elseif (request('sort') == 'role' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Boucle à travers les utilisateurs -->
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            @if ($user->role === 'admin')
                                <span class="badge bg-label-success me-1">Admin</span>
                            @elseif ($user->role === 'membre')
                                <span class="badge bg-label-primary me-1">Membre</span>
                            @endif
                        </td>
                        <td>


                            <!-- Bouton de restauration qui ouvre la modale -->
                            <!-- Bouton de restauration -->
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#restoreModal" data-user-id="{{ $user->id }}">
                                <i class="bx bx-undo me-1"></i>
                            </button>

                            <!-- Modale unique -->
                            <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="restoreModalLabel">Confirmation de restauration</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir restaurer cet utilisateur ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <form id="restoreForm" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-danger">Restaurer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <!-- Dropdown pour le nombre d'éléments par page -->
                <form action="{{ route('users.index') }}" method="get" class="d-flex justify-content-start">
                    <div class="form-group position-relative">
                        <select name="perPage" id="perPage" class="form-control custom-select"
                            onchange="this.form.submit()">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        </select>
                        <!-- Flèche -->
                        <i class="fas fa-chevron-down position-absolute"
                            style="top: 50%; right: 10px; transform: translateY(-50%); color: #adafb2; font-size: 0.9rem;"></i>
                    </div>
                </form>
            </div>

            <div class="col-6 text-end">
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <!-- Premier bouton -->
                        <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ route('users.index', ['page' => 1, 'perPage' => $perPage]) }}">
                                <i class="tf-icon bx bx-chevrons-left"></i>
                            </a>
                        </li>

                        <!-- Bouton précédent -->
                        <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ route('users.index', ['page' => $page - 1, 'perPage' => $perPage]) }}">
                                <i class="tf-icon bx bx-chevron-left"></i>
                            </a>
                        </li>

                        <!-- Numéros de page -->
                        @for ($i = 1; $i <= $totalPages; $i++)
                            <li class="page-item {{ $i == $page ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ route('users.index', ['page' => $i, 'perPage' => $perPage]) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        <!-- Bouton suivant -->
                        <li class="page-item {{ $page == $totalPages ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ route('users.index', ['page' => $page + 1, 'perPage' => $perPage]) }}">
                                <i class="tf-icon bx bx-chevron-right"></i>
                            </a>
                        </li>

                        <!-- Dernier bouton -->
                        <li class="page-item {{ $page == $totalPages ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ route('users.index', ['page' => $totalPages, 'perPage' => $perPage]) }}">
                                <i class="tf-icon bx bx-chevrons-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection