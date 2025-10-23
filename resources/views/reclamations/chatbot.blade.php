<x-layouts.base>
<style>
    .gradient-text {
        background: linear-gradient(135deg, #d946ef, #ec4899, #f97316);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .chat-container {
        max-height: 500px;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        background: #f9fafb;
    }

    .message {
        margin: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        max-width: 80%;
        word-wrap: break-word;
    }

    .user-message {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        margin-left: auto;
        text-align: right;
    }

    .bot-message {
        background: white;
        color: #374151;
        border: 1px solid #e5e7eb;
        margin-right: auto;
    }

    .typing-indicator {
        display: none;
        padding: 0.75rem 1rem;
        margin: 1rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        max-width: 80%;
    }

    .typing-dots {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #9ca3af;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-dots:nth-child(1) { animation-delay: -0.32s; }
    .typing-dots:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typing {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    .floating-icon {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>

<div class="p-6 bg-white rounded shadow">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div>
            <h5 class="text-2xl font-bold gradient-text">🤖 Chatbot Assistant</h5>
            <p class="text-sm text-gray-500 mt-2 flex items-center">
                <i class="fas fa-robot mr-2 text-blue-500"></i>
                Décrivez votre problème et obtenez une solution instantanée
            </p>
        </div>
        <div class="floating-icon text-4xl opacity-20">
            🤖
        </div>
    </div>

    <!-- Chat Interface -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chat Area -->
        <div class="lg:col-span-2">
            <div class="chat-container" id="chatContainer">
                <div class="message bot-message">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-robot text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 mb-1">Assistant IA</p>
                            <p class="text-sm">Bonjour ! Je suis votre assistant IA pour les réclamations. Décrivez-moi votre problème et je vous proposerai une solution adaptée.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typing Indicator -->
            <div class="typing-indicator" id="typingIndicator">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-robot text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500 mr-2">Assistant IA écrit</span>
                        <div class="typing-dots"></div>
                        <div class="typing-dots"></div>
                        <div class="typing-dots"></div>
                    </div>
                </div>
            </div>

            <!-- Input Form -->
            <form id="chatForm" class="mt-4">
                <div class="flex gap-2">
                    <textarea 
                        id="messageInput" 
                        name="description"
                        placeholder="Décrivez votre problème ou votre demande..."
                        class="flex-1 border rounded-lg px-3 py-2 text-sm resize-none"
                        rows="3"
                        maxlength="1000"
                        required
                    ></textarea>
                    <button 
                        type="submit" 
                        id="sendButton"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-xs text-gray-500" id="charCount">0/1000 caractères</span>
                    <div class="flex gap-2">
                        <select id="categorySelect" name="category" class="text-xs border rounded px-2 py-1">
                            <option value="">Catégorie (optionnel)</option>
                            <option value="technique">Technique</option>
                            <option value="livre">Livre</option>
                            <option value="compte">Compte</option>
                            <option value="service">Service</option>
                            <option value="bibliotheque">Bibliothèque</option>
                            <option value="emprunt">Emprunt</option>
                            <option value="autre">Autre</option>
                        </select>
                        <select id="prioritySelect" name="priority" class="text-xs border rounded px-2 py-1">
                            <option value="">Priorité (optionnel)</option>
                            <option value="basse">Basse</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="haute">Haute</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Quick Actions -->
            <div class="border rounded-lg p-4">
                <h6 class="font-semibold text-sm mb-3 flex items-center">
                    <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                    Actions rapides
                </h6>
                <div class="space-y-2">
                    <button class="w-full text-left text-xs p-2 bg-gray-100 rounded hover:bg-gray-200 quick-action" data-text="Je n'arrive pas à me connecter à mon compte">
                        🔐 Problème de connexion
                    </button>
                    <button class="w-full text-left text-xs p-2 bg-gray-100 rounded hover:bg-gray-200 quick-action" data-text="Je voudrais un résumé du livre 'The Midnight Library' de Matt Haig">
                        📚 Demande de résumé
                    </button>
                    <button class="w-full text-left text-xs p-2 bg-gray-100 rounded hover:bg-gray-200 quick-action" data-text="Je ne peux pas rendre mon livre à temps">
                        📖 Retard de retour
                    </button>
                    <button class="w-full text-left text-xs p-2 bg-gray-100 rounded hover:bg-gray-200 quick-action" data-text="Quels sont les horaires d'ouverture de la bibliothèque ?">
                        🕒 Horaires d'ouverture
                    </button>
                </div>
            </div>

            <!-- Tips -->
            <div class="border rounded-lg p-4">
                <h6 class="font-semibold text-sm mb-3 flex items-center">
                    <i class="fas fa-lightbulb mr-2 text-green-500"></i>
                    Conseils
                </h6>
                <ul class="text-xs text-gray-600 space-y-1">
                    <li>• Soyez précis dans votre description</li>
                    <li>• Mentionnez le type de problème</li>
                    <li>• Indiquez la priorité si urgente</li>
                    <li>• Le chatbot s'adapte à votre demande</li>
                </ul>
            </div>

            <!-- Stats -->
            <div class="border rounded-lg p-4">
                <h6 class="font-semibold text-sm mb-3 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-purple-500"></i>
                    Statistiques
                </h6>
                <div class="text-xs text-gray-600 space-y-1">
                    <div class="flex justify-between">
                        <span>Solutions générées:</span>
                        <span id="solutionCount">0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Dernière activité:</span>
                        <span id="lastActivity">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap gap-4 mt-6">
        <a href="{{ route('reclamations.index') }}" 
           class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux réclamations
        </a>
        <button id="clearChat" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
            <i class="fas fa-trash mr-2"></i> Effacer la conversation
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('chatContainer');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const chatForm = document.getElementById('chatForm');
    const typingIndicator = document.getElementById('typingIndicator');
    const charCount = document.getElementById('charCount');
    const solutionCount = document.getElementById('solutionCount');
    const lastActivity = document.getElementById('lastActivity');
    const clearChatBtn = document.getElementById('clearChat');

    let solutionCounter = 0;

    // Compteur de caractères
    messageInput.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = `${count}/1000 caractères`;
        
        if (count > 800) {
            charCount.classList.add('text-red-500');
        } else {
            charCount.classList.remove('text-red-500');
        }
    });

    // Actions rapides
    document.querySelectorAll('.quick-action').forEach(button => {
        button.addEventListener('click', function() {
            messageInput.value = this.dataset.text;
            messageInput.focus();
            charCount.textContent = `${messageInput.value.length}/1000 caractères`;
        });
    });

    // Envoi du message
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Ajouter le message utilisateur
        addMessage(message, 'user');
        
        // Vider l'input
        messageInput.value = '';
        charCount.textContent = '0/1000 caractères';
        
        // Désactiver le bouton
        sendButton.disabled = true;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Afficher l'indicateur de frappe
        showTypingIndicator();
        
        // Envoyer la requête
        const formData = new FormData(chatForm);
        
        fetch('{{ route("reclamations.chatbot.generate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            
            if (data.success) {
                addMessage(data.solution, 'bot', data);
                solutionCounter++;
                solutionCount.textContent = solutionCounter;
                lastActivity.textContent = new Date().toLocaleTimeString();
            } else {
                addMessage('Désolé, une erreur est survenue. Veuillez réessayer.', 'bot');
            }
        })
        .catch(error => {
            hideTypingIndicator();
            addMessage('Erreur de connexion. Veuillez vérifier votre connexion internet.', 'bot');
            console.error('Erreur:', error);
        })
        .finally(() => {
            sendButton.disabled = false;
            sendButton.innerHTML = '<i class="fas fa-paper-plane"></i>';
        });
    });

    // Effacer la conversation
    clearChatBtn.addEventListener('click', function() {
        if (confirm('Êtes-vous sûr de vouloir effacer la conversation ?')) {
            chatContainer.innerHTML = `
                <div class="message bot-message">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-robot text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 mb-1">Assistant IA</p>
                            <p class="text-sm">Bonjour ! Je suis votre assistant IA pour les réclamations. Décrivez-moi votre problème et je vous proposerai une solution adaptée.</p>
                        </div>
                    </div>
                </div>
            `;
            solutionCounter = 0;
            solutionCount.textContent = '0';
            lastActivity.textContent = '-';
        }
    });

    function addMessage(text, sender, data = null) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;
        
        if (sender === 'user') {
            messageDiv.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white mb-1">Vous</p>
                        <p class="text-sm">${text}</p>
                    </div>
                    <div class="flex-shrink-0 ml-3">
                        <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                    </div>
                </div>
            `;
        } else {
            let extraInfo = '';
            if (data) {
                if (data.fallback) {
                    extraInfo = '<div class="mt-2 p-2 bg-yellow-100 border border-yellow-300 rounded text-xs text-yellow-800"><i class="fas fa-info-circle mr-1"></i> Solution générée automatiquement</div>';
                }
                if (data.timestamp) {
                    extraInfo += `<div class="mt-1 text-xs text-gray-500">Généré le ${data.timestamp}</div>`;
                }
            }
            
            messageDiv.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-robot text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 mb-1">Assistant IA</p>
                        <p class="text-sm whitespace-pre-line">${text}</p>
                        ${extraInfo}
                    </div>
                </div>
            `;
        }
        
        chatContainer.appendChild(messageDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function showTypingIndicator() {
        typingIndicator.style.display = 'block';
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function hideTypingIndicator() {
        typingIndicator.style.display = 'none';
    }
});
</script>
@endpush
</x-layouts.base>
