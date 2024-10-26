@include('evaluator.css')
@include('evaluator.sidebar')


<!--========== CONTENTS ==========-->

<main>
    <section>
<!-- DASHBOARD -->
<div class="big-card">
    <h1 style="text-align: center;">{{$course->course_name}} Curriculums</h1>
    <div class="department-cards">
        @foreach($curriculums as $curriculum)
            <a href="{{ url('view_curriculum_details' , $curriculum->id) }}" class="department-card-link">
                <div class="department-card">
                    <!-- Adjust the image path based on your storage setup -->
                    <img src="{{ asset('images/' . $department->department_logo) }}" alt="{{ $department->department_name }} Logo" class="department-logo">
                    <span class="department-name">{{$curriculum->curriculum_name}}</span>
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
