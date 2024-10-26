@include('secretary.css')
@include('secretary.sidebar')

<main>
    <section class="table-container">
        <h1>Cancelled Advising Details for: {{ $advising->user->name }} ( {{ $advising->user->school_id }} )</h1>

        <!-- Table to display subjects within the unit limit -->
        <h2>Subjects Within Unit Limit</h2>
        <div class="table-container">
            <table class="advising-table" id="within-limit-table">
                <thead>
                    <tr>
                        <th>COURSE CODE</th>
                        <th>DESCRIPTIVE TITLE</th>
                        <th>UNITS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withinLimitSubjects as $subject)
                    <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->pivot->units }}">
                        <td>{{ $subject->subject_code ?? 'No code' }}</td>
                        <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                        <td>{{ $subject->pivot->units ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary">
                <p>Limit of units that can be enrolled: <span id="total-limit">{{ $unitLimit }}</span></p>
                <p>Current total to enroll: <span id="total-units">0</span></p>
            </div>
        </div>

        <!-- Table to display subjects exceeding the unit limit -->
        <h2>Subjects Exceeding Unit Limit</h2>
        <div class="table-container">
            <table class="advising-table" id="exceeding-limit-table">
                <thead>
                    <tr>
                        <th>COURSE CODE</th>
                        <th>DESCRIPTIVE TITLE</th>
                        <th>UNITS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exceedingLimitSubjects as $subject)
                    <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->pivot->units }}">
                        <td>{{ $subject->subject_code ?? 'No code' }}</td>
                        <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                        <td>{{ $subject->pivot->units ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</main>

@include('secretary.footer')
<script src="secretary/main.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const withinLimitTable = document.getElementById('within-limit-table').querySelector('tbody');
    const totalUnitsElement = document.getElementById('total-units');

    function updateTotalUnits() {
        let totalUnits = 0;
        withinLimitTable.querySelectorAll('tr').forEach(row => {
            const units = parseFloat(row.getAttribute('data-units')) || 0;
            totalUnits += units;
        });
        totalUnitsElement.textContent = totalUnits.toFixed(2);
    }

    updateTotalUnits();
});
</script>
