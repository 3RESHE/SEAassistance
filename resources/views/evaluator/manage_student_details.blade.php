@include('evaluator.css')
@include('evaluator.sidebar')

<style>
    /* Dropdown Styles */
    .grade-dropdown, .term-dropdown, .search-input {
        display: block;
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 0.375rem; /* Rounded corners */
        box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        font-size: 1rem;
        color: #333;
        appearance: none;
        -webkit-appearance: none; /* For Safari */
        -moz-appearance: none; /* For Firefox */
        margin-bottom: 1rem; /* Space below inputs */
    }

    .term-dropdown:focus, .grade-dropdown:focus, .search-input:focus {
        border-color: #4f46e5; /* Indigo color */
        outline: none;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2); /* Focus shadow */
    }

    /* Button Styles */
    .submit-button {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.375rem; /* Rounded corners */
        background-color: #007bff; /* Blue color */
        color: #fff;
        font-size: 1rem;
        font-weight: 500;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .submit-button:hover {
        background-color: #0056b3; /* Darker blue for hover */
    }

    .submit-button:focus {
        outline: 2px solid #007bff; /* Blue focus outline */
        outline-offset: 2px;
    }

    /* Search Input */
    .search-input {
        margin-bottom: 1rem; /* Space below the search input */
    }

    /* Date input visibility */
    .date-picker {
        display: none;
    }


    /* General container styling */
        .manageCHAT, .addCHAT {
            margin-top: 20px;
        }

        /* Styling for the buttons */
        .saveCHAT {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1E90FF; /* Sky-blue color */
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .saveCHAT:hover {
            background-color: #4682B4; /* Darker sky-blue */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Hover shadow effect */
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .saveCHAT {
                font-size: 14px;
                padding: 8px 16px;
            }
        }

</style>

<!--========== CONTENTS ==========-->
<br>
<main>
    <section>
        <!-- Curriculum and Details -->
        <div class="curriculum-container">
            <h2 class="curriculum-title">
                <span style="font-style: italic;">Program:</span> 
                {{ $user->course->course_name }}
            </h2>
            <h2 class="curriculum-title">
                <span style="font-style: italic;">Curriculum:</span> 
                @if($user->curriculum)
                    {{ $user->curriculum->curriculum_name }}
                @else
                    <span style="color: red;">No Curriculum Assigned</span>
                @endif
            </h2>

            <h2 style="text-align: left" class="curriculum-title">
                <span style="font-style: italic;">Name: </span> 
                {{ $user->last_name }}, {{ $user->name }} {{ $user->middle_name }}.
            </h2>
            <h2 style="text-align: left" class="curriculum-title">
                <span style="font-style: italic;">School ID: </span> 
                {{ $user->school_id }}
            </h2>

            @if($user->curriculum)
            <div class="manageCHAT">
                <a href="{{ url('manage_student_curriculum', $user->id) }}" class="saveCHAT">Manage Student</a>
            </div>
            @else
                <div class="addCHAT">
                    <a href="{{ url('add_student_curriculum', $user->id) }}" class="saveCHAT">Add Curriculum</a>
                </div>
            @endif
        
            <br>

            <!-- Search Input -->
            <input type="text" id="subject-search" class="search-input" placeholder="Search subjects..." onkeyup="filterSubjects()">

            <!-- Dropdown for enrolled terms -->
            <div class="term-selector">
                <label for="term-dropdown">Select Term:</label>
                <select id="term-dropdown" class="term-dropdown" onchange="filterSubjectsByTerm()">
                    <option value="">--Select Term--</option>
                    @foreach($distinctEnrolledTerms as $term)
                        <option value="{{ $term }}">{{ $term }}</option>
                    @endforeach
                </select>
            </div>

            <div class="curriculum-form__table-container">
                <form action="{{ url('update_grade', $user->id) }}" method="POST">
                    @csrf
                    <table class="curriculum-form__table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Year Term</th>
                                <th>Subject Code</th>
                                <th>Descriptive Title</th>
                                <th>Units</th>
                                <th>Grade</th>
                                <th>Transfer Grade</th>
                                <th>Deadline Date (If INC)</th>
                                <th>Enrolled Term</th>
                                <th>Source School (Optional)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $previousTerm = '';
                                $currentTermTotal = 0;
                                $index = 1;
                            @endphp
                            @foreach($subjects as $term => $subjectsByTerm)
                                @php
                                    $parts = explode(' ', $term);
                                    $year = $parts[0];
                                    $termLabel = isset($parts[1]) ? $parts[1] : 'Unknown Term';
                                    $formattedTerm = "{$year} Year {$termLabel}";
                        
                                    if ($previousTerm !== $termLabel) {
                                        if ($previousTerm !== '') {
                                            // Only display the total row if no term is selected
                                            echo '<tr id="total-row" style="display: none;">'; // Initially hide the total row
                                            echo '<td colspan="5" style="text-align: right; font-weight: bold; background-color: #f0f0f0;">';
                                            echo "Total:";
                                            echo '</td>';
                                            echo '<td style="font-weight: bold; background-color: #f0f0f0;">';
                                            echo $currentTermTotal;
                                            echo '</td>';
                                            echo '<td colspan="3"></td>';
                                            echo '</tr>';
                                        }
                        
                                        $currentTermTotal = 0;
                                        $previousTerm = $termLabel;
                                    }
                                @endphp
                        
                                @foreach($subjectsByTerm as $subject)
                                    @php
                                        // Get current grade, transfer grade, and source school
                                        $currentGrade = $grades[$subject->id] ?? '';
                                        $isTransferGrade = $transferGrades[$subject->id] ?? 0;
                                        $sourceSchool = $sourceSchools[$subject->id] ?? '';
                                        $enrolledTerm = $enrolledTerms[$subject->id] ?? ''; // Use this for filtering
                                    @endphp
                                    <tr class="subject-row" data-term="{{ $enrolledTerm }}" data-title="{{ $subject->descriptive_title }}"> <!-- Add a data attribute for filtering -->
                                        <td>{{ $index++ }}</td>
                                        <td>{{ $formattedTerm }}</td>
                                        <td>{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->descriptive_title }}</td>
                                        <td>{{ $subject->units }}</td>
                                        <td>
                                            <select name="grades[{{ $subject->id }}]" class="grade-dropdown" onchange="checkForINC(this, {{ $subject->id }})">
                                                <!-- Display current grade if set, otherwise show default options -->
                                                @if(isset($grades[$subject->id]))
                                                    <option value="{{ $grades[$subject->id] }}" selected>{{ $grades[$subject->id] }}</option>
                                                @else
                                                    <option value="" disabled selected>Select grade</option>
                                                @endif
                                                <option value="1.0">1.0</option>
                                                <option value="1.25">1.25</option>
                                                <option value="1.50">1.50</option>
                                                <option value="1.75">1.75</option>
                                                <option value="2.0">2.0</option>
                                                <option value="2.25">2.25</option>
                                                <option value="2.50">2.50</option>
                                                <option value="3.0">3.0</option>
                                                <option value="5.0">5.0</option>
                                                <option value="W/F">W/F - Withdrawn/Failure</option>
                                                <option value="W">W - Withdrawn</option>
                                                <option value="UD">UD - Unofficially Dropped</option>
                                                <option value="OD">OD - Officially Dropped</option>
                                                <option value="INC">INC - Incomplete</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="checkbox" value="1" name="transfer_grades[{{ $subject->id }}]" {{ $isTransferGrade == 1 ? 'checked' : '' }}>
                                        </td>
                        
                                        <td>
                                            <input type="date" 
                                                   name="inc_deadlines[{{ $subject->id }}]" 
                                                   id="inc_deadline_{{ $subject->id }}" 
                                                   class="date-picker" 
                                                   value="{{ $incDeadlines[$subject->id] ?? '' }}"
                                                   style="display: {{ $currentGrade === 'INC' ? 'block' : 'none' }};">
                                        </td>
                                        
                                        <td>{{ $enrolledTerm }}</td>
                                        <td>
                                            <input type="text" name="source_schools[{{ $subject->id }}]" value="{{ $sourceSchool }}" class="form-input" placeholder="Optional">
                                        </td>
                                    </tr>
                                    @php
                                        // Increment total for current term
                                        if (is_numeric($currentGrade)) {
                                            $currentTermTotal += (float)$currentGrade;
                                        }
                                    @endphp
                                @endforeach
                            @endforeach
                            <tr id="total-row" style="display: table-row;"> <!-- Ensure total row is visible by default -->
                                <td colspan="5" style="text-align: right; font-weight: bold; background-color: #f0f0f0;">Total:</td>
                                <td style="font-weight: bold; background-color: #f0f0f0;">{{ $currentTermTotal }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tbody>
                        
                    </table>
<br>
                    <button type="submit" class="submit-button">Update Grades</button>
                </form>
            </div>
        </div>
    </section>
</main>
<br>
@include('evaluator.footer')
<!-- Add JavaScript to handle term filtering and live search -->
<script>
    // Function to check for 'INC' grade and toggle the date picker visibility
    function checkForINC(selectElement, subjectId) {
        const selectedValue = selectElement.value;
        const datePicker = document.getElementById(`inc_deadline_${subjectId}`);
        
        // Show the date picker if the selected grade is 'INC', otherwise hide it
        datePicker.style.display = (selectedValue === 'INC') ? 'block' : 'none';
        
        // If the date picker is visible and there's no value, set it to today's date
        if (selectedValue === 'INC' && !datePicker.value) {
            const today = new Date().toISOString().split('T')[0];
            datePicker.value = today; // Set today's date if empty
        }
    }

    // Set date picker to today's date if empty when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        const datePickers = document.querySelectorAll('.date-picker');
        const today = new Date().toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format

        datePickers.forEach(function(datePicker) {
            // If the value is empty, set it to today's date
            if (!datePicker.value) {
                datePicker.value = today;
            }
        });
    });

    // Function to filter subjects by selected term and manage the visibility of the total row
    function filterSubjectsByTerm() {
        const selectedTerm = document.getElementById('term-dropdown').value;
        const subjectRows = document.querySelectorAll('.subject-row'); // All subject rows
        const totalRow = document.getElementById('total-row'); // The total row

        let hasVisibleRows = false; // Flag to check if any rows are visible

        subjectRows.forEach(row => {
            const termValue = row.getAttribute('data-term'); // Get the term from the data attribute

            // Show or hide the row based on the selected term
            if (selectedTerm === '' || termValue === selectedTerm) {
                row.style.display = ''; // Show the row
                hasVisibleRows = true; // Set flag to true if this row is shown
            } else {
                row.style.display = 'none'; // Hide the row
            }
        });

        // Show or hide the total row based on visible rows
        totalRow.style.display = hasVisibleRows ? 'none' : 'table-row'; // Hide total row if any subject rows are visible
    }

    // Function to filter subjects based on the search input
    function filterSubjects() {
        const searchInput = document.getElementById('subject-search').value.toLowerCase();
        const subjectRows = document.querySelectorAll('.subject-row'); // All subject rows
        const totalRow = document.getElementById('total-row'); // The total row

        let hasVisibleRows = false; // Flag to check if any rows are visible

        subjectRows.forEach(row => {
            const title = row.getAttribute('data-title').toLowerCase(); // Get the subject title from the data attribute
            
            // Show or hide the row based on the search input
            if (title.includes(searchInput)) {
                row.style.display = ''; // Show the row
                hasVisibleRows = true; // Set flag to true if this row is shown
            } else {
                row.style.display = 'none'; // Hide the row
            }
        });

        // Show or hide the total row based on visible rows
        totalRow.style.display = hasVisibleRows ? 'none' : 'table-row'; // Hide total row if any subject rows are visible
    }
</script>

<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>