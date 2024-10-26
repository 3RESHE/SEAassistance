@include('secretary.css')
@include('secretary.sidebar')

<!--========== CONTENTS ==========-->
<br>
<main>
    <section>
        <!-- Courses -->
        <div class="curriculum-container">
            <h2 class="curriculum-title">Manage Prerequisite and Corequisite Subjects for : <br><span style="color: skyblue">{{$course->course_name}}</span> </h2>
            
            <!-- Live Search Input -->
            <div class="search-bar">
                <input type="text" id="subjectSearch" class="curriculum-form__input" placeholder="Search for subjects or course codes...">
                <span class="search-icon"><i class="fas fa-search"></i></span>
            </div>

            <form action="{{ url('updateSubjectRelations') }}" method="POST" class="curriculum-form">
                @csrf
                <div class="curriculum-form__table-container">
                    <table class="curriculum-form__table" id="subjectsTable">
                        <thead>
                            <tr>
                                <th>Descriptive Title</th>
                                <th>Course Code</th>
                                <th>Units</th>
                                <th>Required Year Level</th>
                                <th>Prerequisite Subjects</th>
                                <th>Corequisite Subjects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjects as $subject)
                            <tr>
                                <td>
                                    <input type="text" class="curriculum-form__input" value="{{ $subject->descriptive_title }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="curriculum-form__input" value="{{ $subject->subject_code }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="curriculum-form__input" value="{{ $subject->units }}" readonly>
                                </td>
                                <td>
                                    <select name="required_year_level[{{ $subject->id }}]" class="curriculum-form__select">
                                        <option value="">Select Year Level</option>
                                        <option value="1st" {{ $subject->curriculumSubjects->firstWhere('subject_id', $subject->id)->required_year_level ?? '' == '1st' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd" {{ $subject->curriculumSubjects->firstWhere('subject_id', $subject->id)->required_year_level ?? '' == '2nd' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd" {{ $subject->curriculumSubjects->firstWhere('subject_id', $subject->id)->required_year_level ?? '' == '3rd' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th" {{ $subject->curriculumSubjects->firstWhere('subject_id', $subject->id)->required_year_level ?? '' == '4th' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="curriculum-form__btn" onclick="openModal('preReqModal', {{ $subject->id }})">
                                        @if ($subject->prerequisites->isNotEmpty())
                                            {{ $subject->prerequisites->pluck('descriptive_title')->join(', ') }}
                                        @else
                                            Select Prerequisites
                                        @endif
                                    </button>
                                    <input type="hidden" name="pre_requisite_subjects[{{ $subject->id }}]" id="preReqSubjects_{{ $subject->id }}" value="{{ $subject->prerequisites->pluck('id')->join(',') }}">
                                </td>
                                <td>
                                    <button type="button" class="curriculum-form__btn" onclick="openModal('coReqModal', {{ $subject->id }})">
                                        @if ($subject->corequisites->isNotEmpty())
                                            {{ $subject->corequisites->pluck('descriptive_title')->join(', ') }}
                                        @else
                                            Select Corequisites
                                        @endif
                                    </button>
                                    <input type="hidden" name="co_requisite_subjects[{{ $subject->id }}]" id="coReqSubjects_{{ $subject->id }}" value="{{ $subject->corequisites->pluck('id')->join(',') }}">
                                </td>
                                <input type="hidden" name="subject_ids[]" value="{{ $subject->id }}">
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    <!-- In the form, change the button type -->
<button type="button" class="curriculum-form__submit" onclick="confirmSubmission()">Update Subjects</button>

            </form>
        </div>

        <!-- Prerequisite Subjects Modal -->
        <div id="preReqModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal('preReqModal')">&times;</span>
                <h3>Select Prerequisite Subjects</h3>
                <input type="text" id="searchModal" class="modal-search" placeholder="Search subjects or course codes...">
                <div id="preReqList" class="modal-list">
                    @foreach ($allSubjects as $subject)
                    <label>
                        <input type="checkbox" value="{{ $subject->id }}" {{ in_array($subject->id, $subject->prerequisites->pluck('id')->toArray()) ? 'checked' : '' }}>
                        {{ $subject->descriptive_title }} ({{ $subject->subject_code }}) <!-- Added Course Code Display -->
                    </label>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="modal-save-btn" onclick="saveSelection('preReqModal')">Save</button>
                </div>
            </div>
        </div>

        <!-- Corequisite Subjects Modal -->
        <div id="coReqModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal('coReqModal')">&times;</span>
                <h3>Select Corequisite Subjects</h3>
                <input type="text" id="searchModalCoReq" class="modal-search" placeholder="Search subjects or course codes...">
                <div id="coReqList" class="modal-list">
                    @foreach ($allSubjects as $subject)
                    <label>
                        <input type="checkbox" value="{{ $subject->id }}" {{ in_array($subject->id, $subject->corequisites->pluck('id')->toArray()) ? 'checked' : '' }}>
                        {{ $subject->descriptive_title }} ({{ $subject->subject_code }}) <!-- Added Course Code Display -->
                    </label>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="modal-save-btn" onclick="saveSelection('coReqModal')">Save</button>
                </div>
            </div>
        </div>
    </section>
</main>

@include('secretary.footer')

<!--========== MAIN JS ==========-->
<script src="{{ asset('secretary/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    let currentSubjectId;

    function openModal(modalId, subjectId) {
        currentSubjectId = subjectId;
        document.getElementById(modalId).style.display = 'block';
        populateModalLists(modalId);
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function saveSelection(modalId) {
        const selectedCheckboxes = Array.from(document.querySelectorAll(`#${modalId} input[type="checkbox"]:checked`));
        const selectedIds = selectedCheckboxes.map(cb => cb.value);
        const selectedTexts = selectedCheckboxes.map(cb => cb.nextSibling.textContent.trim()).join(', ');

        // Update the hidden input with the selected subject IDs
        const inputId = modalId === 'preReqModal' ? 'preReqSubjects_' : 'coReqSubjects_';
        document.getElementById(`${inputId}${currentSubjectId}`).value = selectedIds.join(',');

        const subjectButton = document.querySelector(`button[onclick="openModal('${modalId}', ${currentSubjectId})"]`);
        subjectButton.textContent = selectedTexts || `Select ${modalId === 'preReqModal' ? 'Prerequisites' : 'Corequisites'}`;

        closeModal(modalId);
    }

    function confirmSubmission() {
    Swal.fire({
        title: 'Do you want to update the prerequisite and corequisite subjects?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, submit the form
            document.querySelector('.curriculum-form').submit();
        }
    });
}



    function populateModalLists(modalId) {
        const listId = modalId === 'preReqModal' ? 'preReqList' : 'coReqList';
        const subjects = @json($allSubjects);
        const listContainer = document.getElementById(listId);
        const hiddenInputValue = document.getElementById(modalId === 'preReqModal' ? `preReqSubjects_${currentSubjectId}` : `coReqSubjects_${currentSubjectId}`).value.split(',');

        listContainer.innerHTML = '';

        subjects.forEach(subject => {
            const isChecked = hiddenInputValue.includes(subject.id.toString());
            listContainer.innerHTML += `
                <label>
                    <input type="checkbox" value="${subject.id}" ${isChecked ? 'checked' : ''}>
                    ${subject.descriptive_title} (${subject.subject_code}) <!-- Added Course Code Display -->
                </label>
            `;
        });
    }

    // Unified search for both modals
    document.getElementById('searchModal').addEventListener('keyup', function() {
        filterSubjects('searchModal', 'preReqList');
    });

    document.getElementById('searchModalCoReq').addEventListener('keyup', function() {
        filterSubjects('searchModalCoReq', 'coReqList');
    });

    function filterSubjects(inputId, listId) {
        const input = document.getElementById(inputId);
        const filter = input.value.toLowerCase();
        const labels = document.querySelectorAll(`#${listId} label`);

        labels.forEach(label => {
            const text = label.textContent || label.innerText;
            const courseCode = text.match(/\((.*?)\)/)[1]; // Extract course code from label text
            label.style.display = text.toLowerCase().includes(filter) || courseCode.toLowerCase().includes(filter) ? '' : 'none';
        });
    }

    // Live Search for main table
    document.getElementById('subjectSearch').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#subjectsTable tbody tr');

        rows.forEach(row => {
            const descriptiveTitle = row.cells[0].querySelector('input').value.toLowerCase();
            const courseCode = row.cells[1].querySelector('input').value.toLowerCase();
            const units = row.cells[2].querySelector('input').value.toLowerCase();

            if (descriptiveTitle.includes(searchValue) || courseCode.includes(searchValue) || units.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<style>
    /* Styles for the search bar */
    .search-bar {
        position: relative;
        margin-bottom: 20px;
    }

    .curriculum-form__input {
        width: 100%;
        padding: 10px 40px 10px 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }

    .modal-search {
        width: 100%;
        padding: 10px 20px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>
