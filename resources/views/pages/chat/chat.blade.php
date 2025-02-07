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
    <div class="chat-area">
        <div class="chat-header">Chat en direct</div>

        <!-- Liste des messages -->
        <div id="message-list" class="message-list"></div>

        <!-- Zone de saisie -->
        <div class="input-area">
    <div class="input-container">
        <input type="text" id="content" placeholder="Tapez votre message ici...">
        <label for="file" class="file-label">
            <i class="fas fa-paperclip"></i>
        </label>
        <input type="file" id="file" accept=".jpg,.jpeg,.png,.pdf,.docx,.txt" hidden>
        <span id="file-name"></span>
    </div>
    <button id="send">
        <i class="fas fa-paper-plane"></i> Envoyer
    </button>
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
        const userId = "{{ auth()->id() }}";

        function fetchMessages() {
            fetch('/messages')
                .then(response => response.json())
                .then(data => {
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
                                <img src="{{ asset(path: 'storage/' . $userConnected->image) }}" class="user-avatar">  
                                <strong>${message.user.first_name}&nbsp;${message.user.last_name}</strong>
                            </div>
                            <p>${message.content ?? ''}</p>
                            ${message.file_path ? `
                                <a href="/storage/${message.file_path}" target="_blank">
                                    <i class="${getFileIcon(message.file_name)}"></i> ${message.file_name}
                                </a>
                            ` : ''}                            
                            <span class="time">${new Date(message.created_at).toLocaleTimeString()}</span>
                        </div>
                    `;

                        messagesDiv.appendChild(messageDiv);
                    });

                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                });
        }

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
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Message envoyé:', data);
                    contentInput.value = '';
                    fileInput.value = '';
                    fetchMessages();
                })
                .catch(error => console.error('Erreur:', error));
        });

        fetchMessages();
        setInterval(fetchMessages, 5000000);
    });
</script>
@endsection