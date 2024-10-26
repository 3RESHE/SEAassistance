
@include('admin.css')
@include('admin.sidebar', ['user' => Auth::user()])

<!--========== CONTENTS ==========-->

<main>
    <section>
        <div class="new-card">
            <h3 class="new-card__title">Students of : <span style="color: skyblue">{{ $course->course_name }}</span> </h3>

            <!-- Button to open modal -->
            <button id="toggleStudentModalButton" class="new-form__button">Creat New User</button>
            <br>

            <!-- Student Accounts Table -->
            <div class="table-responsive">
                <table class="new-table">
                    <thead>
                        <tr>
                            <th>School ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Course</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->school_id}}</td>
                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->department->department_name }}</td>
                                <td title="{{ $student->course->course_name }}">{{ $student->course->course_acronym }}</td>
                                <td>
                                    <button class="new-btn new-btn-edit">Edit</button>
                                    <button class="new-btn new-btn-delete">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- DASHBOARD -->

        <!-- Upload Excel File Modal -->
        <div id="studentModal" class="new-modal">
            <div class="new-modal__content">
                <span class="new-modal__close" id="closeStudentModalButton">&times;</span>
                <h3 class="new-modal__title">Create Student Account for : <span style="color: skyblue">{{ $course->course_name }}</span> </h3>
                <form action="{{ url('create_student_account') }}" method="POST" enctype="multipart/form-data" class="new-form">
                    @csrf

                    <input type="hidden" name="department_id" value="{{ $department->id }}">
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    <div class="new-form__group">
                        <label for="excel_file" class="new-form__label">Upload Excel File:</label>
                        <input type="file" name="excel_file" id="excel_file" class="new-form__input" accept=".xlsx, .xls" required>
                    </div>

                    <button type="submit" class="new-form__button">Create Student Account</button>
                </form>
            </div>
        </div>
        <!-- END Upload Excel File Modal -->
    </section>
</main>

@include('admin.footer')

<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>
<script>
    // Open modal
    document.getElementById('toggleStudentModalButton').addEventListener('click', function() {
        document.getElementById('studentModal').style.display = 'block';
    });

    // Close modal
    document.getElementById('closeStudentModalButton').addEventListener('click', function() {
        document.getElementById('studentModal').style.display = 'none';
    });

    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById('studentModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
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
