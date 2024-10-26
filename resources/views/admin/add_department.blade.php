@include('admin.css')

@include('admin.sidebar', ['user' => Auth::user()])

<!--========== CONTENTS ==========-->

<main>
    <section>

        <!-- Add Department -->
        <div class="card">
            <h3 class="card__title">Create Department</h3>
            <form id="departmentForm" action="{{ url('create_department') }}" method="POST" enctype="multipart/form-data" class="form">
                @csrf
                <div class="form__group">
                    <label for="department_name" class="form__label">Department Name</label>
                    <input type="text" name="department_name" id="department_name" class="form__input" placeholder="Enter Department Name" required>
                </div>
                <div class="form__group">
                    <label for="department_logo" class="form__label">Department Logo</label>
                    <input type="file" name="department_logo" id="department_logo" class="form__input" required>
                </div>
                <button type="submit" class="form__button">Add Department</button>
            </form>
        </div>

    </section>
</main>
@include('admin.footer')

<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('departmentForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Show SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to create this department?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, create it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // Submit the form if confirmed
            }
        });
    });
</script>
</body>
</html>
