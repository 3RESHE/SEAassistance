@include('admin.css')

@include('admin.sidebar', ['user' => Auth::user()])

<!--========== CONTENTS ==========-->

<main>
    <section>
        <!-- DASHBOARD -->
        <div class="card">
            <h3 class="card__title">Create Course for: "{{$department->department_name}}"</h3>
            <form id="courseForm" action="{{ url('create_course', $department->id) }}" method="POST" enctype="multipart/form-data" class="form">
                @csrf

                <div class="form__group">
                    <label for="course_name" class="form__label">Course Name</label>
                    <input type="text" name="course_name" id="course_name" class="form__input" placeholder="Enter Course Name" required>
                </div>

                <div class="form__group">
                    <label for="course_acronym" class="form__label">Course Acronym</label>
                    <input type="text" name="course_acronym" id="course_acronym" class="form__input" placeholder="Enter Course Acronym" required>
                </div>

                <button type="submit" class="form__button">Submit</button>
            </form>
        </div>
        <!-- DASHBOARD -->
    </section>
</main>
@include('admin.footer')

<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('courseForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Show SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to create this course?',
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
