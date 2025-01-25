<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Mon application Laravel')</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!-- Config -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <style>
        /* Personnalisation de la liste déroulante */
        .custom-select {
            padding-right: 30px;
            /* Espace pour la flèche */
            border-radius: 0.375rem;
            /* Arrondir les coins */
            border: 1px solid #ccc;
            /* Bordure légère */
            background-color: #f8f9fa;
            /* Fond clair */
        }

        /* Modification de la flèche pour qu'elle soit plus visible */
        .custom-select+i {
            color: #adafb2;
            /* Flèche bleue */
            font-size: 0.8rem;
            /* Taille de la flèche */
        }

        /* Effet au survol du menu */
        .custom-select:hover {
            border-color: #adafb2;
            /* Bordure bleue au survol */
            background-color: #e9ecef;
            /* Fond clair au survol */
        }

        th a i {
            display: inline-block;
            transition: transform 0.3s ease;
            opacity: 1;
            /* Toujours visible */
        }

        th a i.bx-sort {
            opacity: 0.5;
            /* Icône de tri inactive (lorsque la colonne n'est pas triée) */
        }

        th a i.bx-sort-alt,
        th a i.bx-sort-alt-up {
            opacity: 1;
            /* Icône de tri active */
        }

        th a:hover i {
            transform: scale(1.1);
            /* Agrandir l'icône au survol */
        }
    </style>

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.sidebar')
            <div class="layout-page">
                @include('layouts.nav')
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    @include('layouts.footer')
                </div>
            </div>
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <!-- GitHub Buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const userId = button.getAttribute('data-user-id');

                    deleteForm.action = `/users/${userId}`;
                });

            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const restoreModal = document.getElementById('restoreModal');
            const restoreForm = document.getElementById('restoreForm');
            if (restoreModal) {
                restoreModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const userId = button.getAttribute('data-user-id');

                    restoreForm.action = `{{ route('users.restore', ':id') }}`.replace(':id', userId);
                });

            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteModal = document.getElementById('deleteModalDocument');
            const deleteForm = document.getElementById('deleteForm');

            deleteModal.addEventListener('show.bs.modal', (event) => {
                // Bouton qui déclenche la modale
                const button = event.relatedTarget;

                // Récupérer l'ID du document depuis l'attribut data-document-id
                const documentId = button.getAttribute('data-document-id');

                // Mettre à jour l'action du formulaire avec l'ID du document
                deleteForm.action = `/documents/delete/${documentId}`;
            });
        });

    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const restoreModal = document.getElementById('restoreModal');
            const restoreForm = document.getElementById('restoreForm');

            if (restoreModal) {
                restoreModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget; // Bouton qui déclenche la modale
                    const documentId = button.getAttribute('data-document-id'); // Récupère l'ID du document

                    // Met à jour l'action du formulaire
                    restoreForm.action = `{{ route('documents.restore', ':id') }}`.replace(':id', documentId);
                });
            }
        });
    </script>


    @yield('scripts')

</body>

</html>