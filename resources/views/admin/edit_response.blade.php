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

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #64b5f6; /* Sky blue */
            outline: none;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .submit-btn {
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

        .back-btn {
            background-color: #ccc; /* Grey background for the back button */
            color: black;
            border: none;
            cursor: pointer;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #bbb; /* Darker grey on hover */
        }
    </style>
</head>
<body>
    <main>
        <section>
            <div class="form-container">
                <h1 class="form-title">Edit Chat Response</h1>

                @if (session('success'))
                    <div style="color: green;">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.updateResponse', $response->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="response">Response:</label>
                        <input type="text" id="response" name="response" value="{{ $response->response }}" required />
                    </div>

                    <div class="form-group">
                        <label for="keywords">Keywords (comma-separated):</label>
                        <input type="text" id="keywords" name="keywords" value="{{ implode(',', $response->keywords->pluck('keyword')->toArray()) }}" required />
                    </div>

                    <div class="button-container">
                        <button type="submit" class="submit-btn">Update Response</button>
                        <a href="{{ url('/view_responses') }}" class="back-btn">Back to Responses</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
    @include('admin.footer')
    <!--========== MAIN JS ==========-->
    <script src="admin/main.js"></script>
</body>
</html>
