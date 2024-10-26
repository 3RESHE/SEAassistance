@include('evaluator.css')
@include('evaluator.sidebar')


<!--========== CONTENTS ==========-->

<main>
    <section>
<!-- DASHBOARD -->
<div class="big-card">
    <h1 style="text-align: center;">{{ $department->department_name }} COURSES</h1>
    <div class="department-cards">
        @foreach($courses as $course)
            <a href="{{ url('view_students' , $course->id) }}" class="department-card-link">
                <div class="department-card">
                    <!-- Adjust the image path based on your storage setup -->
                    <img src="{{ asset('images/' . $department->department_logo) }}" alt="{{ $department->department_name }} Logo" class="department-logo">
                    <span class="department-name">{{ $course->course_name }}</span>
                </div>
            </a>
        @endforeach
    </div>
</div>

  <!-- DASHBOARD -->




        






</section>
</main>

@include('secretary.footer')

    <!--========== MAIN JS ==========-->
    <script src="admin/main.js"></script>
</body>
</html>
