@extends('layouts.app')

@section('content')
<div id="chat" class="chat-container">
  <!-- Liste des utilisateurs -->
  <div id="users" class="user-list">
    <h3>Utilisateurs en ligne</h3>
    <ul>
        @forelse ($connectedUsers as $user)
            <li class="user">
                <img src="{{ asset(path: 'storage/' . $userConnected->image) }}"
                     class="user-avatar">  
                     &nbsp;&nbsp;{{ $user->first_name }} {{ $user->last_name }}
            </li>
        @empty
            <li class="user">Aucun utilisateur en ligne</li>
        @endforelse
    </ul>
</div>


  <!-- Zone de chat -->
  <div class="chat-area">
      <!-- En-tête du chat -->
      <div class="chat-header">
          Chat en direct
      </div>

      <!-- Liste des messages -->
      <div id="message-list" class="message-list">
          <!-- Message reçu -->
          <div class="message received">
              <div class="content">
                  <strong>Utilisateur 1:</strong>
                  <p>Bonjour, comment ça va ?</p>
                  <span class="time">10:42</span>
              </div>
          </div>

          <!-- Message envoyé -->
          <div class="message sent">
              <div class="content">
                  <p>Ça va bien, merci ! Et toi ?</p>
                  <span class="time">10:43</span>
              </div>
          </div>
      </div>

      <!-- Zone de saisie -->
      <div class="input-area">
          <input type="text" id="content" placeholder="Tapez votre message ici...">
          <button id="send">Envoyer</button>
      </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const messagesDiv = document.getElementById('message-list');
    const contentInput = document.getElementById('content');
    const sendButton = document.getElementById('send');
    const userId = "{{ auth()->id() }}"; // Récupérer l'ID de l'utilisateur connecté depuis Blade

    function fetchMessages() {
        fetch('/messages')
            .then(response => response.json())
            .then(data => {
                messagesDiv.innerHTML = '';
                data.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message', 'mb-4');

                    // Vérifier si le message appartient à l'utilisateur connecté
                    if (message.user_id == userId) {
                        messageDiv.classList.add('sent'); // Message envoyé (droite)
                    } else {
                        messageDiv.classList.add('received'); // Message reçu (gauche)
                    }

                    messageDiv.innerHTML = `
                        <div class="content">
                            <div class="user-info">
                                <img src="{{ asset(path: 'storage/' . $userConnected->image) }}"
                     class="user-avatar">  
                                <strong>${message.user.name}</strong>
                            </div>
                            <p>${message.content ?? ''}</p>
                            ${message.file_path ? `<a href="/storage/${message.file_path}" target="_blank">${message.file_name}</a>` : ''}
                            <span class="time">${new Date(message.created_at).toLocaleTimeString()}</span>
                        </div>
                    `;


                    messagesDiv.appendChild(messageDiv);
                });

                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });
    }

    sendButton.addEventListener('click', () => {
        const content = contentInput.value;

        fetch('/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ content })
        })
        .then(response => response.json())
        .then(() => {
            contentInput.value = '';
            fetchMessages();
        });
    });

    fetchMessages();
    setInterval(fetchMessages, 50000);
});


</script>
@endsection