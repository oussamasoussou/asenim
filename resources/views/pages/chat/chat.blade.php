@extends('layouts.app')

@section('content')
<div id="chat" class="chat-container">
    <!-- Liste des utilisateurs -->
    <div id="users" class="user-list">
        <h3>Utilisateurs en ligne</h3>
        <ul>
            @forelse ($connectedUsers as $user)
                <li class="user">
                    <img src="{{ asset(path: 'storage/' . $userConnected->image) }}" ² class="user-avatar">
                    &nbsp;&nbsp;{{ $user->first_name }} {{ $user->last_name }}
                </li>
            @empty
                <li class="user">Aucun utilisateur en ligne</li>
            @endforelse
        </ul>
    </div>

    <!-- Zone de chat -->
    <div class="chat-area flex-grow-1 d-flex flex-column bg-white rounded-4 shadow-sm border ms-3" style="height: 85vh; min-height: 700px;">
        <!-- En-tête -->
        <div class="chat-header px-4 py-3 border-bottom d-flex align-items-center bg-light bg-opacity-50 rounded-top-4">
            <h5 class="m-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="bx bx-message-square-dots text-primary fs-4"></i> Chat en direct
            </h5>
        </div>

        <!-- Liste des messages -->
        <div id="message-list" class="message-list flex-grow-1 p-4 overflow-auto" style="background-color: #f8f9fc;"></div>

        <!-- Zone de saisie -->
        <div class="input-area p-3 bg-white border-top rounded-bottom-4">
            
            <!-- Prévisualisation du fichier -->
            <div id="file-name-preview" class="d-none align-items-center pb-2 px-2">
                <span class="badge bg-label-primary text-dark rounded-pill d-flex align-items-center gap-2 px-3 py-2 border shadow-sm" style="font-size: 0.85rem;">
                    <i class="bx bx-paperclip text-primary"></i> <span id="file-name" class="fw-medium text-truncate" style="max-width: 200px;"></span>
                    <i class="bx bx-x cursor-pointer ms-1 text-danger fs-5" id="remove-file" title="Supprimer"></i>
                </span>
            </div>

            <!-- Formulaire de saisie -->
            <div class="d-flex align-items-center gap-3">
                <div class="input-group shadow-sm rounded-pill border bg-white overflow-hidden p-1 d-flex align-items-center flex-grow-1">
                    <input type="text" id="content" class="form-control border-0 shadow-none px-4" placeholder="Écrivez votre message..." style="font-size: 1rem;">
                    <button class="btn btn-icon btn-white border-0" type="button" style="width: 45px; height: 45px; border-radius: 50%;">
                        <label for="file" class="cursor-pointer d-flex align-items-center justify-content-center w-100 h-100 m-0">
                            <i class="bx bx-link-alt text-primary fs-4 hover-lift"></i>
                        </label>
                    </button>
                    <input type="file" id="file" accept=".jpg,.jpeg,.png,.pdf,.docx,.txt" hidden>
                </div>
                
                <!-- Bouton Envoyer -->
                <button id="send" class="btn btn-primary rounded-circle shadow hover-lift d-flex align-items-center justify-content-center p-0" style="width: 55px; height: 55px; flex-shrink: 0; background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none;">
                    <i class="bx bxs-send fs-3 text-white" style="transform: translateX(-2px) translateY(2px);"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Global Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-header border-0 pb-0 justify-content-end p-2 position-absolute w-100 z-3">
                <button type="button" class="btn-close btn-close-white bg-dark p-2 rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.8;"></button>
            </div>
            <div class="modal-body text-center p-0 position-relative">
                <img id="imagePreviewModalSrc" src="" class="img-fluid rounded shadow" style="max-height: 90vh; object-fit: contain; cursor: zoom-out;" data-bs-dismiss="modal">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messagesDiv = document.getElementById('message-list');
        const contentInput = document.getElementById('content');
        const fileInput = document.getElementById('file');
        const sendButton = document.getElementById('send');
        const fileNamePreview = document.getElementById('file-name-preview');
        const fileNameDisplay = document.getElementById('file-name');
        const removeFileBtn = document.getElementById('remove-file');
        const userId = "{{ auth()->id() }}";

        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
                fileNamePreview.classList.remove('d-none');
                fileNamePreview.classList.add('d-flex');
            } else {
                fileNamePreview.classList.add('d-none');
                fileNamePreview.classList.remove('d-flex');
            }
        });

        removeFileBtn.addEventListener('click', function() {
            fileInput.value = '';
            fileNamePreview.classList.add('d-none');
            fileNamePreview.classList.remove('d-flex');
        });

        function fetchMessages() {
            fetch('/messages', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (response.status === 401) {
                        window.location.reload();
                    }
                    const contentType = response.headers.get("content-type");
                    if (!contentType || !contentType.includes("application/json")) {
                        throw new TypeError("La réponse n'est pas du JSON valide.");
                    }
                    return response.json();
                })
                .then(data => {
                    const isScrolledToBottom = messagesDiv.scrollHeight - messagesDiv.clientHeight <= messagesDiv.scrollTop + 50;
                    const previousScrollTop = messagesDiv.scrollTop;

                    messagesDiv.innerHTML = '';
                    data.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message', 'mb-4');

                        if (message.user_id == userId) {
                            messageDiv.classList.add('sent');
                        } else {
                            messageDiv.classList.add('received');
                        }

                        messageDiv.innerHTML = `
                        <div class="content">
                            <div class="user-info">
                                <img src="{{ asset('storage/' . $userConnected->image) }}" class="user-avatar">  
                                <strong>${message.user.first_name}&nbsp;${message.user.last_name}</strong>
                            </div>
                            <p>${message.content && message.content !== 'null' ? message.content : ''}</p>
                            ${message.file_path ? (
                                isImageFile(message.file_name, message.file_type) ? `
                                    <div class="mt-2">
                                        <img src="/storage/${message.file_path}" alt="attachment" class="cursor-pointer" style="max-height: 150px; max-width: 100%; border-radius: 8px; cursor: zoom-in;" onclick="openImageModal('/storage/${message.file_path}')">
                                    </div>
                                ` : `
                                    <a href="/storage/${message.file_path}" target="_blank" class="d-flex align-items-center gap-3 p-2 mt-2 bg-white border border-light rounded-3 shadow-sm text-decoration-none transition-all hover-scale" style="width: fit-content; max-width: 100%;">
                                        <div class="d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px; background-color: #f8f9fa; flex-shrink: 0;">
                                            <i class="${getFileIcon(message.file_name)} fs-3 text-primary"></i>
                                        </div>
                                        <div class="text-truncate pe-3">
                                            <span class="d-block fw-bold text-dark text-truncate" style="font-size: 0.9rem;">${message.file_name}</span>
                                            <span class="d-block text-muted" style="font-size: 0.75rem;">Cliquer pour ouvrir</span>
                                        </div>
                                        <div class="ms-auto ps-3 pe-2 border-start border-light d-flex align-items-center">
                                            <i class="bx bx-download fs-4 text-secondary hover-primary"></i>
                                        </div>
                                    </a>
                                `
                            ) : ''}                            
                            <span class="time mt-1 block">${new Date(message.created_at).toLocaleTimeString()}</span>
                        </div>
                    `;

                        messagesDiv.appendChild(messageDiv);
                    });

                    if (isScrolledToBottom) {
                        messagesDiv.scrollTop = messagesDiv.scrollHeight;
                    } else {
                        messagesDiv.scrollTop = previousScrollTop;
                    }
                })
                .catch(error => console.error('Erreur lors de la récupération des messages:', error));
        }

        function isImageFile(fileName, fileType) {
            if (fileType === 'image') return true;
            if (!fileName) return false;
            const ext = fileName.split('.').pop().toLowerCase();
            return ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
        }

        // Available globally inside the script but bound as a real DOM event
        window.openImageModal = function(src) {
            const modalImage = document.getElementById('imagePreviewModalSrc');
            modalImage.src = src;
            const imageModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            imageModal.show();
        };

        function getFileIcon(fileName) {
            const extension = fileName.split('.').pop().toLowerCase();
            const icons = {
                'pdf': 'fas fa-file-pdf', // Icône PDF
                'doc': 'fas fa-file-word',
                'docx': 'fas fa-file-word', // Icône Word
                'xls': 'fas fa-file-excel',
                'xlsx': 'fas fa-file-excel', // Icône Excel
                'txt': 'fas fa-file-alt', // Icône Texte
                'jpg': 'fas fa-file-image',
                'jpeg': 'fas fa-file-image',
                'png': 'fas fa-file-image', // Icône Image
                'mp4': 'fas fa-file-video', // Icône Vidéo
                'mp3': 'fas fa-file-audio'  // Icône Audio
            };

            return icons[extension] || 'fas fa-file'; // Icône par défaut
        }


        sendButton.addEventListener('click', function () {
            let content = contentInput.value;
            let file = fileInput.files[0];

            let formData = new FormData();
            formData.append('content', content);
            if (file) {
                formData.append('file', file);
            }

            fetch('/messages', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (response.status === 401) {
                        window.location.reload();
                    }
                    const contentType = response.headers.get("content-type");
                    if (!contentType || !contentType.includes("application/json")) {
                        throw new TypeError("La réponse n'est pas du JSON valide.");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Message envoyé:', data);
                    contentInput.value = '';
                    fileInput.value = '';
                    fileNamePreview.classList.add('d-none');
                    fileNamePreview.classList.remove('d-flex');
                    fetchMessages();
                })
                .catch(error => console.error('Erreur:', error));
        });

        fetchMessages();
        setInterval(fetchMessages, 5000);
    });
</script>
@endsection