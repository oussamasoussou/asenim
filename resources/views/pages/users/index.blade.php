@extends('layouts.app')

@section('title', 'Liste des utilisateurs')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Liste des utilisateurs</h4>
            <p class="text-muted mb-0">Gérez les membres et administrateurs de la plateforme.</p>
        </div>
        <div class="d-flex gap-3 mt-3 mt-md-0">
            <a href="{{ route('users.archives') }}" class="btn btn-outline-secondary rounded-pill d-flex align-items-center shadow-sm hover-lift px-4">
                <i class="bx bx-archive-in me-2"></i> Voir les Archivés
            </a>
            <a href="{{ route('store-user') }}" class="btn btn-primary rounded-pill d-flex align-items-center shadow hover-lift px-4" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none;">
                <i class="bx bx-user-plus me-2"></i> Nouveau
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-md rounded-4 overflow-hidden">
        <!-- Search Bar -->
        <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-muted fw-semibold">Utilisateurs actifs</h6>
            <form action="{{ route('users.index') }}" method="GET" class="input-container w-auto m-0">
                <i class="bx bx-search text-muted fs-5 ps-2"></i>
                <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
                <input type="hidden" name="order" value="{{ request('order', 'asc') }}">
                <input type="hidden" name="perPage" value="{{ $perPage }}">
                <input type="text" name="search" id="searchInput" placeholder="Rechercher un utilisateur..." value="{{ request('search') }}" class="w-100 border-0 bg-transparent outline-0 shadow-none" style="outline: none;" />
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="border-0 px-4 py-3">
                            <a href="{{ route('users.index', ['sort' => 'name', 'order' => request('order') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-decoration-none text-muted fw-semibold d-flex align-items-center gap-1">
                                Utilisateur
                                <i class="bx bx-sort @if(request('sort') == 'name' && request('order') == 'asc') bx-sort-alt @elseif(request('sort') == 'name' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                            </a>
                        </th>
                        <th class="border-0 px-4 py-3">
                            <a href="{{ route('users.index', ['sort' => 'email', 'order' => request('order') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-decoration-none text-muted fw-semibold d-flex align-items-center gap-1">
                                Email
                                <i class="bx bx-sort @if(request('sort') == 'email' && request('order') == 'asc') bx-sort-alt @elseif(request('sort') == 'email' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                            </a>
                        </th>
                        <th class="border-0 px-4 py-3">Téléphone</th>
                        <th class="border-0 px-4 py-3">
                            <a href="{{ route('users.index', ['sort' => 'role', 'order' => request('order') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-decoration-none text-muted fw-semibold d-flex align-items-center gap-1">
                                Rôle
                                <i class="bx bx-sort @if(request('sort') == 'role' && request('order') == 'asc') bx-sort-alt @elseif(request('sort') == 'role' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                            </a>
                        </th>
                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0" id="user-table-body">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="rounded-circle bg-label-primary d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                        <small class="text-muted">Inscrit {{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-muted">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-muted">{{ $user->phone ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if ($user->role === 'admin')
                                    <span class="badge bg-label-success rounded-pill px-3 py-2 fw-semibold">Admin</span>
                                @elseif ($user->role === 'membre')
                                    <span class="badge bg-label-primary rounded-pill px-3 py-2 fw-semibold">Membre</span>
                                @else
                                    <span class="badge bg-label-secondary rounded-pill px-3 py-2 fw-semibold">{{ ucfirst($user->role) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-primary" title="Modifier">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-user-id="{{ $user->id }}" title="Archiver/Supprimer">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bx bx-user-x text-muted mb-3" style="font-size: 3rem;"></i>
                                <h6 class="text-muted">Aucun utilisateur trouvé</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        <div class="card-footer bg-white border-top border-light py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center text-muted text-sm">
                <span>Afficher</span>
                <form action="{{ route('users.index') }}" method="get" class="mx-2">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="order" value="{{ request('order') }}">
                    <select name="perPage" class="form-select form-select-sm shadow-none border-light rounded-pill px-3" onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </form>
                <span>par page</span>
            </div>

            <nav aria-label="Page navigation" id="pagination-container">
                <ul class="pagination pagination-sm justify-content-end mb-0 gap-1">
                    <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                        <a class="page-link rounded-circle" href="{{ route('users.index', ['page' => $page - 1, 'perPage' => $perPage, 'search' => request('search'), 'sort' => request('sort'), 'order' => request('order')]) }}"><i class="bx bx-chevron-left"></i></a>
                    </li>
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                            <a class="page-link rounded-circle {{ $i == $page ? 'bg-primary border-primary text-white shadow-sm' : '' }}" href="{{ route('users.index', ['page' => $i, 'perPage' => $perPage, 'search' => request('search'), 'sort' => request('sort'), 'order' => request('order')]) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $page == $totalPages ? 'disabled' : '' }}">
                        <a class="page-link rounded-circle" href="{{ route('users.index', ['page' => $page + 1, 'perPage' => $perPage, 'search' => request('search'), 'sort' => request('sort'), 'order' => request('order')]) }}"><i class="bx bx-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 px-5">
                <i class="bx bx-error-circle text-danger mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mb-2">Confirmation</h4>
                <p class="text-muted">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action peut l'archiver.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Oui, supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let timeout = null;

    function fetchUsers(url = null) {
        const search = $('#searchInput').val();
        const perPage = $('[name="perPage"]').val();
        const sort = $('[name="sort"]').val();
        const order = $('[name="order"]').val();
        
        let targetUrl = url || "{{ route('users.index') }}";
        
        // Build query params
        const params = new URLSearchParams({
            search: search,
            perPage: perPage,
            sort: sort,
            order: order
        });

        // Add page if url doesn't have it
        if (!url) {
            targetUrl += '?' + params.toString();
        } else if (url.indexOf('?') === -1) {
            targetUrl += '?' + params.toString();
        }

        $.ajax({
            url: targetUrl,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                updateTable(response.users);
                updatePagination(response);
            }
        });
    }

    function updateTable(users) {
        const tbody = $('#user-table-body');
        tbody.empty();

        if (users.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bx bx-user-x text-muted mb-3" style="font-size: 3rem;"></i>
                        <h6 class="text-muted">Aucun utilisateur trouvé</h6>
                    </td>
                </tr>
            `);
            return;
        }

        users.forEach(user => {
            const roleBadge = getRoleBadge(user.role);
            const initials = user.first_name ? user.first_name.charAt(0).toUpperCase() : '?';
            const createdAt = new Date(user.created_at).toLocaleDateString();

            tbody.append(`
                <tr>
                    <td class="px-4 py-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <div class="rounded-circle bg-label-primary d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 38px; height: 38px;">
                                    ${initials}
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold text-dark">${user.first_name} ${user.last_name}</h6>
                                <small class="text-muted">Inscrit le ${createdAt}</small>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-muted">${user.email}</td>
                    <td class="px-4 py-3 text-muted">${user.phone || '-'}</td>
                    <td class="px-4 py-3">${roleBadge}</td>
                    <td class="px-4 py-3 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="/users/${user.id}/edit" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-primary" title="Modifier">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-user-id="${user.id}" title="Archiver/Supprimer">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        });
    }

    function getRoleBadge(role) {
        if (role === 'admin') return '<span class="badge bg-label-success rounded-pill px-3 py-2 fw-semibold">Admin</span>';
        if (role === 'membre') return '<span class="badge bg-label-primary rounded-pill px-3 py-2 fw-semibold">Membre</span>';
        return `<span class="badge bg-label-secondary rounded-pill px-3 py-2 fw-semibold">${role.charAt(0).toUpperCase() + role.slice(1)}</span>`;
    }

    function updatePagination(data) {
        const container = $('#pagination-container');
        const ul = container.find('ul');
        ul.empty();

        // Prev button
        const prevDisabled = data.page == 1 ? 'disabled' : '';
        const prevUrl = data.page > 1 ? `#` : 'javascript:void(0)';
        ul.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link rounded-circle ajax-page" href="#" data-page="${data.page - 1}"><i class="bx bx-chevron-left"></i></a>
            </li>
        `);

        // Pages
        for (let i = 1; i <= data.totalPages; i++) {
            const activeClass = i == data.page ? 'active' : '';
            const linkClass = i == data.page ? 'bg-primary border-primary text-white shadow-sm' : '';
            ul.append(`
                <li class="page-item ${activeClass}">
                    <a class="page-link rounded-circle ajax-page ${linkClass}" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }

        // Next button
        const nextDisabled = data.page == data.totalPages ? 'disabled' : '';
        ul.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link rounded-circle ajax-page" href="#" data-page="${data.page + 1}"><i class="bx bx-chevron-right"></i></a>
            </li>
        `);
    }

    // Event listener for search
    $('#searchInput').on('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            fetchUsers();
        }, 300);
    });

    // Event listener for pagination
    $(document).on('click', '.ajax-page', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page && page > 0) {
            const baseUrl = "{{ route('users.index') }}";
            fetchUsers(`${baseUrl}?page=${page}`);
        }
    });

    // Handle form submission (prevent reload)
    $('.input-container').on('submit', function(e) {
        e.preventDefault();
        fetchUsers();
    });
});
</script>
@endsection