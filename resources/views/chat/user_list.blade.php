@include('admin.css')
@include('admin.sidebar', ['user' => Auth::user()])

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    main {
        display: flex;
        height: 100vh;
    }

    #userList {
        width: 300px;
        background-color: #fff;
        border-right: 1px solid #dcdcdc;
        overflow-y: auto;
        padding: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    #userList h1 {
        text-align: center;
        font-size: 1.5rem;
        color: #007bff;
        margin-bottom: 20px;
    }

    #userList ul {
        list-style: none;
        padding: 0;
    }

    #userList li {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        background-color: #f7f7f7;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #userList li:hover {
        background-color: #e0f7fa;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 10px;
    }

    #chatContainer {
        flex: 1;
        display: flex;
        flex-direction: column;
        background-color: #fff;
        padding: 20px;
        position: relative;
    }

    #chatHeader {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: #333;
    }

    #chatMessages {
        flex: 1;
        overflow-y: auto;
        border: 1px solid #dcdcdc;
        border-radius: 8px;
        background-color: #f9f9f9;
        padding: 10px;
        margin-bottom: 10px;
        position: relative;
    }

    #chatMessages ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    #chatMessages li {
        padding: 10px;
        border-radius: 20px;
        margin-bottom: 10px;
        position: relative;
        max-width: 80%;
    }

    .message-sent {
        background-color: #007bff;
        color: white;
        align-self: flex-end;
        margin-left: auto;
        border-bottom-left-radius: 0;
    }

    .message-received {
        background-color: #e0e0e0;
        color: black;
        align-self: flex-start;
        border-bottom-right-radius: 0;
    }

    form {
        display: flex;
        margin-top: 10px;
    }

    input[type="text"] {
        flex: 1;
        padding: 10px;
        border: 1px solid #dcdcdc;
        border-radius: 20px;
        margin-right: 10px;
        outline: none;
        font-size: 1rem;
    }

    input[type="text"]:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 1rem;
    }

    button:hover {
        background-color: #0056b3;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        #userList {
            width: auto;
            border-right: none;
            border-bottom: 1px solid #dcdcdc;
        }

        #chatContainer {
            padding: 10px;
        }

        #chatMessages {
            padding: 5px;
        }

        button {
            padding: 10px;
        }

        /* Hide username on small devices */
        .username {
            display: none;
        }
    }
</style>

<main>
    <section id="userList">
        <h1>Users</h1>
        <ul>
            @foreach($users as $user)
                <li>
                    <a href="#" class="user-link" data-id="{{ $user->id }}" style="display: flex; align-items: center; width: 100%; text-decoration: none; color: inherit;">
                        <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <span class="username">{{ $user->name }}</span> <!-- Wrapped username in a span -->
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
    
    <section id="chatContainer">
        <h1 id="chatHeader">Select a user to start chatting</h1>
        <div id="chatMessages">
            <ul>
                <!-- Messages will be loaded here -->
            </ul>
        </div>
        <form id="replyForm" method="POST" style="display: none;">
            @csrf
            <input type="text" name="content" placeholder="Type your reply here" required>
            <button type="submit">Send</button>
        </form>
    </section>
</main>

<br>

@include('admin.footer')

<script src="admin/main.js"></script>

<script>
    $(document).on('click', '.user-link', function (e) {
        e.preventDefault();
        
        var userId = $(this).data('id');
        var username = $(this).find('.username').text(); // Get the username from the span

        $.ajax({
            url: '/ChatView/' + userId,
            method: 'GET',
            success: function (data) {
                $('#chatMessages').html(data);
                $('#replyForm').attr('data-user-id', userId).show();
                $('#chatHeader').text('Chat with ' + username); // Update chat header correctly
                $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
            },
            error: function () {
                alert('Could not load messages. Please try again.');
            }
        });
    });
</script>
