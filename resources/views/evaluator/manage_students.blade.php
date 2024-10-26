@include('evaluator.css')

<style>
    .container {
    max-width: 100%;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-toggle {
    background-color: #6c757d;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: block;
    width: auto;
}

.dropdown-toggle:hover {
    background-color: #5a6268;
}

.dropdown-menu {
    display: none;
    position: absolute;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    z-index: 1000;
    width: 300px;
    padding: 10px;
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-menu.active {
    display: block;
}

.dropdown-header {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 10px;
}

.dropdown-item {
    display: flex;
    align-items: center;
    font-size: 14px;
    padding: 12px 16px;
    border-radius: 4px;
    color: #1A1A2E;
    text-decoration: none;
    transition: background-color 0.2s;
    cursor: pointer;
}

.dropdown-item:hover {
    background-color: #A0A4A8;
    color: ghostwhite;
    text-decoration: none;
}

.dropdown-item input {
    margin-right: 10px;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th, .table td {
    border: 1px solid #dee2e6;
    padding: 10px;
    text-align: left;
}

.table th {
    background-color: #f8f9fa;
}

.table tr {
    transition: background-color 0.3s;
    cursor: pointer;
}

.table tr:hover {
    background-color: #f1f1f1;
}

.mb-3 {
    margin-bottom: 20px;
}

.d-flex {

    justify-content: space-between;
    align-items: center;
}

.mt-5 {
    margin-top: 50px;
}

.p-3 {
    padding: 20px;
}

.bi-gear {
    font-size: 16px;
}

.align-right {
    margin-left: auto;
}

.search-wrapper {
    position: relative;
    width: 100%;
}

.input-group {
    position: relative;
    width: 100%;
}

.input-group input[type="text"] {
    width: 100%;
    padding: 10px 40px 10px 20px;
    border-radius: 25px;
    border: 1px solid #ced4da;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    font-size: 16px;
}

.input-group input[type="text"]:focus {
    outline: none;
    border-color: #5a6268;
    box-shadow: 0 0 0 0.2rem rgba(90, 98, 104, 0.25);
}

.input-group .search-clear {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    font-size: 16px;
    cursor: pointer;
    color: #6c757d;
}

.input-group .search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    color: #6c757d;
}

@media (max-width: 768px) {
    .dropdown-menu {
        width: 100%;
    }

    .dropdown-toggle {
        width: 100%;
        text-align: center;
    }

    .table-responsive {
        margin-bottom: 20px;
    }

    .d-flex {
        flex-direction: column;
        align-items: flex-start;
    }

    .dropdown {
        width: 100%;
    }
}

/* Modal Container */
.modal {
    display: none; 
    position: fixed; 
    z-index: 1000; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4); 
}

/* Modal Content */
.modal-content {
    background-color: #fff;
    margin: 15% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 50%; 
    border-radius: 8px;
}

/* Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.pointer
{
    cursor: pointer;
}

.modal-header {
    align-items: center;
    justify-content: space-between;
}

.back-button {
    background: none;
    border: none;
    font-size: 18px;
    color: black;
    cursor: pointer;
}

.back-button:hover {
    color: gray;
}

.card__title {
    margin: 0;
}





</style>

@include('evaluator.sidebar')

<main>
    <section>
        <!-- DASHBOARD -->


        
        <div class="container mt-5">
            <h2 style="text-align: center">Students of {{$department->department_name}}</h2>
        
            {{-- SEARCH FIELD --}}
            <div class="search-wrapper d-flex mb-3">
                <div class="input-group">
                    <span class="search-icon"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" placeholder="Search..." class="form-control">
                    <button class="search-clear" id="searchClear"><i class="bi bi-x-circle"></i></button>
                </div>
            </div>
        
            <div class="d-flex mb-3">
                <div class="dropdown align-right ms-3">
                    <button style="margin:5px" class="dropdown-toggle" type="button" id="settingsDropdown">
                        Filter Search
                    </button>
        
                    <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                        <h6 class="dropdown-header">Select Fields to Display</h6>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="lastname" checked> Last Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="name" checked> First Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="middlename" checked> Middle Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="email" checked> Email</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="course" checked> Course</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="curriculum" checked> Curriculum</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="year" checked> Year</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="semester" checked> Semester</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="academic-year" checked> Academic Year</label></li>
        
                        <hr>
        
                        <h6 class="dropdown-header">Filter by Course</h6>
                        <li><label class="dropdown-item"><input type="radio" name="course-filter" class="course-filter" value="All" checked> All</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="course-filter" class="course-filter" value="BSIT"> BSIT</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="course-filter" class="course-filter" value="BSCS"> BSCS</label></li>
        
                        <hr>
        
                        <h6 class="dropdown-header">Filter by Year</h6>
                        <li><label class="dropdown-item"><input type="radio" name="year-filter" class="year-filter" value="All" checked> All</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="year-filter" class="year-filter" value="1st"> 1st Year</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="year-filter" class="year-filter" value="2nd"> 2nd Year</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="year-filter" class="year-filter" value="3rd"> 3rd Year</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="year-filter" class="year-filter" value="4th"> 4th Year</label></li>
        
                        <hr>
        
                        <h6 class="dropdown-header">Filter by Semester</h6>
                        <li><label class="dropdown-item"><input type="radio" name="semester-filter" class="semester-filter" value="All" checked> All</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="semester-filter" class="semester-filter" value="1st"> 1st Semester</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="semester-filter" class="semester-filter" value="2nd"> 2nd Semester</label></li>
    
                    </ul>
                </div>
                <!-- New Dropdown Button -->
                <div class="dropdown align-right ms-3">
                    <button style="margin: 5px" class="dropdown-toggle" type="button" id="actionDropdown">
                        Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                        <li><a class="dropdown-item" href="{{url('update_multiple_curriculums_courses', $department->id)}}">Update Multiple Curriculums</a></li>
                        <li><a class="dropdown-item" id="updateYearBtn">Update Year</a></li>
                    </ul>
                </div>
            </div>
        
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="field-schoolid">School Id</th>
                            <th class="field-year">Year</th>
                            <th class="field-semester">Semester</th>
                            <th class="field-academic-year">Academic Year</th>
                            <th class="field-lastname">Last Name</th>
                            <th class="field-name">First Name</th>
                            <th class="field-middlename">Middle Name</th>
                            <th class="field-middlename">Suffix Name</th>
                            {{-- <th class="field-middlename">Student Type</th> --}}
                            <th class="field-email">Email</th>
                            <th class="field-course">Course</th>
                            <th class="field-curriculum">Curriculum</th>
                        </tr>
                    </thead>
                    <tbody id="studentTable">
                        @foreach ($students as $student)
                        <tr data-id="{{ $student->id }}">
                            <td class="field-schoolid">{{ $student->school_id }}</td>
                            <td class="field-year">{{ $student->year }}</td>
                            <td class="field-semester">{{ $student->semester }}</td>
                            <td class="field-academic-year">{{ $student->academic_year }}</td>
                            <td class="field-lastname">{{ $student->last_name }}</td>
                            <td class="field-name">{{ $student->name }}</td>
                            <td class="field-middlename">{{ $student->middle_name }}</td>
                            <td class="field-suffixname">{{ $student->name_suffix}}</td>
                            {{-- <td class="field-student-type">{{ $student->student_type}}</td> --}}
                            <td class="field-email">{{ $student->email }}</td>
                            <td class="field-course">{{ $student->course->course_acronym }}</td>
                            <td class="field-curriculum">{{ $student->curriculum->curriculum_name ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </section>
</main>

<!-- Existing Modal Structure -->
<div class="modal" id="updateYearModal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3 class="card__title">Update Student</h3>
        <!-- Laravel Form -->
        <form action="{{ url('update_student_account') }}" method="POST" enctype="multipart/form-data" class="form">
            @csrf  
            <input type="hidden" name="department_id" value="{{ $department->id }}">
            
            <div class="form__group">
                <label for="academic_year" class="form__label">Academic Year</label>
                <select name="academic_year" id="academic_year" class="form__input" required>
                    <option value="" disabled selected>Select Academic Year</option>
                    @foreach ($academicYears as $academicYear)
                        <option value="{{ $academicYear }}">{{ $academicYear }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form__group">
                <label for="excel_file" class="form__label">Upload Excel File</label>
                <input type="file" name="excel_file" id="excel_file" class="form__input" accept=".xlsx, .xls" required>
            </div>
            
            <button style="margin-top: 5px" type="submit" class="form__button">Submit</button>
        </form>
    </div>
</div>








<script>document.addEventListener('DOMContentLoaded', function () {
    // Dropdown functionality for settings
    const dropdownToggle = document.getElementById('settingsDropdown');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    const fieldCheckboxes = document.querySelectorAll('.field-checkbox');
    const searchInput = document.getElementById('searchInput');
    const searchClear = document.getElementById('searchClear');
    const courseFilters = document.querySelectorAll('.course-filter');
    const yearFilters = document.querySelectorAll('.year-filter');
    const semesterFilters = document.querySelectorAll('.semester-filter');

    dropdownToggle.addEventListener('click', function () {
        dropdownMenu.classList.toggle('active');
    });

    document.addEventListener('click', function (e) {
        if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('active');
        }
    });

    fieldCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const field = this.dataset.field;
            const cells = document.querySelectorAll(`.field-${field}`);
            cells.forEach(cell => {
                cell.style.display = this.checked ? '' : 'none';
            });
        });
    });

    searchInput.addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#studentTable tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const text = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    searchClear.addEventListener('click', function () {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
    });

    courseFilters.forEach(filter => {
        filter.addEventListener('change', function () {
            filterTable();
        });
    });

    yearFilters.forEach(filter => {
        filter.addEventListener('change', function () {
            filterTable();
        });
    });

    semesterFilters.forEach(filter => {
        filter.addEventListener('change', function () {
            filterTable();
        });
    });

    function filterTable() {
        const courseFilter = document.querySelector('input[name="course-filter"]:checked').value;
        const yearFilter = document.querySelector('input[name="year-filter"]:checked').value;
        const semesterFilter = document.querySelector('input[name="semester-filter"]:checked').value;
        const rows = document.querySelectorAll('#studentTable tr');

        rows.forEach(row => {
            const course = row.querySelector('.field-course').textContent.trim();
            const year = row.querySelector('.field-year').textContent.trim();
            const semester = row.querySelector('.field-semester').textContent.trim();

            const courseMatch = courseFilter === 'All' || course.includes(courseFilter);
            const yearMatch = yearFilter === 'All' || year.includes(yearFilter);
            const semesterMatch = semesterFilter === 'All' || semester.includes(semesterFilter);

            row.style.display = courseMatch && yearMatch && semesterMatch ? '' : 'none';
        });
    }

    // Make rows clickable
    const baseUrl = "{{ url('/manage_student_details') }}";
    document.querySelectorAll('#studentTable tr').forEach(row => {
        row.addEventListener('click', function () {
            const studentId = this.dataset.id;
            window.location.href = `${baseUrl}/${studentId}`;
        });
    });

    // Dropdown functionality for actions
    const actionDropdownToggle = document.getElementById('actionDropdown');
    const actionDropdownMenu = actionDropdownToggle.nextElementSibling;

    actionDropdownToggle.addEventListener('click', function () {
        actionDropdownMenu.classList.toggle('active');
    });

    document.addEventListener('click', function (e) {
        if (!actionDropdownToggle.contains(e.target) && !actionDropdownMenu.contains(e.target)) {
            actionDropdownMenu.classList.remove('active');
        }
    });

    // Modal functionality for Update Year
    const updateYearModal = document.getElementById('updateYearModal');
    const updateYearBtn = document.getElementById('updateYearBtn');
    const closeYearModal = document.querySelector('#updateYearModal .close');

    updateYearBtn.onclick = function() {
        updateYearModal.style.display = 'block';
    }

    closeYearModal.onclick = function() {
        updateYearModal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target === updateYearModal) {
            updateYearModal.style.display = 'none';
        }
    }

    // Modal functionality for Update Semester Only
    const updateSemesterOnlyBtn = document.getElementById('updateSemesterOnlyBtn');
    const updateSemesterModal = document.getElementById('updateSemesterModal');
    const backToMainModalBtn = document.getElementById('backToMainModal');
    const closeSemesterModal = document.querySelector('#updateSemesterModal .close');

    updateSemesterOnlyBtn.onclick = function() {
        updateYearModal.style.display = 'none';
        updateSemesterModal.style.display = 'block';
    }

    backToMainModalBtn.onclick = function() {
        updateSemesterModal.style.display = 'none';
        updateYearModal.style.display = 'block';
    }

    closeSemesterModal.onclick = function() {
        updateSemesterModal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target === updateSemesterModal) {
            updateSemesterModal.style.display = 'none';
        }
    }
});

</script>



@include('secretary.footer')


<!--========== MAIN JS ==========-->
<script src="secretary/main.js"></script>

</body>
</html>
