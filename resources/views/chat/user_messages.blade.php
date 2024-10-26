<div>
    <h1>Chat with {{ $user->name }}</h1>
    <div id="chatMessages" style="max-width: 600px; margin: auto;">
        <ul style="list-style-type: none; padding: 0;">
            @foreach($messages as $message)
                <li class="{{ $message->sender_id == auth()->id() ? 'message-sent' : 'message-received' }}" 
                    data-message-id="{{ $message->id }}" style="word-wrap: break-word; max-width: 100%;">
                    <strong>{{ $message->sender->name }}:</strong> 
                    <span style="word-wrap: break-word;">{{ $message->content }}</span>
                    <br>
                    <small>{{ $message->created_at->format('Y-m-d H:i') }}</small>
                </li>
            @endforeach
        </ul>
    </div>

    <form id="replyForm" method="POST" style="max-width: 600px; margin: auto;">
        @csrf
        <input type="text" name="content" placeholder="Type your reply here" required style="width: calc(100% - 100px);">
        <button type="submit">Send</button>
    </form>
</div>

<script>
    $(document).off('submit', '#replyForm').on('submit', '#replyForm', function (e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var $button = $(this).find('button');

        $button.prop('disabled', true);

        $.ajax({
            url: '/chat/reply/' + '{{ $user->id }}',
            method: 'POST',
            data: formData,
            success: function (response) {
                console.log("Response received:", response);
                var existingMessages = $('#chatMessages ul li').map(function() {
                    return $(this).data('message-id');
                }).get();

                if (!existingMessages.includes(response.message.id)) {
                    var messageHtml = '<li class="message-sent" data-message-id="' + response.message.id + '" style="word-wrap: break-word; max-width: 100%;">' +
                                      '<strong>' + response.message.sender.name + ':</strong> <span style="word-wrap: break-word;">' + response.message.content + '</span><br>' +
                                      '<small>' + new Date(response.message.created_at).toLocaleString() + '</small></li>';
                    $('#chatMessages ul').append(messageHtml);
                    $('input[name="content"]').val('');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error sending message:", textStatus, errorThrown);
                alert('Could not send message. Please try again.');
            },
            complete: function () {
                $button.prop('disabled', false);
            }
        });
    });
</script>

<style>
    /* Additional styles for the chat */
    .message-sent, .message-received {
        padding: 10px;
        margin: 5px 0;
        border-radius: 10px;
        overflow-wrap: break-word; /* For better wrapping support */
    }

    .message-sent {
        background-color: #d1e7dd; /* Light green background for sent messages */
        text-align: right; /* Align sent messages to the right */
    }

    .message-received {
        background-color: #f8d7da; /* Light red background for received messages */
        text-align: left; /* Align received messages to the left */
    }

    #replyForm {
        display: flex;
        gap: 10px; /* Space between input and button */
    }

    input[type="text"] {
        flex-grow: 1; /* Allow input to take available space */
    }
</style>
