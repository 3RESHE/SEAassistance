@include('secretary.css')
@include('secretary.sidebar')

<main>
    <section class="table-container">
        <h1>Enrolled Advising Details for: {{ $advising->user->name }} ( {{ $advising->user->school_id }} )  </h1>

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
        <h2>Subjects Exceeding Unit Limit</h2>
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
                    @foreach($exceedingLimitSubjects as $subject)
                    <tr>
                        <td>{{ $subject->subject_code ?? 'No code' }}</td>
                        <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                        <td>{{ $subject->pivot->units ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary">
            <p>Total Unit Limit: <span>{{ $unitLimit }}</span></p>
        </div>
    </section>
</main>

@include('secretary.footer')
<script src="secretary/main.js"></script>
