@include('evaluator.css')
@include('evaluator.sidebar')

<br>
<main>
    <section>
        <div class="curriculum-container">
            <h2 class="curriculum-title">Generate New Curriculum</h2>
            <form action="{{ url('Generate_Curriculum') }}" method="POST" class="curriculum-form">
                @csrf
            
                <label for="curriculum_name" class="curriculum-form__label">Enter Curriculum Name</label>
                <input type="text" name="curriculum_name" id="curriculum_name" class="curriculum-form__input" placeholder="Enter Curriculum Name" required>
            
                <!-- Ensure course_id is set correctly -->
                <input type="hidden" name="course_id" id="course_id" value="{{$courses->id}}">
            
                <div class="curriculum-form__table-container">
                    <table class="curriculum-form__table" id="subjectTable">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Semester</th>
                                <th>Descriptive Title</th>
                                <th>Course Code</th>
                                <th>Units</th>
                                <th>Prerequisite Subjects</th>
                                <th>Corequisite Subjects</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjects as $subject)
                            <tr id="subjectRow-{{ $subject->id }}">
                                <td>
                                    <select name="year[]" class="curriculum-form__select" required>
                                        <option value="1st" {{ old('year', $subject->year) == '1st' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd" {{ old('year', $subject->year) == '2nd' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd" {{ old('year', $subject->year) == '3rd' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th" {{ old('year', $subject->year) == '4th' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="year_term[]" class="curriculum-form__select" required>
                                        <option value="1st_sem" {{ old('year_term', $subject->year_term) == '1st_sem' ? 'selected' : '' }}>1st Sem</option>
                                        <option value="2nd_sem" {{ old('year_term', $subject->year_term) == '2nd_sem' ? 'selected' : '' }}>2nd Sem</option>
                                        <option value="summer" {{ old('year_term', $subject->year_term) == 'summer' ? 'selected' : '' }}>Summer</option>
                                    </select>
                                </td>
                                <td>
                                    {{ $subject->descriptive_title }}
                                </td>
                                <td>
                                    {{ $subject->subject_code }}
                                </td>
                                <td>
                                    {{ $subject->units }}
                                </td>
                                <td>
                                    @if ($subject->prerequisites->isNotEmpty())
                                        {{ $subject->prerequisites->pluck('descriptive_title')->join(', ') }}
                                    @else
                                        No Prerequisites
                                    @endif
                                </td>
                                <td>
                                    @if ($subject->corequisites->isNotEmpty())
                                        {{ $subject->corequisites->pluck('descriptive_title')->join(', ') }}
                                    @else
                                        No Corequisites
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="remove-btn" onclick="removeSubject({{ $subject->id }})">Remove</button>
                                </td>
                                <input type="hidden" name="subject_ids[]" id="subjectId-{{ $subject->id }}" value="{{ $subject->id }}">
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h3>Other Subjects</h3>
                <div class="curriculum-form__table-container">
                    <table class="curriculum-form__table" id="otherSubjectsTable">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Semester</th>
                                <th>Descriptive Title</th>
                                <th>Course Code</th>
                                <th>Units</th>
                                <th>Prerequisite Subjects</th>
                                <th>Corequisite Subjects</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Removed subjects will be appended here -->
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="curriculum-form__submit">Generate New Curriculum</button>
            </form>
            
        </div>

<script src="{{ asset('secretary/main.js') }}"></script>

<script>
    function removeSubject(subjectId) {
        const subjectRow = document.getElementById('subjectRow-' + subjectId);
        const otherSubjectsTable = document.getElementById('otherSubjectsTable').querySelector('tbody');

        // Move the subject to the "Other Subjects" table
        otherSubjectsTable.appendChild(subjectRow);

        // Disable inputs to ensure they are not submitted with the form
        const inputs = subjectRow.querySelectorAll('input, select');
        inputs.forEach(input => input.disabled = true);

        // Change the button to "Add Back"
        const button = subjectRow.querySelector('.remove-btn');
        button.innerText = 'Add Back';
        button.setAttribute('onclick', `addBackSubject(${subjectId})`);
    }

    function addBackSubject(subjectId) {
        const subjectRow = document.getElementById('subjectRow-' + subjectId);
        const subjectTable = document.getElementById('subjectTable').querySelector('tbody');

        // Move the subject back to the original table
        subjectTable.appendChild(subjectRow);

        // Enable inputs to ensure they are submitted with the form
        const inputs = subjectRow.querySelectorAll('input, select');
        inputs.forEach(input => input.disabled = false);

        // Change the button to "Remove"
        const button = subjectRow.querySelector('.remove-btn');
        button.innerText = 'Remove';
        button.setAttribute('onclick', `removeSubject(${subjectId})`);
    }
</script>
    </section>
</main>

@include('secretary.footer')
