@include('admin.css')
@include('admin.sidebar', ['user' => Auth::user()])

<!--========== CONTENTS ==========-->

<main>
    <section>
        <!-- DASHBOARD -->
        <div class="new-card">
            <h3 class="new-card__title">Manage Admins</h3>

            <!-- Button to open "Add Admin" modal -->
            <button id="toggleAdminModalButton" class="new-form__button">Add Admin</button>

            <!-- Admins Table -->
            <div class="table-responsive">
                <table class="new-table">
                    <thead>
                        <tr>
                            <th>School ID</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                <td>{{ $admin->school_id }}</td>
                                <td>{{ $admin->name }} {{ $admin->last_name }}</td>
                                <td>{{ $admin->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END DASHBOARD -->

<!-- Add Admin Modal -->
<div id="adminModal" class="modal">
    <div class="modal-content">
        <span class="close-button" id="closeAdminModalButton">&times;</span>
        <h3 class="modal-title">Add Admin</h3>
        <form action="{{ url('create_admin') }}" method="POST" class="modal-form">
            @csrf

            <div class="form-group">
                <label for="school_id">School ID:</label>
                <div class="input-container">
                    <input type="text" name="school_id" id="school_id" class="form-input" placeholder="Enter School ID" required>
                </div>
            </div>

            <div class="form-group">
                <label for="first_name">First Name:</label>
                <div class="input-container">
                    <input type="text" name="first_name" id="first_name" class="form-input" placeholder="Enter First Name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <div class="input-container">
                    <input type="text" name="last_name" id="last_name" class="form-input" placeholder="Enter Last Name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name:</label>
                <div class="input-container">
                    <input type="text" name="middle_name" id="last_name" class="form-input" placeholder="Enter Middle Name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="name_suffix">Name Suffix:</label>
                <div class="input-container">
                    <input type="text" name="name_suffix" id="name_suffix" class="form-input" placeholder="Enter Name Suffix" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <div class="input-container">
                    <input type="email" name="email" id="email" class="form-input" placeholder="Enter Email" required>
                </div>
            </div>

            <button type="submit" class="form-button">Create Admin</button>
        </form>
    </div>
</div>
<!-- END Add Admin Modal -->




    </section>
</main>

@include('admin.footer')

<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>

<script>
// Open "Add Admin" modal
document.getElementById('toggleAdminModalButton').onclick = function () {
    document.getElementById('adminModal').style.display = 'block';
};

// Close "Add Admin" modal
document.getElementById('closeAdminModalButton').onclick = function () {
    document.getElementById('adminModal').style.display = 'none';
};

// Close modal by clicking outside of it
window.onclick = function (event) {
    const modal = document.getElementById('adminModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};

</script>

<style>
    /* New Styles */
    .new-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .new-card__title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .new-form__button {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .new-form__button:hover {
        background-color: #0056b3;
    }

    .new-table {
        width: 100%;
        border-collapse: collapse;
    }

    .new-table th, .new-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .new-btn {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .new-btn:hover {
        background-color: #218838;
    }

    .new-btn-delete {
        background-color: #dc3545;
    }

    .new-btn-delete:hover {
        background-color: #c82333;
    }   
/* Modal Styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #ffffff; /* White background */
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    width: 90%; /* Could be more or less, depending on screen size */
    max-width: 500px; /* Max width for larger screens */
}

/* Modal Title */
.modal-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333; /* Dark text color */
}

/* Close Button */
.close-button {
    color: #ff5c5c; /* Red color */
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s;
}

.close-button:hover {
    color: #ff1e1e; /* Darker red on hover */
}

/* Form Styles */
.modal-form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: bold; /* Bold labels */
    margin-bottom: 5px;
}

.input-container {
    position: relative; /* Position for the placeholder */
}

.form-input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px; /* Rounded corners */
    font-size: 16px;
    width: 100%; /* Full width */
    box-sizing: border-box; /* Include padding and border in the element's total width */
}

/* Placeholder style */
.placeholder {
    position: absolute;
    left: 10px; /* Align with input */
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: #999; /* Light gray color */
    pointer-events: none; /* Prevent interaction */
}

/* Submit Button */
.form-button {
    background-color: #007bff; /* Bootstrap primary color */
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.form-button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

/* Animation */
@keyframes slideIn {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
