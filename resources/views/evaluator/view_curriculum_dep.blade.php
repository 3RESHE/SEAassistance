@include('evaluator.css')
@include('evaluator.sidebar')


    <!--========== CONTENTS ==========-->
    <br>
    <main>
        <section>


            <div class="big-card">
                <h1 style="text-align: center;">DEPARTMENTS</h1>
                <div class="department-cards">
                    @foreach($departments as $department)
                        <a href="{{url('view_curriculum_course', $department->id)}}" class="department-card-link">
                            <div class="department-card">
                                <img src="{{ asset('images/' . $department->department_logo) }}" alt="{{ $department->department_name }} Logo" class="department-logo">
                                <span class="department-name">{{ $department->department_name }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
  

        </section>
    </main>




    
    <script src="evaluator/main.js"></script>
    @include('evaluator.footer')
    </body>
    </html>
    