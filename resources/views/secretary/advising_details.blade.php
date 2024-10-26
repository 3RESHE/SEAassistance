@include('secretary.sidebar')
@include('secretary.css')

<style>
    .overload-btn {
    background-color: #ffcc00; /* Yellow background */
    color: #333; /* Dark text color */
    border: none; /* No border */
    border-radius: 5px; /* Slightly rounded corners */
    padding: 10px 20px; /* Padding for top/bottom and left/right */
    font-size: 12px; /* Font size */
    font-weight: bold; /* Bold text */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s, transform 0.3s; /* Smooth transition for hover effects */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.overload-btn:hover {
    background-color: #e6b800; /* Darker yellow on hover */

}

.overload-btn:active {
    transform: translateY(1px); /* Press effect */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Decrease shadow on click */
}


</style>
<main>
    <section class="table-container">
        <h1>Advising Details for:</h1>
        <p><small>name:</small> {{ $advising->user->name }}  {{ $advising->user->last_name }}  ( {{ $advising->user->school_id }} )</p>
        <p><small>course: </small> {{ $advising->user->course->course_name }}</p>

        <!-- Table to display subjects within the unit limit -->
        <h2>Subjects Within Unit Limit</h2>
        <div class="table-container">
            <form id="advising-form" action="{{ url('updateAdvisingDetails') }}" method="POST" onsubmit="return updateHiddenInputs();">
                @csrf
                <input type="hidden" name="advising_id" value="{{ $advising->id }}">
                <table class="advising-table" id="within-limit-table">
                    <thead>
                        <tr>
                            <th>COURSE CODE</th>
                            <th>DESCRIPTIVE TITLE</th>
                            <th>UNITS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withinLimitSubjects as $subject)
                        <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->pivot->units }}">
                            <td>{{ $subject->subject_code ?? 'No code' }}</td>
                            <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                            <td>{{ $subject->pivot->units ?? 'N/A' }}</td>
                            <td>
                                <button type="button" class="btn-danger remove-btn">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="summary">
                    <p>Limit of units that can be enrolled: <span id="total-limit">{{ $unitLimit }}</span></p>
                    <p>Current total to enroll: <span id="total-units">0</span></p>
                </div>

                <input type="hidden" name="unit_limit" id="unit-limit" value="{{ $unitLimit }}">
                <div class="button-right">
                    <button type="submit" class="btn-success proceed-btn">Approve</button>
                    @if($Load == 'Overload')  <!-- Changed this line -->
                        <button type="button" class="btn-warning overload-btn" onclick="handleOverload()">Approval Letter</button>
                    @endif
                </div>
                

                <input type="hidden" name="within_limit_subjects" id="within-limit-subjects">
                <input type="hidden" name="exceeding_limit_subjects" id="exceeding-limit-subjects">
                <input type="hidden" name="not_qualified_subjects" id="not-qualified-subjects">
                <input type="hidden" name="not_open_subjects" id="not-open-subjects">
            </form>
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
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exceedingLimitSubjects as $subject)
                    <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->pivot->units }}">
                        <td>{{ $subject->subject_code ?? 'No code' }}</td>
                        <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                        <td>{{ $subject->pivot->units ?? 'N/A' }}</td>
                        <td>
                            <button type="button" class="btn-primary add-btn">Add</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <h2>Not Qualified Subjects</h2>
        <div class="table-wrapper">
            <table class="advising-table" id="not-qualified-table">
                <thead>
                    <tr>
                        <th>COURSE CODE</th>
                        <th>DESCRIPTIVE TITLE</th>
                        <th>UNITS</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notQualifiedSubjects as $subject)
                    <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->units }}">
                        <td>{{ $subject->subject_code ?? 'No code' }}</td>
                        <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                        <td>{{ $subject->units ?? 'N/A' }}</td>
                        <td>
                            <button type="button" class="btn-primary add-btn">Add</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="selected-subjects-not-qualified" style="display:none;"></div>
        </div>

        <h2>Not Open Subjects</h2>
        <div class="table-wrapper">
            <table class="advising-table" id="not-open-table">
                <thead>
                    <tr>
                        <th>COURSE CODE</th>
                        <th>DESCRIPTIVE TITLE</th>
                        <th>UNITS</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notOpenSubjects as $subject)
                    <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->units }}">
                        <td>{{ $subject->subject_code ?? 'No code' }}</td>
                        <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                        <td>{{ $subject->units ?? 'N/A' }}</td>
                        <td>
                            <button type="button" class="btn-primary add-btn">Add</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="selected-subjects-not-open" style="display:none;"></div>
        </div>

        <h2>Already Passed Subjects</h2>
        <div class="table-wrapper">
            <table class="advising-table" id="">
                <thead>
                    <tr>
                        <th>COURSE CODE</th>
                        <th>DESCRIPTIVE TITLE</th>
                        <th>UNITS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alreadyPassedSubjects as $subject)
                    <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->units }}">
                        <td>{{ $subject->subject_code ?? 'No code' }}</td>
                        <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                        <td>{{ $subject->units ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="selected-subjects-already-passed" style="display:none;"></div>
        </div>

        <h2>Subjects with Problems</h2>
        <div class="table-wrapper">
            <table class="advising-table" id="">
                <thead>
                    <tr>
                        <th>COURSE CODE</th>
                        <th>DESCRIPTIVE TITLE</th>
                        <th>UNITS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjectsWithProblem as $subject)
                    <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->units }}">
                        <td>{{ $subject->subject_code ?? 'No code' }}</td>
                        <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                        <td>{{ $subject->units ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="selected-subjects-with-problems" style="display:none;"></div>
        </div>
    </section>
</main>

@include('secretary.footer')
<script src="secretary/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert2 -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const withinLimitTable = document.getElementById('within-limit-table').querySelector('tbody');
    const exceedingLimitTable = document.getElementById('exceeding-limit-table').querySelector('tbody');
    const notQualifiedSubjects = document.getElementById('not-qualified-table').querySelector('tbody');
    const notOpenSubjects = document.getElementById('not-open-table').querySelector('tbody');
    const totalUnitsElement = document.getElementById('total-units');
    const unitLimit = parseFloat(document.getElementById('unit-limit').value);
    
    function updateTotalUnits() {
        let totalUnits = 0;
        withinLimitTable.querySelectorAll('tr').forEach(row => {
            const units = parseFloat(row.getAttribute('data-units')) || 0;
            totalUnits += units;
        });
        totalUnitsElement.textContent = totalUnits.toFixed(2);
    }
    
    function updateHiddenInputs() {
        const withinLimitSubjects = Array.from(withinLimitTable.querySelectorAll('tr')).map(row => row.getAttribute('data-subject-id'));
        document.getElementById('within-limit-subjects').value = withinLimitSubjects.join(',');
    
        const exceedingLimitSubjects = Array.from(exceedingLimitTable.querySelectorAll('tr')).map(row => row.getAttribute('data-subject-id'));
        document.getElementById('exceeding-limit-subjects').value = exceedingLimitSubjects.join(',');
    
        const notQualifiedSubjectsList = Array.from(notQualifiedSubjects.querySelectorAll('tr')).map(row => row.getAttribute('data-subject-id'));
        document.getElementById('not-qualified-subjects').value = notQualifiedSubjectsList.join(',');
    
        const notOpenSubjectsList = Array.from(notOpenSubjects.querySelectorAll('tr')).map(row => row.getAttribute('data-subject-id'));
        document.getElementById('not-open-subjects').value = notOpenSubjectsList.join(',');
    }
    
    // Handling removal from Within Limit Table
    withinLimitTable.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-btn')) {
            const row = event.target.closest('tr');
            const subjectId = row.getAttribute('data-subject-id');
            const units = row.getAttribute('data-units');
    
            // Add the removed subject back to the Exceeding Limit table
            const newRow = document.createElement('tr');
            newRow.setAttribute('data-subject-id', subjectId);
            newRow.setAttribute('data-units', units);
            newRow.innerHTML = `
                <td>${row.children[0].textContent}</td>
                <td>${row.children[1].textContent}</td>
                <td>${units}</td>
                <td><button type="button" class="btn-primary add-btn">Add</button></td>
            `;
            exceedingLimitTable.appendChild(newRow);
    
            row.remove();
            updateTotalUnits();
            updateHiddenInputs();
        }
    });
    
    // Handling addition from Exceeding Limit Table
    exceedingLimitTable.addEventListener('click', function(event) {
        if (event.target.classList.contains('add-btn')) {
            const row = event.target.closest('tr');
            const subjectId = row.getAttribute('data-subject-id');
            const units = row.getAttribute('data-units');
    
            // Add the subject to the Within Limit table
            const newRow = document.createElement('tr');
            newRow.setAttribute('data-subject-id', subjectId);
            newRow.setAttribute('data-units', units);
            newRow.innerHTML = `
                <td>${row.children[0].textContent}</td>
                <td>${row.children[1].textContent}</td>
                <td>${units}</td>
                <td><button type="button" class="btn-danger remove-btn">Remove</button></td>
            `;
            withinLimitTable.appendChild(newRow);
    
            row.remove();
            updateTotalUnits();
            updateHiddenInputs();
        }
    });
    
    // Handling addition from Not Qualified Subjects Table
    notQualifiedSubjects.addEventListener('click', function(event) {
        if (event.target.classList.contains('add-btn')) {
            const row = event.target.closest('tr');
            const subjectId = row.getAttribute('data-subject-id');
            const units = row.getAttribute('data-units');
    
            // Add the subject to the Within Limit table
            const newRow = document.createElement('tr');
            newRow.setAttribute('data-subject-id', subjectId);
            newRow.setAttribute('data-units', units);
            newRow.innerHTML = `
                <td>${row.children[0].textContent}</td>
                <td>${row.children[1].textContent}</td>
                <td>${units}</td>
                <td><button type="button" class="btn-danger remove-btn">Remove</button></td>
            `;
            withinLimitTable.appendChild(newRow);
    
            row.remove();
            updateTotalUnits(); // Update total units after adding
            updateHiddenInputs();
        }
    });
    
    // Handling addition from Not Open Subjects Table
    notOpenSubjects.addEventListener('click', function(event) {
        if (event.target.classList.contains('add-btn')) {
            const row = event.target.closest('tr');
            const subjectId = row.getAttribute('data-subject-id');
            const units = row.getAttribute('data-units');
    
            // Add the subject to the Within Limit table
            const newRow = document.createElement('tr');
            newRow.setAttribute('data-subject-id', subjectId);
            newRow.setAttribute('data-units', units);
            newRow.innerHTML = `
                <td>${row.children[0].textContent}</td>
                <td>${row.children[1].textContent}</td>
                <td>${units}</td>
                <td><button type="button" class="btn-danger remove-btn">Remove</button></td>
            `;
            withinLimitTable.appendChild(newRow);
    
            row.remove();
            updateTotalUnits(); // Update total units after adding
            updateHiddenInputs();
        }
    });
    
    // Confirmation before form submission
    document.getElementById('advising-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent immediate submission
    
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to approve the advising request. Do you want to proceed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                updateHiddenInputs(); // Update hidden inputs
                event.target.submit(); // Proceed with form submission
            }
        });
    });

    // Overload Unit Letter Button functionality
    const overloadButton = document.getElementById('overload-button');
    if (overloadButton) {
        overloadButton.addEventListener('click', function () {
            // Implement the logic for Overload Unit Letter here
            alert("Overload Unit Letter button clicked!");
            // You can also redirect to another page or open a modal
        });
    }
    
    updateTotalUnits(); // Initial total units calculation
});

</script>