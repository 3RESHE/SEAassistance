@include('secretary.css')
@include('evaluator.sidebar')

<main>
    <section class="table-container">
        <h1>Enrolled Student Details for: {{ $advising->user->name }} ( {{ $advising->user->school_id }} )  </h1>



        <form action="{{ route('EnrollStudent', $advising->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="background-color: green; border: none; color: white; cursor: pointer;" class="btn-view-details">
                Enroll
            </button>
        </form>

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
