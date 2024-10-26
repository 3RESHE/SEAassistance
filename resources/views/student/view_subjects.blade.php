@include('student.css')
@include('student.sidebar')

<br>
<main>
    <section>
        <div class="curriculum-container">
            <h2 class="curriculum-title">
                <span style="font-style: italic;">Program:</span> 
                {{ $user->course->course_name }}
            </h2>
            <h2 class="curriculum-title">
                <span style="font-style: italic;">Curriculum:</span> 
                @if($curriculum)
                    {{ $curriculum->curriculum_name }}
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
            
            <!-- Add the button to request the TOR -->
            <a href="#" class="button" 
               onclick="confirmTorRequest(event)">
               Request Evaluation of Grades
            </a>

            <!-- Hidden form to submit the TOR request -->
            <form id="request-tor-form" action="{{ route('tor.request') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <style>
                .button {
                    display: inline-block;
                    padding: 10px 20px;
                    font-size: 16px;
                    color: white;
                    background-color: #007bff;
                    border: none;
                    border-radius: 5px;
                    text-decoration: none;
                    text-align: center;
                    transition: background-color 0.3s;
                }

                .button:hover {
                    background-color: #0056b3;
                }
            </style>

            <br><br>

            @if($curriculum)
                <!-- Dropdown for filtering subjects by enrolled term -->
                <div class="term-selector">
                    <label for="term-dropdown">Select Enrolled Term:</label>
                    <select id="term-dropdown" onchange="filterSubjectsByTerm()">
                        <option value="">-- Select Term --</option>
                        @php
                            // Get unique enrolled terms from grades
                            $uniqueTerms = $grades->pluck('enrolled_term')->unique()->filter()->values();
                        @endphp
                        @foreach($uniqueTerms as $term)
                            <option value="{{ $term }}">{{ $term }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="curriculum-form__table-container">
                    <table class="curriculum-form__table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Year Term</th>
                                <th>Subject Code</th>
                                <th>Descriptive Title</th>
                                <th>Units</th>
                                <th>Remarks</th>
                                <th>Enrolled Term</th>
                            </tr>
                        </thead>
                        <tbody id="subject-rows">
                            @php
                                $index = 1;
                            @endphp
                        
                            @foreach($subjects as $term => $subjectsByTerm)
                                <tr>
                                    <td colspan="7" style="font-weight: bold; background-color: #f0f0f0;">
                                        {{ $term }}
                                    </td>
                                </tr>
                        
                                @foreach($subjectsByTerm as $subject)
                                @php
                                    $gradeRecord = $grades[$subject->id] ?? null;
                                    $gradeDisplay = '';
                                    $enrolledTerm = $gradeRecord->enrolled_term ?? '';

                                    // Logic for gradeDisplay here
                                    if ($gradeRecord) {
                                        // Check for special grades
                                        if ($gradeRecord->grade === 'Enrolled') {
                                            $gradeDisplay = 'Enrolled';
                                        } elseif ($gradeRecord->grade === 'Approved') {
                                            $gradeDisplay = 'Approved';
                                        } elseif (is_numeric($gradeRecord->grade)) {
                                            $numericGrade = floatval($gradeRecord->grade);
                                            // Determine if the student passed or failed
                                            $gradeDisplay = ($numericGrade >= 1.0 && $numericGrade <= 3.0) ? 'Passed' : 'Failed';
                                        } else {
                                            $gradeDisplay = 'Failed'; // Default for any non-recognized grade
                                        }
                                    }
                                @endphp
                                <tr class="subject-row" data-term="{{ $enrolledTerm }}">
                                    <td>{{ $index++ }}</td>
                                    <td>{{ $term }}</td>
                                    <td>{{ $subject->subject_code }}</td>
                                    <td>{{ $subject->descriptive_title }}</td>
                                    <td>{{ $subject->units }}</td>
                                    <td>{{ $gradeDisplay }}</td> <!-- Displaying Passed/Failed/Enrolled/Approved -->
                                    <td>{{ $enrolledTerm }}</td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>
            @else
                <p style="color: red; text-align: center;">No subjects to display because no curriculum is assigned.</p>
            @endif
        </div>

        <br><br><br><br>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmTorRequest(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Confirm Request',
                    text: "Are you sure you want to request an Evaluation of Grades?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, request it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('request-tor-form').submit();
                    }
                });
            }

            function filterSubjectsByTerm() {
                const selectedTerm = document.getElementById('term-dropdown').value;
                const subjectRows = document.querySelectorAll('.subject-row');

                subjectRows.forEach(row => {
                    const termValue = row.getAttribute('data-term');

                    // Show or hide the row based on the selected term
                    if (selectedTerm === '' || termValue === selectedTerm) {
                        row.style.display = ''; // Show the row
                    } else {
                        row.style.display = 'none'; // Hide the row
                    }
                });
            }
        </script>

    </section>
</main>

@include('student.footer')
<script src="student/main.js"></script>
