@include('admin.css')
@include('admin.sidebar', ['user' => Auth::user()])

<!--========== CONTENTS ==========-->

<main>
    <section>
        <!-- DASHBOARD -->
        <div class="new-card">
            <h3 class="new-card__title">Create Secretary</h3>

            <!-- Button to open modal -->
            <button id="toggleSecretaryModalButton" class="new-form__button">Add Secretary</button>

            <!-- Secretary Table -->
            <div class="table-responsive">
                <table class="new-table">
                    <thead>
                        <tr>
                            <th>School ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>School ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($secretaries as $secretary)
                            <tr>
                                <td>{{ $secretary->school_id }}</td>
                                <td>{{ $secretary->name }} {{ $secretary->last_name }}</td>
                                <td>{{ $secretary->email }}</td>
                                <td>{{ $secretary->school_id }}</td>
                                <td>
                                    <button class="new-btn new-btn-edit" 
                                            data-id="{{ $secretary->id }}" 
                                            data-first-name="{{ $secretary->name }}" 
                                            data-last-name="{{ $secretary->last_name }}" 
                                            data-middle-name="{{ $secretary->middle_name }}" 
                                            data-name-suffix="{{ $secretary->name_suffix }}" 
                                            data-email="{{ $secretary->email }}" 
                                            data-school-id="{{ $secretary->school_id }}" 
                                            data-departments="{{ $secretary->departments->pluck('id')->implode(',') }}"
                                            onclick="openEditModal(this)">Edit</button>
                                    <button class="new-btn new-btn-delete" onclick="confirmDelete({{ $secretary->id }})">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- DASHBOARD -->

        <!-- Add Secretary Modal -->
        <div id="secretaryModal" class="new-modal">
            <div class="new-modal__content">
                <span class="new-modal__close" id="closeSecretaryModalButton">&times;</span>
                <h3 class="new-modal__title">Add Secretary</h3>
                <form action="{{ url('create_secretary') }}" method="POST" class="new-form">
                    @csrf

                    <div class="new-form__group">
                        <label for="school_id" class="new-form__label">School ID:</label>
                        <input type="text" name="school_id" id="school_id" class="new-form__input" placeholder="Enter School ID" required>
                    </div>

                    <div class="new-form__group">
                        <label for="email" class="new-form__label">Email:</label>
                        <input type="email" name="email" id="email" class="new-form__input" placeholder="Enter Email" required>
                    </div>

                    <div class="new-form__group">
                        <label for="first_name" class="new-form__label">First Name:</label>
                        <input type="text" name="first_name" id="first_name" class="new-form__input" placeholder="Enter First Name" required>
                    </div>

                    <div class="new-form__group">
                        <label for="last_name" class="new-form__label">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" class="new-form__input" placeholder="Enter Last Name" required>
                    </div>

                    <div class="new-form__group">
                        <label for="middle_name" class="new-form__label">Middle Name:</label>
                        <input type="text" name="middle_name" id="middle_name" class="new-form__input" placeholder="Enter Middle Name">
                    </div>

                    <div class="new-form__group">
                        <label for="name_suffix" class="new-form__label">Name Suffix:</label>
                        <input type="text" name="name_suffix" id="name_suffix" class="new-form__input" placeholder="Enter Name Suffix">
                    </div>

                    <div class="new-form__group">
                        <label for="department_ids" class="new-form__label">Departments:</label>
                        @foreach($departments as $department)
                            <div class="new-form__checkbox">
                                <label>
                                    <input type="checkbox" name="department_ids[]" value="{{ $department->id }}">
                                    {{ $department->department_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="new-form__button">Create Secretary</button>
                </form>
            </div>
        </div>
        <!-- END Add Secretary Modal -->

        <!-- Edit Secretary Modal -->
        <div id="editSecretaryModal" class="new-modal">
            <div class="new-modal__content">
                <span class="new-modal__close" id="closeEditSecretaryModalButton">&times;</span>
                <h3 class="new-modal__title">Edit Secretary</h3>
                <form id="editSecretaryForm" action="{{ url('update_secretary') }}" method="POST" class="new-form">
                    @csrf
                    @method('PUT') <!-- Indicating it's an update -->

                    <input type="hidden" name="id" id="edit_id"> <!-- Hidden field for the secretary's ID -->

                    <div class="new-form__group">
                        <label for="edit_school_id" class="new-form__label">School ID:</label>
                        <input type="text" name="school_id" id="edit_school_id" class="new-form__input" placeholder="Enter School ID" readonly required>
                    </div>

                    <div class="new-form__group">
                        <label for="edit_email" class="new-form__label">Email:</label>
                        <input type="email" name="email" id="edit_email" class="new-form__input" placeholder="Enter Email" readonly required>
                    </div>

                    <div class="new-form__group">
                        <label for="edit_first_name" class="new-form__label">First Name:</label>
                        <input type="text" name="first_name" id="edit_first_name" class="new-form__input" placeholder="Enter First Name" readonly required>
                    </div>

                    <div class="new-form__group">
                        <label for="edit_last_name" class="new-form__label">Last Name:</label>
                        <input type="text" name="last_name" id="edit_last_name" class="new-form__input" placeholder="Enter Last Name" readonly required>
                    </div>

                    <div class="new-form__group">
                        <label for="edit_middle_name" class="new-form__label">Middle Name:</label>
                        <input type="text" name="middle_name" id="edit_middle_name" class="new-form__input" readonly placeholder="Enter Middle Name">
                    </div>

                    <div class="new-form__group">
                        <label for="edit_name_suffix" class="new-form__label">Name Suffix:</label>
                        <input type="text" name="name_suffix" id="edit_name_suffix" class="new-form__input" readonly placeholder="Enter Name Suffix">
                    </div>

                    <div class="new-form__group">
                        <label for="edit_department_ids" class="new-form__label">Departments:</label>
                        @foreach($departments as $department)
                            <div class="new-form__checkbox">
                                <label>
                                    <input type="checkbox" name="department_ids[]" value="{{ $department->id }}">
                                    {{ $department->department_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="new-form__button">Update Secretary</button>
                </form>
            </div>
        </div>
        <!-- END Edit Secretary Modal -->

    </section>
</main>

@include('admin.footer')

<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert library -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert library -->

<script>
    // Open "Add Secretary" modal
    document.getElementById('toggleSecretaryModalButton').onclick = function () {
        document.getElementById('secretaryModal').style.display = 'block';
    };

    // Close "Add Secretary" modal
    document.getElementById('closeSecretaryModalButton').onclick = function () {
        document.getElementById('secretaryModal').style.display = 'none';
    };

    // Open "Edit Secretary" modal with pre-filled data
    function openEditModal(button) {
        const id = button.getAttribute('data-id');
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_school_id').value = button.getAttribute('data-school-id');
        document.getElementById('edit_email').value = button.getAttribute('data-email');
        document.getElementById('edit_first_name').value = button.getAttribute('data-first-name');
        document.getElementById('edit_last_name').value = button.getAttribute('data-last-name');
        document.getElementById('edit_middle_name').value = button.getAttribute('data-middle-name');
        document.getElementById('edit_name_suffix').value = button.getAttribute('data-name-suffix');

        // Check the corresponding department checkboxes
        const departments = button.getAttribute('data-departments').split(',');
        const checkboxes = document.querySelectorAll('input[name="department_ids[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = departments.includes(checkbox.value);
        });

        document.getElementById('editSecretaryModal').style.display = 'block';
    }

    // Close "Edit Secretary" modal
    document.getElementById('closeEditSecretaryModalButton').onclick = function () {
        document.getElementById('editSecretaryModal').style.display = 'none';
    };

    // Confirm delete using SweetAlert
    function confirmDelete(secretaryId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete route
                window.location.href = `/delete_secretary/${secretaryId}`;
            }
        });
    }

    // Add confirmation for creating a secretary
    document.querySelector('.new-form').onsubmit = function (event) {
        event.preventDefault(); // Prevent the form from submitting immediately

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to add a new secretary.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if confirmed
                this.submit();
            }
        });
    };

    // Add confirmation for updating a secretary
    document.getElementById('editSecretaryForm').onsubmit = function (event) {
        event.preventDefault(); // Prevent the form from submitting immediately

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to update this secretary's information.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if confirmed
                this.submit();
            }
        });
    };
</script>



<style>
    /* New Styles */
    .new-card {
        background-color: #ffffff; /* Card background */
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
        background-color: #007bff; /* Button color */
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .new-form__button:hover {
        background-color: #0056b3; /* Darker button color on hover */
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
        background-color: #28a745; /* Green button */
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .new-btn:hover {
        background-color: #218838; /* Darker green on hover */
    }

    .new-btn-edit {
        background-color: #007bff; /* Blue edit button */
    }

    .new-btn-edit:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    .new-btn-delete {
        background-color: #dc3545; /* Red delete button */
    }

    .new-btn-delete:hover {
        background-color: #c82333; /* Darker red on hover */
    }

    /* Modal Styles */
    .new-modal {
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

    .new-modal__content {
        background: linear-gradient(to bottom right, #fdfdfd, #eaeaea); /* Gradient background */
        margin: 10% auto; /* Center modal */
        padding: 20px;
        border: 1px solid #888;
        width: 90%; /* Responsive width */
        max-width: 500px; /* Set a maximum width */
        border-radius: 15px; /* Rounded corners */
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3); /* Shadow for depth */
        animation: fadeIn 0.5s; /* Animation for opening */
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .new-modal__close {
        color: #ff5c5c; /* Close button color */
        float: right;
        font-size: 28px;
        font-weight: bold;
        transition: color 0.3s;
    }

    .new-modal__close:hover,
    .new-modal__close:focus {
        color: #ff1e1e; /* Change close button color on hover */
        text-decoration: none;
        cursor: pointer;
    }

    .new-modal__title {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        color: #333; /* Title color */
    }

    .new-form__group {
        display: flex;
        flex-direction: column; /* Align items in a column */
        margin-bottom: 15px; /* Space between form groups */
    }

    .new-form__label {
        margin-bottom: 5px; /* Space between label and input */
        font-weight: bold; /* Bold labels for clarity */
        color: #333; /* Label color */
    }

    .new-form__input {
        padding: 10px; /* Input padding */
        border: 1px solid #ccc; /* Border styling */
        border-radius: 5px; /* Rounded input corners */
        font-size: 16px; /* Font size */
    }

    .new-form__input:focus {
        border-color: #007bff; /* Border color on focus */
        outline: none; /* Remove outline */
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .new-card {
            padding: 15px; /* Smaller padding */
        }

        .new-modal__content {
            width: 95%; /* Use more width on small screens */
        }

        .new-form__button {
            width: 100%; /* Full width button on small screens */
        }
    }
</style>

</body>
</html>
