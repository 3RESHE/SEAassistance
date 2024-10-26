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

    /* Form styles */
    .formGroupOne {
        margin-bottom: 15px;
    }

    .formGroupOne label {
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
        color: #333;
    }

    .formControlOne {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .btnPrimaryOne {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .btnPrimaryOne:hover {
        background-color: #0069d9;
    }

    /* Responsive layout */
    @media (max-width: 600px) {
        .containerOne {
            padding: 10px;
        }

        .formControlOne {
            padding: 8px;
        }

        .btnPrimaryOne {
            padding: 8px 16px;
            font-size: 14px;
        }
    }
</style>

<main>
    <section>
        <div class="containerOne">
            <h1 class="headingOne">Edit Chat Button</h1>
            <!-- Form will submit to the update route with the existing button's ID -->
            <form action="{{ route('chat-buttons.update', $chatButton->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="formGroupOne">
                    <label for="label">Button Label</label>
                    <input type="text" name="label" class="formControlOne" value="{{ $chatButton->label }}" required>
                </div>
                <div class="formGroupOne">
                    <label for="response">Response</label>
                    <input type="text" name="response" class="formControlOne" value="{{ $chatButton->response }}" required>
                </div>
                <button type="submit" class="btnPrimaryOne">Update</button>
            </form>
        </div>
    </section>
</main>

@include('admin.footer')

<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>
