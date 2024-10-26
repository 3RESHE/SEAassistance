@include('secretary.css')
@include('evaluator.sidebar')

<main>
    <section class="table-container">
        <h1>Advising Details for:</h1>
        <p><small>name:</small> {{ $advising->user->name }} {{ $advising->user->middle_name }} {{ $advising->user->last_name }}  {{ $advising->user->name_suffix }}  ( {{ $advising->user->school_id }} )</p>
        <p><small>course: </small> {{ $advising->user->course->course_name }}</p>

        
        <a href="{{ url('/') }}" style="background-color: rgb(85, 88, 85); border: none; color: white; cursor: pointer; padding: 8px 16px; text-decoration: none; display: inline-block;">
            View Student Details
        </a>
        

        <!-- Table to display subjects within the unit limit -->
        <h2>Subjects Within Unit Limit</h2>
        <div class="table-container">
            <table class="advising-table">
                <thead>
                    <tr>
                        <th>COURSE CODE</th>
                        <th>DESCRIPTIVE TITLE</th>
                        <th>UNITS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withinLimitSubjects as $subject)
                    <tr>
                        <td>{{ $subject->subject_code ?? 'No code' }}</td>
                        <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                        <td>{{ $subject->pivot->units ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Table to display subjects exceeding the unit limit -->


        <div class="summary">
            <p>Total Unit Limit: <span>{{ $unitLimit }}</span></p>
        </div>
    </section>
</main>
<br>
@include('evaluator.footer')
<script src="evaluator/main.js"></script>
