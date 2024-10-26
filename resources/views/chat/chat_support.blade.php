<div class="chat-container">
    <div class="chat-widget" id="chat-widget">
        <div class="chat-header">
            <h2 id="chat-title">aSEAst</h2>
            <span id="chat-toggle-icon" class="minimize-icon" onclick="toggleChat()">&times;</span> <!-- Close icon -->
        </div>
        <div class="chat-controls">
            <button onclick="switchChat('support')" class="switch-button">SEA Support</button>
            <button onclick="switchChat('bot')" class="switch-button">SEA Bot</button>
        </div>
        <div class="chat-body" id="chat-body">
            <div class="messages-box">
                <ul class="messages-list" id="support-messages">
                    @foreach($groupedMessages as $message)
                    <li class="message-item {{ $message->sender->id == auth()->user()->id ? 'sent' : 'received' }}">
                        <div class="message-content">
                            <strong class="message-sender">{{ $message->sender->id == auth()->user()->id ? 'You' : $message->sender->name }}:</strong>
                            <p>{{ $message->content }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <form action="{{ route('send_chat') }}" method="POST" class="chat-form" id="support-form" onsubmit="keepChatOpen()">
                @csrf
                <div class="input-group">
                    <input type="text" name="content" placeholder="Type a message" required class="chat-input">
                    <button type="submit" class="send-button">Send</button>
                </div>
            </form>
        </div>
        <div class="chat-bot-body" id="chat-bot-body" style="display: none;">
            <div class="containerCHAT">
                <div class="chat-containerCHAT">
                    <div class="cardCHAT">
                        <div class="card-bodyCHAT">
                            <div id="chat-box" class="chat-boxCHAT">
                                @foreach ($chats as $chat)
                                    <div class="{{ $chat->is_bot ? 'bot-messageCHAT' : 'user-messageCHAT' }}">
                                        <strong>{{ $chat->is_bot ? 'Bot' : 'You' }}:</strong> {{ e($chat->message) }}
                                    </div>
                                @endforeach
                            </div>
                            <form id="chat-form" class="chat-formCHAT" onsubmit="sendBotMessage(event)">
                                @csrf
                                <div class="input-groupCHAT">
                                    <input type="text" id="message" class="input-messageCHAT chat-input" placeholder="Type your message..." required>
                                    <button type="submit" class="send-buttonCHAT">Send</button>
                                </div>
                            </form>
                            <div id="suggested-questions" class="suggested-questionsCHAT" style="display: none;">
                                <h5>Related Questions:</h5>
                                <ul id="questions-list" class="questions-listCHAT"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="chat-icon" id="chat-icon" onclick="toggleChat()">
        <span>💬</span> <!-- Chat icon -->
    </div>
</div>

<script>
    let currentChat = 'support';
    let chatOpen = localStorage.getItem('chatOpen') === 'true';

    function toggleChat() {
        const chatWidget = document.getElementById('chat-widget');
        const chatIcon = document.getElementById('chat-icon');
        chatOpen = !chatOpen;
        localStorage.setItem('chatOpen', chatOpen);

        if (chatOpen) {
            chatWidget.style.display = "block"; // Show the chat widget
            chatIcon.style.display = "none"; // Hide the icon when chat is open
        } else {
            chatWidget.style.display = "none"; // Hide the chat widget
            chatIcon.style.display = "flex"; // Show the icon when chat is minimized
        }
    }

    function switchChat(type) {
        const chatBody = document.getElementById('chat-body');
        const chatBotBody = document.getElementById('chat-bot-body');
        const chatTitle = document.getElementById('chat-title');

        if (type === 'support') {
            chatBody.style.display = "block";
            chatBotBody.style.display = "none";
            chatTitle.textContent = "aSEAst";
            currentChat = 'support';
            scrollToBottom('support-messages');
        } else {
            chatBody.style.display = "none";
            chatBotBody.style.display = "block";
            chatTitle.textContent = "aSEAst";
            currentChat = 'bot';
            scrollToBottom('chat-box');
        }
    }

    function scrollToBottom(elementId) {
        const element = document.getElementById(elementId);
        element.scrollTop = element.scrollHeight;
    }

    function keepChatOpen() {
        chatOpen = true;
        localStorage.setItem('chatOpen', chatOpen);
    }

    function sendBotMessage(e) {
        e.preventDefault();

        const message = document.getElementById('message').value;
        const chatBox = document.getElementById('chat-box');
        document.getElementById('message').value = '';

        fetch('{{ route('chat.send') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            chatBox.innerHTML += `<div class="user-messageCHAT"><strong>You:</strong> ${message}</div>`;
            scrollToBottom('chat-box');

            if (data.default_response) {
                chatBox.innerHTML += `<div class="bot-messageCHAT"><strong>Bot:</strong> ${data.default_response}</div>`;
                scrollToBottom('chat-box');
            } else if (data.length > 0) {
                const questionsList = document.getElementById('questions-list');
                questionsList.innerHTML = '';
                document.getElementById('suggested-questions').style.display = 'block';

                data.forEach(question => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-itemCHAT');
                    li.innerHTML = question.question;
                    li.addEventListener('click', function() {
                        fetchAnswer(question.id);
                    });
                    questionsList.appendChild(li);
                });
            } else {
                document.getElementById('suggested-questions').style.display = 'none';
            }
            scrollToBottom('chat-box');
        });
    }

    function fetchAnswer(questionId) {
        fetch(`/chat/answer/${questionId}`)
        .then(response => response.json())
        .then(data => {
            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML += `<div class="bot-messageCHAT"><strong>Bot:</strong> ${data.answer}</div>`;
            scrollToBottom('chat-box');
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const chatWidget = document.getElementById('chat-widget');
        const chatIcon = document.getElementById('chat-icon');
        chatWidget.style.display = chatOpen ? "block" : "none"; // Show chat if open
        chatIcon.style.display = chatOpen ? "none" : "flex"; // Show icon if chat is minimized
    });
</script>

<style>
    /* Container styling */
    .chat-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 999; /* Make sure it is above other elements */
    }

    .chat-widget {
        width: 350px;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        background-color: #ffffff;
        font-family: 'Arial', sans-serif;
        transition: all 0.3s ease; /* Smooth transition */
    }

    /* Header styling */
    .chat-header {
        background-color: #0056b3;
        color: white;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ccc;
        font-size: 1.2em;
    }

    .minimize-icon {
        cursor: pointer;
        font-size: 1.5em;
    }

    /* Controls styling */
    .chat-controls {
        display: flex;
        justify-content: space-between;
        padding: 10px;
    }

    .switch-button {
        background-color: #0056b3;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        margin: 5px;
        transition: background-color 0.3s ease;
    }

    .switch-button:hover {
        background-color: #004494;
    }

    /* Body styling */
    .chat-body {
        padding: 10px;
        max-height: 400px;
        overflow-y: auto;
    }

    /* Messages box styling */
    .messages-box {
        margin-bottom: 10px;
    }

    .messages-list {
        list-style: none; /* Remove default list styles (bullets) */
        padding: 0; /* Remove padding from the list */
        margin: 0; /* Remove margin from the list */
    }

    /* Input group styling */
    .input-group {
        display: flex;
        margin-top: 10px;
    }

    .chat-input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-right: 5px;
    }

    .send-button {
        background-color: #0056b3;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .send-button:hover {
        background-color: #004494;
    }

    /* Chat icon styling */
    .chat-icon {
        display: flex; /* Initially shown when chat is minimized */
        justify-content: center;
        align-items: center;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #0056b3; /* Same color as header */
        color: white;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease; /* Smooth transition */
    }

    /* Adjusted for chat messages */
    .message-item {
        padding: 8px;
        margin: 5px 0;
        border-radius: 8px;
        word-wrap: break-word; /* Allow long messages to wrap */
    }

    .sent {
        background-color: #e1ffc7; /* Light green for sent messages */
        text-align: right;
    }

    .received {
        background-color: #f1f1f1; /* Light gray for received messages */
        text-align: left;
    }

    /* Chat bot styles */
    .containerCHAT {
        display: flex;
        justify-content: center;
    }

    .chat-containerCHAT {
        width: 100%;
    }

    .cardCHAT {
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-bodyCHAT {
        padding: 10px;
    }

    .chat-boxCHAT {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 10px;
    }

    .user-messageCHAT {
        background-color: #e1ffc7;
        padding: 8px;
        border-radius: 5px;
        margin: 5px 0;
        text-align: right;
    }

    .bot-messageCHAT {
        background-color: #f1f1f1;
        padding: 8px;
        border-radius: 5px;
        margin: 5px 0;
        text-align: left;
    }

    /* Additional styling */
    .suggested-questionsCHAT {
        display: none;
        margin-top: 10px;
    }

    .questions-listCHAT {
        list-style-type: none;
        padding: 0;
    }

    .list-group-itemCHAT {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        cursor: pointer;
        margin: 5px 0;
        transition: background-color 0.2s;
    }

    .list-group-itemCHAT:hover {
        background-color: #f0f0f0;
    }
</style>
