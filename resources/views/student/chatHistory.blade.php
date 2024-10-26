@include('student.css')
@include('student.sidebar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Style for the main chat container */
    .chat-container {
        padding: 20px;
        border-radius: 10px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Chatbox styling */
    #chatbox {
        border: none;
        width: 100%;
        height: 500px;
        overflow-y: auto;
        margin-top: 10px;
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 8px;
        background-color: #ffffff;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .user-message, .bot-message {
        margin-bottom: 15px;
        display: flex;
    }

    .user-message {
        justify-content: flex-end;
    }

    .bot-message {
        justify-content: flex-start;
    }

    .message-text {
        display: inline-block;
        padding: 10px 15px;
        border-radius: 20px;
        max-width: 75%;
        word-wrap: break-word;
        position: relative;
    }

    .user-message .message-text {
        background-color: #007bff;
        color: white;
    }

    .bot-message .message-text {
        background-color: #e2e3e5;
        color: #333;
    }

    /* Styling for the input form */
    #message-form {
        margin-top: 10px;
        display: flex;
        width: 100%;
    }

    input[type="text"] {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-right: 10px;
    }

    button {
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }

    /* Chat buttons styling */
    .chat-buttons {
        display: flex;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .chat-button {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 15px;
        margin: 5px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .chat-button:hover {
        background-color: #218838;
    }
</style>

<div class="chat-container">
    <h1>Chat Support</h1>

    <div id="chatbox">
        <!-- Chat messages will be appended here -->
        @foreach($chatHistories as $history)
            <div class="{{ $history->user_id == Auth::id() ? 'user-message' : 'bot-message' }}">
                <span class="message-text">{{ $history->user_message }}</span>
            </div>
            @if ($history->bot_response)
                <div class="bot-message">
                    <span class="message-text">{{ $history->bot_response }}</span>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Chat buttons section -->
    <div class="chat-buttons">
        @foreach($chatButtons as $button)
            <button class="chat-button" data-response="{{ $button->keyword }}" data-bot-response="{{ $button->response }}">
                {{ $button->label }}
            </button>
        @endforeach
    </div>

    <form id="message-form" method="POST">
        <input type="text" id="message" name="message" placeholder="Type your message here..." autocomplete="off" required>
        <button type="submit">Send</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // CSRF token for Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Submit the message via AJAX
        $('#message-form').on('submit', function(e) {
            e.preventDefault();

            var message = $('#message').val();
            sendMessage(message);
        });

        // Handle chat button clicks
        $('.chat-button').on('click', function() {
            var userMessage = $(this).data('response'); // Get the user message from data attribute
            var botResponse = $(this).data('bot-response'); // Get the corresponding bot response

            // Append messages
            appendMessage(userMessage, 'user'); // Append user message
            appendMessage(botResponse, 'bot'); // Append bot response

            // Optionally, send the user message to the server
            sendMessage(userMessage);
        });

        // Send message function
        function sendMessage(message) {
            $.ajax({
                type: 'POST',
                url: '{{ route("chat.send") }}',
                data: { message: message },
                success: function(response) {
                    console.log(response); // Log response for debugging
                    // Clear the input field
                    $('#message').val('');

                    // Append the user message (if not already done by the button click)
                    appendMessage(response.user_message, 'user');

                    // Append the bot response
                    appendMessage(response.bot_response, 'bot');

                    // Scroll to the bottom of the chatbox
                    $('#chatbox').scrollTop($('#chatbox')[0].scrollHeight);
                },
                error: function(xhr, status, error) {
                    console.error("Error sending message:", error); // Log errors
                }
            });
        }

        // Function to append messages to the chatbox
        function appendMessage(message, type) {
            var messageClass = type === 'user' ? 'user-message' : 'bot-message';
            $('#chatbox').append('<div class="' + messageClass + '"><span class="message-text">' + message + '</span></div>');
            $('#chatbox').scrollTop($('#chatbox')[0].scrollHeight);
        }
    });
</script>


@include('admin.footer')
<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>
