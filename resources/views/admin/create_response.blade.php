@include('admin.css')
@include('admin.sidebar', ['user' => Auth::user()])

<style>
    .form-container {
        max-width: 500px;
        margin: 20px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #64b5f6; /* Sky blue */
        outline: none;
    }

    .form-group textarea {
        resize: vertical; /* Allow vertical resizing only */
        height: 100px; /* Set a default height */
    }

    .submit-btn {
        display: inline-block;
        padding: 10px 15px;
        background-color: #64b5f6; /* Sky blue */
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .submit-btn:hover {
        background-color: #42a5f5; /* Darker blue on hover */
    }

    .submit-btn:active {
        background-color: #2196f3; /* Even darker blue on click */
    }
</style>

<main>
    <section>
        <div class="form-container">
            <h2 class="form-title">Add Response</h2>
            <form action="{{url('/save_response')}}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="keywords">Keywords (comma-separated):</label>
                    <input type="text" id="keywords" name="keywords" placeholder="keyword1, keyword2, keyword3" required />
                </div>

                <div class="form-group">
                    <label for="response">Response:</label>
                    <textarea id="response" name="response" placeholder="Enter your response here..." required></textarea>
                </div>
                <button type="submit" class="submit-btn">Save Response</button>
            </form>
        </div>

    </section>
</main>


@include('admin.footer')
<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>
</body>
</html>
