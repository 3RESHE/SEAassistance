@include('evaluator.css')
@include('evaluator.sidebar')

<!--========== CONTENTS ==========-->
<br>
<main>
    <section>
        <!-- Curriculum Details -->
        <div class="curriculum-container">
            <h2 class="curriculum-title">Curriculum Details: {{ $curriculum->curriculum_name }}</h2>
            <div class="curriculum-form__table-container">
                <table class="curriculum-form__table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Year Term</th>
                            <th>Subject Code</th>
                            <th>Descriptive Title</th>
                            <th>Units</th>
                            <th>Prerequisite Subjects</th>
                            <th>Corequisite Subjects</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp <!-- Initialize counter -->
                        @foreach($groupedSubjects as $year => $terms)
                            @foreach($terms as $term => $data)
                                @foreach($data['subjects'] as $subject)
                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $year }} Year - {{ $term }}</td>
                                        <td>{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->descriptive_title }}</td>
                                        <td>{{ $subject->units }}</td>
                                        <td>
                                            @if($subject->prerequisites->isNotEmpty())
                                                {{ $subject->prerequisites->pluck('descriptive_title')->join(', ') }}
                                            @else
                                                No Prerequisites
                                            @endif
                                        </td>
                                        <td>
                                            @if($subject->corequisites->isNotEmpty())
                                                {{ $subject->corequisites->pluck('descriptive_title')->join(', ') }}
                                            @else
                                                No Corequisites
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" align="right"><strong>Total :</strong></td>
                                    <td colspan="2"><strong>{{ $data['total_units'] }}</strong></td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <br>
        <br>
        <br>
        <br>
        
    
        <!--========== MAIN JS ==========-->
        <script src="{{ asset('evaluator/main.js') }}"></script>
    </section>
</main>


@include('evaluator.footer')