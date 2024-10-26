@include('secretary.css')

@include('secretary.sidebar')

<br>
<main>
    <section>

        <!-- Card for Single Subject Addition -->
        <div class="card">
            <h3 class="card__title">Add Single Subject for: <br>{{$courses->course_name}}</h3>
            <form id="single-subject-form" action="{{url('single_create_subjects')}}" method="POST" class="form">
                @csrf
                <input type="hidden" name="department_id" value="{{ $department->id }}">
                <input type="hidden" name="course_id" value="{{ $courses->id }}">

                <div class="form__group">
                    <label for="subject_code" class="form__label">Subject Code</label>
                    <input type="text" name="subject_code" id="subject_code" class="form__input" required>
                </div>

                <div class="form__group">
                    <label for="descriptive_title" class="form__label">Descriptive Title</label>
                    <input type="text" name="descriptive_title" id="descriptive_title" class="form__input" required>
                </div>

                <div class="form__group">
                    <label for="units" class="form__label">Units</label>
                    <input type="number" name="units" min="1" id="units" class="form__input" required>
                </div>

                <button type="submit" class="form__button">Add Subject</button>
            </form>
        </div>

        <!-- Card for Bulk Upload -->
        <div class="card">
            <h3 class="card__title">Upload Subjects for: <br>{{$courses->course_name}}</h3>
            <form id="bulk-upload-form" action="{{url('create_subjects')}}" method="POST" enctype="multipart/form-data" class="form">
                @csrf
                <input type="hidden" name="department_id" value="{{ $department->id }}">
                <input type="hidden" name="course_id" value="{{ $courses->id }}">

                <div class="form__group">
                    <label for="excel_file" class="form__label">Upload Excel File</label>
                    <input type="file" name="excel_file" id="excel_file" class="form__input" accept=".xls,.xlsx" required>
                </div>

                <button type="submit" class="form__button">Upload</button>
            </form>
        </div>

        
    </section>
</main>

@include('secretary.footer')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="secretary/main.js"></script>

<script>
    // Intercept the Single Subject form submission
    document.getElementById('single-subject-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form submission
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to add a new subject.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // Submit the form if confirmed
            }
        });
    });

    // Intercept the Bulk Upload form submission
    document.getElementById('bulk-upload-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form submission

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to upload a new subject list.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, upload it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // Submit the form if confirmed
            }
        });
    });
</script>
</body>
</html>
