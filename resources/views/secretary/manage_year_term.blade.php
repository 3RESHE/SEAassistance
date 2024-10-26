@include('secretary.css')
@include('secretary.sidebar')

<!--========== CONTENTS ==========-->
<br>
<main>
    <section>
        <!-- Courses -->
        <div class="curriculum-container">
            <h2 class="curriculum-title">Manage Pre Requisite and Co Requisite Subjects</h2>
            <form action="{{ route('updateSubjectRelations') }}" method="POST" class="curriculum-form">
                @csrf
                <div class="curriculum-form__table-container">
                    <table class="curriculum-form__table">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Year Term</th>
                                <th>Descriptive Title</th>
                                <th>Course Code</th>
                                <th>Units</th>
                                <th>Pre Requisite Subjects</th>
                                <th>Co Requisite Subjects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $index => $subject)
                            <tr>
                                <td>
                                    <select name="year[]" class="curriculum-form__select">
                                        <option value="1st">1st Year</option>
                                        <option value="2nd">2nd Year</option>
                                        <option value="3rd">3rd Year</option>
                                        <option value="4th">4th Year</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="year_term[]" class="curriculum-form__select">
                                        <option value="1st_sem">1st Sem</option>
                                        <option value="2nd_sem">2nd Sem</option>
                                    </select>
                                </td>
                                <td><input type="text" name="descriptive_title[]" class="curriculum-form__input" value="{{ $subject->descriptive_title }}" readonly></td>
                                <td><input type="text" name="subject_code[]" class="curriculum-form__input" value="{{ $subject->subject_code }}" readonly></td>
                                <td><input type="text" name="units[]" class="curriculum-form__input" value="{{ $subject->units }}" readonly></td>
                                <td><button type="button" class="curriculum-form__btn" onclick="openModal('preReqModal', {{ $subject->id }})">Select Pre Requisites</button></td>
                                <td><button type="button" class="curriculum-form__btn" onclick="openModal('coReqModal', {{ $subject->id }})">Select Co Requisites</button></td>
                                <input type="hidden" name="subject_id[]" value="{{ $subject->id }}">
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="curriculum-form__submit">Update Subjects</button>
            </form>
        </div>

        <!-- Pre Requisite Subjects Modal -->
        <div id="preReqModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal('preReqModal')">&times;</span>
                <h3>Select Pre Requisite Subjects</h3>
                <input type="text" id="preReqSearch" class="modal-search" placeholder="Search subjects...">
                <div id="preReqList" class="modal-list">
                    <!-- List will be populated dynamically -->
                </div>
                <div class="modal-footer">
                    <button class="modal-save-btn" onclick="saveSelection('preReqModal')">Save</button>
                </div>
            </div>
        </div>

        <!-- Co Requisite Subjects Modal -->
        <div id="coReqModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal('coReqModal')">&times;</span>
                <h3>Select Co Requisite Subjects</h3>
                <input type="text" id="coReqSearch" class="modal-search" placeholder="Search subjects...">
                <div id="coReqList" class="modal-list">
                    <!-- List will be populated dynamically -->
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
<script src="secretary/main.js"></script>

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
        const selectedSubjects = selectedCheckboxes.map(cb => cb.nextSibling.textContent.trim()).join(', ');

        const subjectButton = document.querySelector(`button[onclick="openModal('${modalId}', ${currentSubjectId})"]`);
        subjectButton.textContent = selectedSubjects || `Select ${modalId === 'preReqModal' ? 'Pre Requisites' : 'Co Requisites'}`;

        closeModal(modalId);
    }

    function populateModalLists(modalId) {
        const listId = modalId === 'preReqModal' ? 'preReqList' : 'coReqList';
        const subjects = @json($allSubjects);

        const listContainer = document.getElementById(listId);
        listContainer.innerHTML = '';

        subjects.forEach(subject => {
            listContainer.innerHTML += `
                <label>
                    <input type="checkbox" name="${modalId === 'preReqModal' ? 'pre_requisite_subjects[${currentSubjectId}][]' : 'co_requisite_subjects[${currentSubjectId}][]'}" value="${subject.id}">
                    ${subject.descriptive_title}
                </label>
            `;
        });
    }

    document.getElementById('preReqSearch').addEventListener('keyup', function() {
        filterSubjects('preReqSearch', 'preReqList');
    });

    document.getElementById('coReqSearch').addEventListener('keyup', function() {
        filterSubjects('coReqSearch', 'coReqList');
    });

    function filterSubjects(inputId, listId) {
        const input = document.getElementById(inputId);
        const filter = input.value.toLowerCase();
        const list = document.getElementById(listId);
        const labels = list.getElementsByTagName('label');

        Array.from(labels).forEach(label => {
            const text = label.textContent || label.innerText;
            label.style.display = text.toLowerCase().includes(filter) ? '' : 'none';
        });
    }
</script>
