@include('admin.sidebar', ['user' => Auth::user()])
@include('admin.css')

<style>
    /* General page layout */


    .containerOne {

        background-color: #fff;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .headingOne {
        text-align: center;
        font-size: 28px;
        color: #333;
        margin-bottom: 20px;
    }

    /* Link styles */
    a.addButtonOne {
        display: inline-block;
        margin-bottom: 20px;
        padding: 10px 20px;
        background-color: #4caf50;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 16px;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    a.addButtonOne:hover {
        background-color: #45a049;
    }

    /* Success message styles */
    .alertOne {
        padding: 15px;
        background-color: #4caf50;
        color: white;
        margin-bottom: 20px;
        border-radius: 4px;
        text-align: center;
    }

    /* Table styles */
    .tableOne {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .tableOne th, .tableOne td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .tableOne th {
        background-color: #f2f2f2;
        color: #333;
    }

    .tableOne tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .tableOne tr:hover {
        background-color: #f1f1f1;
    }

    /* Button styles */
    .btnOne {
        padding: 8px 16px;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin-right: 5px;
    }

    .btnWarningOne {
        background-color: #ff9800;
    }

    .btnWarningOne:hover {
        background-color: #e68a00;
    }

    .btnDangerOne {
        background-color: #f44336;
    }

    .btnDangerOne:hover {
        background-color: #e53935;
    }

    form.formOne {
        display: inline;
    }

    /* Responsive layout */
    @media (max-width: 600px) {
        .containerOne {
            padding: 10px;
        }

        .tableOne th, .tableOne td {
            padding: 10px;
        }

        .btnOne {
            padding: 6px 12px;
            font-size: 12px;
        }
    }
</style>

<div class="containerOne">
    <h1 class="headingOne">Chat Buttons</h1>
    <a href="{{url('/chat_buttons_create')}}" class="addButtonOne">Add Chat Button</a>


    <table class="tableOne">
        <thead>
            <tr>
                <th>Label</th>
                <th>Response</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($buttons as $button)
                <tr>
                    <td>{{ $button->label }}</td>
                    <td>{{ $button->response }}</td>
                    <td>
                            <a href="{{ route('chat-buttons.edit', $button) }}" class="btnOne btnWarningOne">Edit</a>
                            <form action="{{ route('chat-buttons.destroy', $button) }}" method="POST" class="formOne">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btnOne btnDangerOne">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<main>
    <section>
        
    </section>
</main>


@include('admin.footer')

    <!--========== MAIN JS ==========-->
    <script src="admin/main.js"></script>
