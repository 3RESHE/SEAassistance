



@include('admin.css')


@include('admin.sidebar', ['user' => Auth::user()])

<!--========== CONTENTS ==========-->

<main>
    <section>
<!-- DASHBOARD -->
<div class="big-card">
    <h1 style="text-align: center;">SELECT DEPARTMENT</h1>
    <div class="department-cards">
        @foreach($departments as $department)
            <a href="{{url('view_course', $department->id)}}" class="department-card-link">
                <div class="department-card">
                    <!-- Adjust the image path based on your storage setup -->
                    <img src="{{ asset('images/' . $department->department_logo) }}" alt="{{ $department->department_name }} Logo" class="department-logo">
                    <span class="department-name">{{ $department->department_name }}</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
  <!-- DASHBOARD -->




        




</section>
</main>


@include('admin.footer')
    <!--========== MAIN JS ==========-->
    <script src="admin/main.js"></script>
</body>
</html>
