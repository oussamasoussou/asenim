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
        /* Modern Premium Aesthetics for Asenim Dashboard */
        :root {
            --primary-color: #0ea5e9;
            --primary-hover: #0284c7;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 2px 4px rgba(148, 163, 184, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(148, 163, 184, 0.1), 0 2px 4px -1px rgba(148, 163, 184, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(148, 163, 184, 0.1), 0 4px 6px -2px rgba(148, 163, 184, 0.05);
            --shadow-hover: 0 20px 25px -5px rgba(148, 163, 184, 0.15), 0 10px 10px -5px rgba(148, 163, 184, 0.04);
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Public Sans', sans-serif;
        }

        /* Personnalisation de la liste déroulante */
        .custom-select {
            padding-right: 32px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            background-color: var(--bg-color);
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .custom-select+i {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .custom-select:hover, .custom-select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        th a i {
            display: inline-block;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        th a i.bx-sort { opacity: 0.4; }
        th a i.bx-sort-alt, th a i.bx-sort-alt-up { opacity: 1; color: var(--primary-color); }
        th a:hover i { transform: scale(1.15); color: var(--primary-color); }

        /* Modern Grid Container */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            margin: 24px 0;
        }

        /* Premium Card Styles */
        .card {
            background-color: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(14, 165, 233, 0.3);
        }

        .card-body {
            padding: 24px;
            flex: 1;
        }

        .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .card-text {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .card-text strong {
            color: var(--text-main);
            font-weight: 600;
        }

        .card-footer {
            background-color: rgba(248, 250, 252, 0.5);
            border-top: 1px solid var(--border-color);
            padding: 16px 24px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .card-footer a {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .card-footer a::after {
            content: "→";
            font-family: inherit;
            transition: transform 0.2s ease;
        }

        .card-footer a:hover {
            color: var(--primary-hover);
        }

        .card-footer a:hover::after {
            transform: translateX(4px);
        }
        
        .card-header-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid var(--border-color);
        }

        .text-red { color: #ef4444 !important; font-weight: 700; }

        /* Unified Document Icon Styles */
        .doc-icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            background: #f1f5f9;
            transition: transform 0.3s ease;
        }
        .card:hover .doc-icon-wrapper {
            transform: scale(1.05) rotate(-2deg);
        }

        /* Modern Chat UI */
        .chat-container {
            display: flex;
            height: calc(100vh - 120px);
            background-color: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 1px solid var(--border-color);
            margin-top: 20px;
        }

        .user-list {
            width: 300px;
            background-color: #f8fafc;
            border-right: 1px solid var(--border-color);
            padding: 0;
            overflow-y: auto;
        }

        .user-list h3 {
            font-size: 1.1rem;
            font-weight: 700;
            padding: 20px;
            margin: 0;
            color: #0f172a;
            border-bottom: 1px solid var(--border-color);
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .user-list ul { list-style: none; padding: 12px; margin: 0; }

        .user-list li {
            padding: 12px 16px;
            margin-bottom: 8px;
            background-color: transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--text-main);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-list li:hover {
            background-color: #e0f2fe;
            color: var(--primary-hover);
        }

        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #fff;
        }

        .chat-header {
            padding: 20px 24px;
            background-color: #fff;
            color: #0f172a;
            font-size: 1.25rem;
            font-weight: 700;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            z-index: 10;
        }

        .message-list {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .message {
            margin-bottom: 20px;
            display: flex;
            align-items: flex-end;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message.sent { justify-content: flex-end; }
        .message.received { justify-content: flex-start; }

        .message .content {
            max-width: 65%;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            position: relative;
            line-height: 1.5;
            font-size: 0.95rem;
            box-shadow: var(--shadow-sm);
        }

        .message.sent .content {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .message.received .content {
            background-color: #fff;
            color: var(--text-main);
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 4px;
        }

        .message .time {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 6px;
            display: block;
            text-align: right;
        }
        
        .message.received .time { text-align: left; }

        .input-area {
            display: flex;
            padding: 20px 24px;
            background-color: #fff;
            border-top: 1px solid var(--border-color);
            align-items: center;
            gap: 12px;
        }

        .input-container {
            flex: 1;
            display: flex;
            align-items: center;
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 100px;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            height: 38px;
            max-width: 250px;
        }

        .input-container:focus-within {
            background: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        .input-container input[type="text"] {
            flex: 1;
            border: none;
            outline: none;
            padding: 4px 8px;
            background: transparent;
            font-size: 0.85rem;
            color: var(--text-main);
        }

        .input-container input::placeholder { color: #94a3b8; }

        .file-label {
            cursor: pointer;
            padding: 8px;
            font-size: 20px;
            color: var(--text-muted);
            transition: color 0.2s;
            border-radius: 50%;
            display: flex;
            align-items: center;
        }
        
        .file-label:hover { color: var(--primary-color); background: #f1f5f9; }

        #file-name {
            font-size: 0.8rem;
            color: var(--text-muted);
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        button#send {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 10px rgba(14, 165, 233, 0.3);
        }

        button#send i { font-size: 1.1rem; transform: translateX(-1px); }

        button#send:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 6px 15px rgba(14, 165, 233, 0.4);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: var(--shadow-sm);
        }
        
        /* Modern Utilities */
        .badge-premium {
            background: rgba(14, 165, 233, 0.1);
            color: var(--primary-color);
            padding: 4px 10px;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Subtitle style overrides */
        h4.mb-4 {
            font-weight: 800;
            color: #0f172a;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            position: relative;
            display: inline-block;
            margin-bottom: 2rem !important;
        }
        
        h4.mb-4::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 4px;
            background: linear-gradient(to right, #0ea5e9, #818cf8);
            border-radius: 2px;
        }

        /* Modern Global Pagination */
        .pagination {
            display: flex;
            gap: 6px;
            justify-content: center;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .pagination .page-item .page-link {
            border: none !important;
            background: #f1f5f9;
            color: var(--text-main);
            border-radius: 8px !important;
            padding: 8px 14px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .pagination .page-item .page-link:hover {
            background: #e2e8f0;
            color: var(--primary-color);
            transform: translateY(-1px);
        }
        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 10px rgba(14, 165, 233, 0.4);
            transform: translateY(-2px);
        }
        .pagination .page-item.disabled .page-link {
            background: transparent;
            color: #cbd5e1;
            pointer-events: none;
        }
        
        /* Maximize Table Widths globally */
        .container-fluid {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
            max-width: 100% !important;
        }
        .table-responsive {
            margin: 0;
            width: 100%;
            overflow-x: auto;
        }
        .table {
            width: 100% !important;
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