@include('secretary.css')


@include('secretary.sidebar')

    <!--========== CONTENTS ==========-->
    <br>
    <main>
        <section>


            <div class="big-card">
                <h1 style="text-align: center;">DEPARTMENTS</h1>
                <div class="department-cards">
                    @foreach($departments as $department)
                        <a href="{{url('view_course_sub', $department->id)}}" class="department-card-link">
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



    @include('secretary.footer')

    
        <!--========== MAIN JS ==========-->
        <script src="secretary/main.js"></script>

    </body>
    </html>
    