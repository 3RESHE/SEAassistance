@include('secretary.css')
@include('secretary.sidebar')

<!--========== CONTENTS ==========-->
<br>
<main>
    <section>
        <div class="curriculum-container">
            <h2 class="curriculum-title">{{ $course->course_name }}</h2>
            <div class="curriculum-form__table-container">
                <table class="curriculum-form__table" border="1" cellpadding="10">
                    <thead>
                        <tr>
                            <th>#</th>
                            {{-- <th>Year Term</th> --}}
                            <th>Subject Code</th>
                            <th>Descriptive Title</th>
                            <th>Credit</th>
                            <th>Prerequisites</th>
                            <th>Corequisites</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedSubjects as $year => $terms)
                            @foreach($terms as $term => $data)
                                @php $counter = 1; @endphp <!-- Reset counter for each term group -->
                                @foreach($data['subjects'] as $subject)
                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        {{-- <td>{{ $year }} Year - {{ $term }}</td> --}}
                                        <td>{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->descriptive_title }}</td>
                                        <td>{{ $subject->units }}</td>
                                        <td>
                                            @if($subject->prerequisites->isNotEmpty())
                                                {{ $subject->prerequisites->pluck('descriptive_title')->join(', ') }}
                                            @else
                                                None
                                            @endif
                                        </td>
                                        <td>
                                            @if($subject->corequisites->isNotEmpty())
                                                {{ $subject->corequisites->pluck('descriptive_title')->join(', ') }}
                                            @else
                                                None
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
    </section>
</main>


@include('secretary.footer')
<!--========== MAIN JS ==========-->
<script src="secretary/main.js"></script>
