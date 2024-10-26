
@include('admin.css')


@include('admin.sidebar', ['user' => Auth::user()])

<!--========== CONTENTS ==========-->

<main>
    <section>
<!-- DASHBOARD -->
{{-- <div class="card">
    <h3 class="card__title">Create Student Account</h3>
    <form action="/your-submit-url" method="POST" enctype="multipart/form-data" class="form">
    
    <!-- Hidden Fields -->
    <input type="hidden" name="department" id="department" value="hidden_department_value">
    <input type="hidden" name="course" id="course" value="hidden_course_value">
    
    <div class="form__group">
        <label for="year" class="form__label">Year</label>
        <select name="year" id="year" class="form__input" required>
            <option value="" disabled selected>Select Year</option>
            <option value="1st">1st Year</option>
            <option value="2nd">2nd Year</option>
            <option value="3rd">3rd Year</option>
            <option value="4th">4th Year</option>
        </select>
    </div>

    <div class="form__group">
        <label for="excel_file" class="form__label">Upload Excel File</label>
        <input type="file" name="excel_file" id="excel_file" class="form__input" accept=".xlsx, .xls" required>
    </div>
    
    <button type="submit" class="form__button">Create Student Account</button>
    </form>
    </div> --}}
  <!-- DASHBOARD -->


  
@include('admin.css')


@include('admin.sidebar', ['user' => Auth::user()])

<!--========== CONTENTS ==========-->

<main>
    <section>


<!-- DEPARTMENTS -->

<div class="big-card">
    <h1 style="text-align: center;">DEPARTMENTS</h1>
    <div class="department-cards">
        @foreach($departments as $department)
            <a href="{{url('add_student_course', $department->id)}}" class="department-card-link">
                <div class="department-card">
                    <!-- Adjust the image path based on your storage setup -->
                    <img src="{{ asset('images/' . $department->department_logo) }}" alt="{{ $department->department_name }} Logo" class="department-logo">
                    <span class="department-name">{{ $department->department_name }}</span>
                </div>
            </a>
        @endforeach
    </div>
</div>


<!-- DEPARTMENTS -->


        





</section>
</main>

    <!--========== MAIN JS ==========-->
    <script src="admin/main.js"></script>
</body>
</html>





        



</section>
</main>


@include('admin.footer')


    <!--========== MAIN JS ==========-->
    <script src="admin/main.js"></script>
</body>
</html>
