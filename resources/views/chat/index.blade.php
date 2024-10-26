@include('student.css')
@include('student.sidebar')

<div class="containerCHAT">
    <div class="chat-containerCHAT">
        <div class="cardCHAT">
            <div class="card-headerCHAT">Chat Support</div>

            <div class="card-bodyCHAT">
                <!-- Chat Display Section -->
                <div id="chat-box" class="chat-boxCHAT">
                    <!-- Loop through chat history -->
                    @foreach ($chats as $chat)
                        @if($chat->is_bot)
                            <!-- Bot Message -->
                            <div class="bot-messageCHAT">
                                <strong>Bot:</strong> {{ e($chat->message) }}
                            </div>
                        @else
                            <!-- User Message -->
                            <div class="user-messageCHAT">
                                <strong>You:</strong> {{ e($chat->message) }}
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- User Input Section -->
                <form id="chat-form" class="chat-formCHAT">
                    @csrf
                    <div class="input-groupCHAT">
                        <input type="text" id="message" class="input-messageCHAT" placeholder="Type your message..." required>
                        <button type="submit" class="send-buttonCHAT">Send</button>
                    </div>
                </form>

                <!-- Suggested Questions Section -->
                <div id="suggested-questions" class="suggested-questionsCHAT" style="display: none;">
                    <h5>Related Questions:</h5>
                    <ul id="questions-list" class="questions-listCHAT"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();

        let message = document.getElementById('message').value;
        let chatBox = document.getElementById('chat-box');

        // Clear the input field
        document.getElementById('message').value = '';

        // Send message to the server
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
            // Update chat history with user's message
            let userMessage = `<div class="user-messageCHAT"><strong>You:</strong> ${message}</div>`;
            chatBox.innerHTML += userMessage;
            chatBox.scrollTop = chatBox.scrollHeight;

            // Handle the response from the server
            if (data.default_response) {
                // Display default response
                let botMessage = `<div class="bot-messageCHAT"><strong>Bot:</strong> ${data.default_response}</div>`;
                chatBox.innerHTML += botMessage;
                chatBox.scrollTop = chatBox.scrollHeight;
            } else if (data.length > 0) {
                // Display related questions
                let questionsList = document.getElementById('questions-list');
                questionsList.innerHTML = ''; // Clear previous questions
                document.getElementById('suggested-questions').style.display = 'block';

                data.forEach(question => {
                    let li = document.createElement('li');
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
        });
    });

    // Fetch the answer when a question is clicked
    function fetchAnswer(questionId) {
        fetch(`/chat/answer/${questionId}`)
        .then(response => response.json())
        .then(data => {
            let chatBox = document.getElementById('chat-box');
            let botMessage = `<div class="bot-messageCHAT"><strong>Bot:</strong> ${data.answer}</div>`;
            chatBox.innerHTML += botMessage;
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    }
</script>

<script src="student/main.js"></script>
@include('student.footer')

<style>
    .containerCHAT {
        max-width: 100%;
        margin: 50px auto;
        padding: 0;
    }

    .chat-containerCHAT {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .cardCHAT {
        border: none;
    }

    .card-headerCHAT {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 15px;
        text-align: center;
        font-size: 1.5rem;
    }

    .card-bodyCHAT {
        padding: 15px;
    }

    .chat-boxCHAT {
        height: 400px;
        width: 100%;
        overflow-y: scroll;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 5px;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
    }

    .bot-messageCHAT, .user-messageCHAT {
        border-radius: 20px;
        padding: 10px 15px;
        margin: 10px 0;
        max-width: 70%;
        width: 600px;
        word-wrap: break-word;
        display: inline-block;
    }

    .bot-messageCHAT {
        background-color: #e2f0d9;
        align-self: flex-start;
    }

    .user-messageCHAT {
        background-color: #d9e8ff;
        align-self: flex-end;
        margin-left: auto;
    }

    .chat-formCHAT {
        margin-top: 15px;
        display: flex;
    }

    .input-groupCHAT {
        flex: 1;
        display: flex;
    }

    .input-messageCHAT {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 20px 0 0 20px;
        outline: none;
        transition: border-color 0.3s;
    }

    .input-messageCHAT:focus {
        border-color: #007bff;
    }

    .send-buttonCHAT {
        padding: 10px 15px;
        border: none;
        background-color: #007bff;
        color: white;
        border-radius: 0 20px 20px 0;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .send-buttonCHAT:hover {
        background-color: #0056b3;
    }

    .suggested-questionsCHAT {
        margin-top: 15px;
    }

    .questions-listCHAT {
        list-style-type: none;
        padding: 0;
    }

    .list-group-itemCHAT {
        cursor: pointer;
        padding: 10px;
        margin: 5px 0;
        border-radius: 5px;
        background-color: #f1f1f1;
        transition: background-color 0.3s;
    }

    .list-group-itemCHAT:hover {
        background-color: #e2e2e2;
    }
</style>
