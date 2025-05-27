@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <a href="{{ route('encadrant.projects.show', $project) }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i>
                            </a>
                            {{ $task->title }}
                        </h4>
                        <small class="text-muted">
                            Projet: {{ $project->name }}
                        </small>
                    </div>
                    <div>
                        <span class="badge bg-{{ 
                            $task->status === 'Terminé' ? 'success' : 
                            ($task->status === 'En cours' ? 'primary' : 
                            ($task->status === 'Bloqué' ? 'danger' : 'secondary')) 
                        }}">
                            {{ $task->status }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Détails de la tâche</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Description :</strong></p>
                                    <p>{{ $task->description ?? 'Aucune description' }}</p>
                                    
                                    <hr>
                                    
                                    <p><strong>Assigné à :</strong> 
                                        @if($task->assignee)
                                            {{ $task->assignee->name }}
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </p>
                                    
                                    <p><strong>Durée estimée :</strong> {{ $task->duration ?? 'Non spécifiée' }}</p>
                                    <p><strong>Date de création :</strong> {{ $task->created_at->format('d/m/Y') }}</p>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('encadrant.projects.tasks.edit', ['project' => $project->id, 'task' => $task->id]) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Participants</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        @forelse($task->participants as $participant)
                                            <div class="list-group-item d-flex align-items-center">
                                                <img src="{{ $participant->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($participant->name) }}" 
                                                     class="rounded-circle me-3" width="40" height="40" alt="{{ $participant->name }}">
                                                <div>
                                                    <h6 class="mb-0">{{ $participant->name }}</h6>
                                                    <small class="text-muted">{{ $participant->email }}</small>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted mb-0">Aucun participant pour l'instant</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Discussion</h5>
                                    <button class="btn btn-sm btn-outline-secondary" id="refresh-messages">
                                        <i class="fas fa-sync-alt"></i> Actualiser
                                    </button>
                                </div>
                                <div class="card-body chat-container" id="chat-messages">
                                    <div class="text-center py-4" id="loading-messages">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Chargement...</span>
                                        </div>
                                    </div>
                                    <!-- Messages will be loaded here via AJAX -->
                                </div>
                                <div class="card-footer">
                                    <form id="message-form">
                                        @csrf
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="message-input" 
                                                   placeholder="Tapez votre message..."
                                                   autocomplete="off">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chat-container {
        height: 500px;
        overflow-y: auto;
        padding: 15px;
    }
    
    .message {
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
    }
    
    .message-sender {
        font-weight: bold;
        font-size: 0.9em;
    }
    
    .message-content {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 15px;
        max-width: 70%;
        word-wrap: break-word;
    }
    
    .message-time {
        font-size: 0.75em;
        color: #6c757d;
        margin-top: 5px;
    }
    
    /* Current user's messages */
    .message.current-user {
        align-items: flex-end;
    }
    
    .message.current-user .message-content {
        background-color: #007bff;
        color: white;
    }
    
    /* Other users' messages */
    .message.other-user {
        align-items: flex-start;
    }
    
    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chat-messages');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const refreshButton = document.getElementById('refresh-messages');
        const loadingIndicator = document.getElementById('loading-messages');
        
        let page = 1;
        let isLoading = false;
        let hasMore = true;
        
        // Load messages
        function loadMessages(append = false) {
            if (isLoading || (!append && !hasMore)) return;
            
            isLoading = true;
            
            if (!append) {
                chatContainer.innerHTML = '<div id="loading-messages" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div></div>';
                loadingIndicator = document.getElementById('loading-messages');
            } else {
                loadingIndicator.style.display = 'block';
            }
            
            fetch(`{{ route('encadrant.tasks.messages', ['project' => $project->id, 'task' => $task->id]) }}?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    if (data.messages.length === 0) {
                        hasMore = false;
                        if (page === 1) {
                            chatContainer.innerHTML = '<div class="text-center py-4 text-muted">Aucun message pour le moment</div>';
                        }
                        return;
                    }
                    
                    const messagesHtml = data.messages.reverse().map(message => createMessageElement(message)).join('');
                    
                    if (append) {
                        chatContainer.insertAdjacentHTML('afterbegin', messagesHtml);
                    } else {
                        chatContainer.innerHTML = messagesHtml;
                        scrollToBottom();
                    }
                    
                    if (data.next_page) {
                        page++;
                    } else {
                        hasMore = false;
                    }
                })
                .catch(error => {
                    console.error('Error loading messages:', error);
                    if (!append) {
                        chatContainer.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement des messages</div>';
                    }
                })
                .finally(() => {
                    isLoading = false;
                    loadingIndicator.style.display = 'none';
                });
        }
        
        // Create message element
        function createMessageElement(message) {
            const isCurrentUser = message.user.id === {{ auth()->id() }};
            const messageClass = isCurrentUser ? 'current-user' : 'other-user';
            const time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            return `
                <div class="message ${messageClass}">
                    <div class="message-content">
                        <div class="message-sender">${message.user.name}</div>
                        <div class="message-text">${message.message.replace(/\n/g, '<br>')}</div>
                        <div class="message-time">${time}</div>
                    </div>
                </div>
            `;
        }
        
        // Send message
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            const formData = new FormData();
            formData.append('message', message);
            formData.append('_token', '{{ csrf_token() }}');
            
            fetch(`{{ route('encadrant.tasks.messages.store', ['project' => $project->id, 'task' => $task->id]) }}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const messageElement = createMessageElement(data.message);
                    if (chatContainer.firstChild && chatContainer.firstChild.id === 'loading-messages') {
                        chatContainer.innerHTML = messageElement;
                    } else {
                        chatContainer.insertAdjacentHTML('beforeend', messageElement);
                    }
                    messageInput.value = '';
                    scrollToBottom();
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Une erreur est survenue lors de l\'envoi du message');
            });
        });
        
        // Refresh messages
        refreshButton.addEventListener('click', function() {
            page = 1;
            hasMore = true;
            loadMessages();
        });
        
        // Auto-refresh every 30 seconds
        setInterval(() => {
            if (document.visibilityState === 'visible') {
                loadMessages(false);
            }
        }, 30000);
        
        // Scroll to bottom of chat
        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
        
        // Initial load
        loadMessages();
        
        // Load more messages when scrolling to top
        chatContainer.addEventListener('scroll', function() {
            if (chatContainer.scrollTop === 0 && hasMore && !isLoading) {
                const oldScrollHeight = chatContainer.scrollHeight;
                loadMessages(true);
                chatContainer.scrollTop = chatContainer.scrollHeight - oldScrollHeight;
            }
        });
    });
</script>
@endpush
@endsection
