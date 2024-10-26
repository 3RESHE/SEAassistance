<style>
    /* Ensure messages are displayed properly with wrapping */
    ul {
        list-style-type: none; /* Remove bullet points */
        padding: 0; /* Remove padding */
        margin: 0; /* Remove margin */
    }

    li {
        word-wrap: break-word; /* Allow breaking of long words */
        overflow-wrap: break-word; /* Ensure long words break */
        white-space: pre-wrap; /* Preserve whitespace and wrap lines */
        margin-bottom: 10px; /* Space between messages */
        max-width: 80%; /* Limit the width of messages */
        word-break: break-all; /* Break long words to fit within max-width */
    }

    /* Styling for sent messages */
    .message-sent {
        background-color: #007bff;
        padding: 10px; /* Add padding */
        border-radius: 5px; /* Rounded corners */
        align-self: flex-end; /* Align to the right */
    }

    /* Styling for received messages */
    .message-received {
        background-color: #f0f0f0; /* Light gray background */
        padding: 10px; /* Add padding */
        border-radius: 5px; /* Rounded corners */
        align-self: flex-start; /* Align to the left */
    }
</style>

<ul>
    @foreach($messages as $message)
        <li class="{{ $message->sender_id == auth()->id() ? 'message-sent' : 'message-received' }}" 
            data-message-id="{{ $message->id }}">
            <strong>{{ $message->sender->name }}:</strong> 
            {{ $message->content }} <br>
            <small>{{ $message->created_at->format('Y-m-d H:i') }}</small>
        </li>
    @endforeach
</ul>

<form id="replyForm" method="POST">
    @csrf
    <input type="text" name="content" placeholder="Type your reply here" required>
    <button type="submit">Send</button>
</form>

<script>
    function formatMessageContent(content) {
        return content.replace(/(.{15})/g, '$1<br>').trim(); // Insert <br> after every 15 characters
    }

    $(document).off('submit', '#replyForm').on('submit', '#replyForm', function (e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = $(this).serialize(); // Get form data
        var $button = $(this).find('button'); // Get the button

        // Disable the button to prevent multiple submissions
        $button.prop('disabled', true);

        $.ajax({
            url: '/chat/reply/' + '{{ $user->id }}', // Adjust URL to include the user ID
            method: 'POST',
            data: formData,
            success: function (response) {
                console.log("Response received:", response); // Log response
                // Check if the message is already in the list to avoid duplicates
                var existingMessages = $('#chatMessages ul li').map(function() {
                    return $(this).data('message-id'); // Use data attribute for message ID
                }).get();

                if (!existingMessages.includes(response.message.id)) {
                    // Format the message content before appending it
                    var formattedContent = formatMessageContent(response.message.content);

                    var messageHtml = '<li class="message-sent" data-message-id="' + response.message.id + '">' +
                                      '<strong>' + response.message.sender.name + ':</strong> ' + formattedContent + '<br>' +
                                      '<small>' + new Date(response.message.created_at).toLocaleString() + '</small></li>';
                    $('#chatMessages ul').append(messageHtml); // Append the new message to the chat
                    $('input[name="content"]').val(''); // Clear the input field
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error sending message:", textStatus, errorThrown); // Log error
                alert('Could not send message. Please try again.');
            },
            complete: function () {
                // Re-enable the button after the AJAX call completes
                $button.prop('disabled', false);
            }
        });
    });
</script>
