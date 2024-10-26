@include('admin.css')
@include('admin.sidebar', ['user' => Auth::user()])

<style>
    .table th {
        background-color: #f9f9f9;
    }

    .table td {
        border: 1px solid #ccc;
        padding: 8px;
        position: relative;
        overflow: hidden;
        text-overflow: ellipsis; /* This can be removed since we want to show all text */
    }

    .response-cell {
        max-width: 200px; /* You can adjust this as needed */
        overflow: hidden;
        white-space: normal; /* Allow text to wrap */
        cursor: pointer; /* Change cursor to pointer for clickable effect */
        color: #007bff; /* Optional: change color to indicate it's clickable */
    }

    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0, 0, 0); /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
    }

    .modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
    max-height: 80vh; /* Maximum height of the modal */
    overflow-y: auto; /* Enable vertical scrolling if content exceeds max height */
    word-wrap: break-word; /* Allow long words to break and wrap to the next line */
}


    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<div class="card1">
    <h2>Existing Responses</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Keywords</th>
                <th>Response</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($responses as $response)
                <tr>
                    <td data-label="Keywords">
                        {{ implode(', ', $response->keywords->pluck('keyword')->toArray()) }}
                    </td>
                    <td data-label="Response" class="response-cell" onclick="openModal('{{ $response->response }}')">
                        <strong>{{ $response->response }}</strong>
                    </td>
                    <td data-label="Actions">
                        <form action="{{ url('/deleteResponse', $response->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background-color: #f44336; color: white; border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer;">Delete</button>
                        </form>
                        <a href="{{ route('admin.editResponse', $response->id) }}" style="margin-left: 10px; color: #64b5f6;">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Structure -->
<div id="responseModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Response Details</h2>
        <p id="modalResponseContent"></p>
    </div>
</div>

<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>

<script>
    function openModal(response) {
        document.getElementById('modalResponseContent').innerText = response;
        document.getElementById('responseModal').style.display = "block";
    }

    function closeModal() {
        document.getElementById('responseModal').style.display = "none";
    }

    // Close the modal when the user clicks anywhere outside of the modal
    window.onclick = function(event) {
        const modal = document.getElementById('responseModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

@include('admin.footer')