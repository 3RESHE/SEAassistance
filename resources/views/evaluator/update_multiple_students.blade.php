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

</style>

@include('evaluator.sidebar')



<main>
    <section>
        <!-- DASHBOARD -->


        <div class="card">
            <h3 class="card__title">Upload Excel to Give Curriculum</h3>
        
            <!-- Laravel Form -->
            <form action="{{ url('give_multiple_curriculum') }}" method="POST" enctype="multipart/form-data" class="form">
                @csrf  
                
                <div class="form__group">
                    <label for="curriculum" class="form__label">Curriculum</label>
                    <select name="curriculum_id" id="curriculum" class="form__input" required>
                        <option value="" disabled selected>Select curriculum</option>
        
                        <!-- Loop through curriculums passed from the controller -->
                        @foreach ($curriculums as $curriculum)
                            <option value="{{ $curriculum->id }}">{{ $curriculum->curriculum_name }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="course_id" id="course_id" value="{{$course->id}}">
        
                <div class="form__group">
                    <label for="excel_file" class="form__label">Upload Excel File</label>
                    <input type="file" name="excel_file" id="excel_file" class="form__input" accept=".xlsx, .xls" required>
                </div>
                
                <button type="submit" class="form__button">Submit</button>
            </form>
        </div>
        

        <div class="container mt-5">
            <h2 style="text-align: center">{{$course->course_name}}</h2>


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
                    
                        <hr>
                    
                        <h6 class="dropdown-header">Filter by Year</h6>
                        <li><label class="dropdown-item"><input type="radio" name="year-filter" class="year-filter" value="All" checked> All</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="year-filter" class="year-filter" value="1st"> 1st Year</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="year-filter" class="year-filter" value="2nd"> 2nd Year</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="year-filter" class="year-filter" value="3rd"> 3rd Year</label></li>
                        <li><label class="dropdown-item"><input type="radio" name="year-filter" class="year-filter" value="4th"> 4th Year</label></li>
                    </ul>
                    
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="field-schoolid">School Id</th>
                            <th class="field-lastname">Last Name</th>
                            <th class="field-name">First Name</th>
                            <th class="field-middlename">Middle Name</th>
                            <th class="field-email">Email</th>
                            <th class="field-email">Course</th>
                            
                            
                            <th class="field-curriculum">Curriculum</th>
                            <th class="field-year">Year</th>
           
                        </tr>
                    </thead>
                    <tbody id="studentTable">
                        @foreach ($students as $student)
                        <tr data-id="{{ $student->id }}">
                            <td class="field-schoolid">{{ $student->school_id }}</td>
                            <td class="field-lastname">{{ $student->last_name }}</td>
                            <td class="field-name">{{ $student->name }}</td>
                            <td class="field-middlename">{{ $student->middle_name }}</td>
                            <td class="field-email">{{ $student->email }}</td>
                            <td class="field-course">{{ $student->course->course_acronym }}</td>
                            <td class="field-curriculum">{{ $student->curriculum->curriculum_name ?? 'N/A' }}</td>
                            <td class="field-year">{{ $student->year }}</td>
                        </tr>
                        
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownToggle = document.getElementById('settingsDropdown');
        const dropdownMenu = document.querySelector('.dropdown-menu');
        const fieldCheckboxes = document.querySelectorAll('.field-checkbox');
        const searchInput = document.getElementById('searchInput');
        const searchClear = document.getElementById('searchClear');
        const yearFilters = document.querySelectorAll('.year-filter');

        // Toggle dropdown menu
        dropdownToggle.addEventListener('click', function () {
            dropdownMenu.classList.toggle('active');
        });

        // Hide dropdown menu if clicked outside
        document.addEventListener('click', function (e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('active');
            }
        });

        // Toggle field visibility
        fieldCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const field = this.dataset.field;
                const cells = document.querySelectorAll(`.field-${field}`);
                cells.forEach(cell => {
                    cell.style.display = this.checked ? '' : 'none';
                });
            });
        });

        // Live search
        searchInput.addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#studentTable tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const text = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Clear search input
        searchClear.addEventListener('click', function () {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        });

        // Filter table by year
        function filterTable() {
            const yearFilter = document.querySelector('input[name="year-filter"]:checked').value;
            const rows = document.querySelectorAll('#studentTable tr');

            rows.forEach(row => {
                const year = row.querySelector('.field-year').textContent;
                const yearMatch = yearFilter === 'All' || year.includes(yearFilter);
                row.style.display = yearMatch ? '' : 'none';
            });
        }

        // Apply year filters on change
        yearFilters.forEach(filter => {
            filter.addEventListener('change', filterTable);
        });

        // Make rows clickable
        const baseUrl = "{{ url('/manage_student_details') }}";
        document.querySelectorAll('#studentTable tr').forEach(row => {
            row.addEventListener('click', function () {
                const studentId = this.dataset.id;
                window.location.href = `${baseUrl}/${studentId}`;
            });
        });

        // New actions dropdown
        const actionDropdownToggle = document.getElementById('actionDropdown');
        if (actionDropdownToggle) {
            const actionDropdownMenu = actionDropdownToggle.nextElementSibling;

            actionDropdownToggle.addEventListener('click', function () {
                actionDropdownMenu.classList.toggle('active');
            });

            document.addEventListener('click', function (e) {
                if (!actionDropdownToggle.contains(e.target) && !actionDropdownMenu.contains(e.target)) {
                    actionDropdownMenu.classList.remove('active');
                }
            });
        }
    });
</script>



<!--========== MAIN JS ==========-->
<script src="secretary/main.js"></script>


@include('secretary.footer')

</body>
</html>