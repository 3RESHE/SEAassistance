@include('student.css')
@include('student.sidebar')

<style>
    .table-wrapper {
    width: 100%;
    margin: 0 auto;
}

.button-right {
    display: flex;
    justify-content: flex-end; /* Align the button to the right */
    margin-top: 20px;
}

.proceed-btn {
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.proceed-btn:hover {
    background-color: #218838;
}

/* If you want to center the button instead of aligning to the right, change justify-content to center */

</style>

<!--========== CONTENTS ==========-->
<main>
    <section class="content-box">
        <h1>Advising Results</h1>

        @if(empty($curriculum))
            <p>No subjects to display because no curriculum is assigned.</p>
        @elseif(empty($withinLimitSubjects) && empty($exceedingLimitSubjects) && empty($existingAdvising))
            <p>No subjects available based on your curriculum and current semester.</p>
        @elseif($existingAdvising)
            <p style="color: skyblue">You have successfully submitted your advising request. Please proceed to the advising process in your department.</p>
            <h2>Pre-assessment Subjects</h2>
            <div class="table-wrapper">
                <table id="subjects-to-be-enrolled-table">
                    <thead>
                        <tr>
                            <th>COURSE CODE</th>
                            <th>DESCRIPTIVE TITLE</th>
                            <th>UNITS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($advisingDetails as $detail)
                            <tr>
                                <td>{{ $detail->subject_code ?? 'No code' }}</td>
                                <td>{{ $detail->descriptive_title ?? 'No title' }}</td>
                                <td>{{ $detail->units ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @elseif($approvedSubjects->isNotEmpty())
            <p style="color: skyblue">Your advising request has been approved! The subjects have been confirmed by your advisor. Please proceed with the next steps for enrollment.</p>
           
           
            <h3>Approved Subjects</h3>
            <div class="table-wrapper">
                <table id="enrolled-subjects-table">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedSubjects as $subject)
                        <tr data-subject-id="{{ $subject->id }}">
                                <td>{{ $subject->subject_code ?? 'No code' }}</td>
                                <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                                <td>{{ $subject->units }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>





            
        @elseif($enrolledSubjects->isNotEmpty())
            <h3>Enrolled Subjects</h3>
            <div class="table-wrapper">
                <table id="enrolled-subjects-table">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrolledSubjects as $subject)
                            <tr>
                                <td>{{ $subject->subject_code ?? 'No code' }}</td>
                                <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                                <td>{{ $subject->units }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <h2>Subjects Within Unit Limit</h2>
            <div class="table-wrapper">
                <form id="advising-form" action="{{ url('/submitAdvising') }}" method="POST">
                    @csrf
                    
                    <table id="within-limit-table">
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
                                <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->units }}">
                                    <td>{{ $subject->subject_code ?? 'No code' }}</td>
                                    <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                                    <td class="units">{{ $subject->units ?? 'N/A' }}</td>
                                    <td>
                                        <button type="button" style="color: white; background-color: #dc3545;" class="btn-danger remove-btn">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <p>Limit of units that can be enrolled: <span id="total-limit">{{ $unitLimit }}</span></p>
                    <p>Current total to enroll: <span id="total-units">0</span></p>
                    <input type="hidden" name="unit_limit" id="unit-limit" value="{{ $unitLimit }}">
                    
                    <div class="button-right">
                        @if(!$existingAdvising)
                            <button type="submit" class="btn-success proceed-btn">Proceed</button>
                        @else
                            <p style="color: skyblue">You have successfully submitted your advising request. Please proceed to the advising process in your department.</p>
                        @endif
                    </div>

                    <div id="selected-subjects-within-limit" style="display:none;"></div>
                    <div id="selected-subjects-exceeding-limit" style="display:none;"></div>
                </form>
            </div>

            <h2>Subjects Exceeding Unit Limit</h2>
            <div class="table-wrapper">
                <table id="exceeding-limit-table">
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
            </div>



<h2>Not Qualified Subjects</h2>
<div class="table-wrapper">
    <table id="not-qualified-table">
        <thead>
            <tr>
                <th>COURSE CODE</th>
                <th>DESCRIPTIVE TITLE</th>
                <th>UNITS</th>
             
            </tr>
        </thead>
        <tbody>
            @foreach($notQualifiedSubjects as $subject)
            <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->units }}">
                    <td>{{ $subject->subject_code ?? 'No code' }}</td>
                    <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                    <td>{{ $subject->units ?? 'N/A' }}</td>
       
                </tr>
            @endforeach
        </tbody>
    </table>
    <div id="selected-subjects-not-qualified" style="display:none;"></div>
</div>

<h2>Not Open Subjects</h2>
<div class="table-wrapper">
    <table id="not-open-table">
        <thead>
            <tr>
                <th>COURSE CODE</th>
                <th>DESCRIPTIVE TITLE</th>
                <th>UNITS</th>

            </tr>
        </thead>
        <tbody>
            @foreach($notOpenSubjects as $subject)
            <tr data-subject-id="{{ $subject->id }}" data-units="{{ $subject->units }}">
                    <td>{{ $subject->subject_code ?? 'No code' }}</td>
                    <td>{{ $subject->descriptive_title ?? 'No title' }}</td>
                    <td>{{ $subject->units ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div id="selected-subjects-not-open" style="display:none;"></div>
</div>


            <!-- Add these sections in your Blade file, similar to your existing tables -->
            <h2>Already Passed Subjects</h2>
            <div class="table-wrapper">
                <table id="already-passed-table">
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
    <table id="subjects-with-problems-table">
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

        @endif
    </section>
</main>

<!-- Modal warning for unit limit -->
<div id="warning-modal" class="modal-warning">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>You have exceeded the unit limit! Please remove some subjects to proceed.</p>
    </div>
</div>
<br>
@include('student.footer')


<!--========== MAIN JS ==========-->
<script src="{{ asset('student/main.js') }}"></script>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectedSubjectsWithinLimitDiv = document.getElementById('selected-subjects-within-limit');
        const selectedSubjectsExceedingLimitDiv = document.getElementById('selected-subjects-exceeding-limit');
        const form = document.getElementById('advising-form');
        const unitLimitInput = document.getElementById('unit-limit');
        const proceedButton = document.querySelector('.proceed-btn');
        const totalUnitsElement = document.getElementById('total-units');

        function updateTotalUnits() {
            let totalUnits = Array.from(document.querySelectorAll('#within-limit-table tbody tr')).reduce((sum, row) => sum + (parseInt(row.dataset.units, 10) || 0), 0);
            totalUnitsElement.textContent = totalUnits;

            // Update color and button text based on unit limit
            updateTotalLimit(totalUnits);
        }

        function updateTotalLimit(totalUnits) {
            const totalLimit = parseInt(unitLimitInput.value, 10) || 0;
            const form = document.getElementById('advising-form');
            
            if (totalUnits > totalLimit) {
                totalUnitsElement.style.color = 'red'; // Change text color to red
                proceedButton.style.backgroundColor = 'orange'; // Change button color to warning color
                proceedButton.style.color = 'white'; // Change text color for better contrast
                // Show warning notification
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'You are exceeding subjects; now you are currently having overloading units.',
                });
                proceedButton.textContent = 'Proceed Advising with Overload Units'; // Change button text
                form.action = '{{ route('submitOverloadAdvising') }}'; // Set form action to overload route
            } else {
                totalUnitsElement.style.color = ''; // Reset to default color
                proceedButton.style.backgroundColor = ''; // Reset button color to default
                proceedButton.style.color = ''; // Reset text color to default
                proceedButton.textContent = 'Proceed Advising'; // Default button text
                form.action = '{{ url('/submitAdvising') }}'; // Reset form action to the normal advising route
            }
}


        function addHiddenInput(subjectId, type) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `${type}[]`;
            input.value = subjectId;
            (type === 'within_limit' ? selectedSubjectsWithinLimitDiv : selectedSubjectsExceedingLimitDiv).appendChild(input);
        }

        function removeHiddenInput(subjectId, type) {
            const inputToRemove = [...document.getElementsByName(`${type}[]`)].find(input => input.value === subjectId);
            if (inputToRemove) inputToRemove.remove();
        }

        function updateHiddenInputs() {
            selectedSubjectsWithinLimitDiv.innerHTML = '';
            selectedSubjectsExceedingLimitDiv.innerHTML = '';
            // Add subjects from within limit table
            document.querySelectorAll('#within-limit-table tbody tr').forEach(row => addHiddenInput(row.dataset.subjectId, 'within_limit'));
            // Add subjects from exceeding limit table
            document.querySelectorAll('#exceeding-limit-table tbody tr').forEach(row => addHiddenInput(row.dataset.subjectId, 'exceeding_limit'));
            
            // Add subjects from approved subjects table
            document.querySelectorAll('#approved-subjects-table tbody tr').forEach(row => addHiddenInput(row.dataset.subjectId, 'approved_subjects'));
            
            // Add subjects from enrolled subjects table
            document.querySelectorAll('#enrolled-subjects-table tbody tr').forEach(row => addHiddenInput(row.dataset.subjectId, 'enrolled_subjects'));

            // Add subjects from already passed table
            document.querySelectorAll('#already-passed-table tbody tr').forEach(row => addHiddenInput(row.dataset.subjectId, 'already_passed'));
            
            // Add subjects from not qualified table
            document.querySelectorAll('#not-qualified-table tbody tr').forEach(row => addHiddenInput(row.dataset.subjectId, 'not_qualified'));
            
            // Add subjects from not open table
            document.querySelectorAll('#not-open-table tbody tr').forEach(row => addHiddenInput(row.dataset.subjectId, 'not_open'));

            // Add subjects from problems table
            document.querySelectorAll('#problems-table tbody tr').forEach(row => addHiddenInput(row.dataset.subjectId, 'problems'));
        }


        function handleRemoveButtonClick(event) {
            const rowToRemove = event.target.closest('tr');
            const subjectId = rowToRemove.dataset.subjectId;
            rowToRemove.remove();
            removeHiddenInput(subjectId, 'within_limit');
            updateTotalUnits();

            const newRow = document.createElement('tr');
            newRow.dataset.subjectId = subjectId;
            newRow.dataset.units = rowToRemove.dataset.units;
            newRow.innerHTML = `
                <td>${rowToRemove.cells[0].textContent.trim()}</td>
                <td>${rowToRemove.cells[1].textContent.trim()}</td>
                <td>${rowToRemove.dataset.units}</td>
                <td><button type="button" class="btn-primary add-btn">Add</button></td>
            `;
            document.querySelector('#exceeding-limit-table tbody').appendChild(newRow);
            newRow.querySelector('.add-btn').addEventListener('click', handleAddButtonClick);
            updateTotalUnits(); // Ensure total is updated after adding back
        }

        function handleAddButtonClick(event) {
            const rowToAdd = event.target.closest('tr');
            const subjectId = rowToAdd.dataset.subjectId;
            if (!document.querySelector(`#within-limit-table tbody tr[data-subject-id="${subjectId}"]`)) {
                rowToAdd.remove();
                addHiddenInput(subjectId, 'within_limit');
                const newRow = document.createElement('tr');
                newRow.dataset.subjectId = subjectId;
                newRow.dataset.units = rowToAdd.dataset.units;
                newRow.innerHTML = `
                    <td>${rowToAdd.cells[0].textContent.trim()}</td>
                    <td>${rowToAdd.cells[1].textContent.trim()}</td>
                    <td>${rowToAdd.dataset.units}</td>
                    <td><button type="button" class="btn-danger remove-btn">Remove</button></td>
                `;
                document.querySelector('#within-limit-table tbody').appendChild(newRow);
                newRow.querySelector('.remove-btn').addEventListener('click', handleRemoveButtonClick);
                updateTotalUnits(); // Ensure total is updated after adding
            }
        }

        document.querySelectorAll('.remove-btn').forEach(button => button.addEventListener('click', handleRemoveButtonClick));
        document.querySelectorAll('.add-btn').forEach(button => button.addEventListener('click', handleAddButtonClick));

        proceedButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent immediate form submission
            updateHiddenInputs(); // Update hidden inputs before showing SweetAlert

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to modify this request after submitting!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form if the user confirms
                }
            });
        });

        updateTotalUnits();
    });
</script>
