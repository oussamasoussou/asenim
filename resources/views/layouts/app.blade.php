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

        /* Grid Container */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }




        /* Card Styles */
        .card {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }


        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .card-text {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 10px;
        }

        .card-footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: right;
        }

        .card-footer a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }

        .text-red {
            color: #a00000;
        }








        .chat-container {
            display: flex;
            height: 80vh;
            background-color: #f9f9f9;
        }

        /* Liste des utilisateurs */
        .user-list {
            width: 25%;
            background-color: #2c3e50;
            color: #fff;
            padding: 20px;
            overflow-y: auto;
        }

        .user-list h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: whitesmoke;
        }

        .user-list ul {
            list-style: none;
        }

        .user-list li {
            padding: 10px;
            margin-bottom: 10px;
            background-color: #34495e;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .user-list li:hover {
            background-color: #1abc9c;
        }

        /* Zone de chat */
        .chat-area {
            width: 75%;
            display: flex;
            flex-direction: column;
            background-color: #fff;
        }

        /* En-tête du chat */
        .chat-header {
            padding: 20px;
            background-color: #1abc9c;
            color: #fff;
            font-size: 1.5rem;
            text-align: center;
        }

        /* Liste des messages */
        .message-list {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #ecf0f1;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .message.sent {
            justify-content: flex-end;
        }

        .message.received {
            justify-content: flex-start;
        }

        .message .content {
            max-width: 60%;
            padding: 10px 15px;
            border-radius: 10px;
            position: relative;
        }

        .message.sent .content {
            background-color: #ffffff;
            color: rgb(44, 44, 44);
        }

        .message.received .content {
            background-color: #fff;
            color: #333;
            border: 1px solid #ddd;
        }

        .message .time {
            font-size: 0.8rem;
            color: #777;
            margin-top: 5px;
            display: block;
        }

        /* Zone de saisie */
        .input-area {
            display: flex;
            padding: 15px;
            background-color: #fff;
            border-top: 1px solid #ddd;
        }

        .input-area input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            font-size: 1rem;
        }

        .input-area button {
            padding: 10px 20px;
            margin-left: 10px;
            background-color: #0d7aa6;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .input-area button:hover {
            background-color: #16a085;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .input-area {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .input-area input[type="file"] {
            flex: 1;
        }

        .input-area {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f1f1f1;
            padding: 10px;
            border-radius: 10px;
        }

        .input-container {
            flex: 1;
            display: flex;
            align-items: center;
            background: white;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            position: relative;
        }

        .input-container input[type="text"] {
            flex: 1;
            border: none;
            outline: none;
            padding: 5px;
        }

        .file-label {
            cursor: pointer;
            padding: 5px;
            font-size: 18px;
            color: #007bff;
        }

        #file-name {
            font-size: 14px;
            color: #555;
            margin-left: 10px;
        }

        button#send {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        button#send:hover {
            background: #0056b3;
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


    <!-- ------------------------------ DELETE USER ------------------------------ -->
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

    <!-- ------------------------------ RESTORE USER ------------------------------ -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const restoreModal = document.getElementById('restoreModal');
            const restoreForm = document.getElementById('restoreForm');

            if (restoreModal) {
                restoreModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const userId = button.getAttribute('data-user-id');

                    restoreForm.action = `{{ url('/users') }}/${userId}/restore`; // ✅ Corrigé ici
                });
            }
        });
    </script>

    <!-- ------------------------------ DELETE DOCUMENT ------------------------------ -->

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

    <!-- ------------------------------ RESTORE DOCUMENT ------------------------------ -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const restoreModal = document.getElementById('restoreModalDocument');
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

    <!-- ------------------------------ DELETE NEWS & EVENT ------------------------------ -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('deleteModalNews');
            const deleteForm = document.getElementById('deleteForm');

            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Bouton qui déclenche le modal
                const newsId = button.getAttribute('data-news-id'); // Récupération de l'ID
                const actionUrl = "{{ url('news') }}/" + newsId; // Construction de l'URL d'action
                deleteForm.setAttribute('action', actionUrl); // Mise à jour de l'action du formulaire
            });
        });
    </script>

    <!-- ------------------------------ RESTORE NEWS & EVENT ------------------------------ -->


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const restoreModal = document.getElementById('restoreModalNews');
            const restoreForm = document.getElementById('restoreForm');

            if (restoreModal) {
                restoreModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget; // Bouton qui déclenche la modale
                    const documentId = button.getAttribute('data-news-id'); // Récupère l'ID du document

                    // Met à jour l'action du formulaire avec une URL valide
                    restoreForm.action = `{{ url('news/restore') }}/${documentId}`;
                });
            }
        });

    </script>

    @yield('scripts')

</body>

</html>