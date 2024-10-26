<div>
    <h1>All Messages</h1>
    <ul>
        @foreach($messages as $message)
            <li>
                <strong>{{ $message->sender->name }}:</strong> {{ $message->content }}
                <small>{{ $message->created_at->diffForHumans() }}</small>
            </li>
        @endforeach
    </ul>
</div>