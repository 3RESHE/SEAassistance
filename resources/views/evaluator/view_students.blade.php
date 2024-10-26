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
        right: 0;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        z-index: 1000;
        width: 300px;
        padding: 10px;
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
        padding: 5px 0;
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
    }

    .table tr:hover {
        background-color: #f1f1f1;
    }

    .mb-3 {
        margin-bottom: 20px;
    }

    .d-flex {
        display: flex;
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

    .input-group .search-icon i {
        font-size: 18px;
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

        .dropdown-menu {
            position: static;
            box-shadow: none;
            border-radius: 0;
        }

        .dropdown-item {
            justify-content: space-between;
        }
    }
</style>

@include('evaluator.sidebar')

<main>
    <section>
        <!-- DASHBOARD -->

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

            <div class="d-flex justify-content-end mb-3">
                <div class="dropdown align-right">
                    <button class="dropdown-toggle" type="button" id="settingsDropdown">
                        <i class="bx bx-cog">Settings</i>
                    </button>
                    <ul class="dropdown-menu p-3" aria-labelledby="settingsDropdown">
                        <h6 class="dropdown-header">Select Fields to Display</h6>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="lastname" checked> Last Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="firstname" checked> First Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="middlename" checked> Middle Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="email" checked> Email</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="course" checked> Course</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="field-checkbox" data-field="year" checked> Year</label></li>

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
                    </ul>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="field-lastname">Last Name</th>
                            <th class="field-firstname">First Name</th>
                            <th class="field-middlename">Middle Name</th>
                            <th class="field-email">Email</th>
                            <th class="field-course">Course</th>
                            <th class="field-year">Year</th>
                        </tr>
                    </thead>
                    
                    <tbody id="studentTableBody">
                        @foreach ($users as $user)
                        <tr data-course="{{ $user->course->course_acronym }}" data-year="{{ $user->year }}">
                            <td class="field-lastname">{{ $user->last_name }}</td>
                            <td class="field-firstname">{{ $user->name }}</td>
                            <td class="field-middlename">{{ $user->middle_name }}</td>
                            <td class="field-email">{{ $user->email }}</td>
                            <td class="field-course">{{ $user->course->course_acronym }}</td>
                            <td class="field-year">{{ $user->year }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const searchInput = document.getElementById("searchInput");
                const searchClear = document.getElementById("searchClear");
                const studentTableBody = document.getElementById("studentTableBody");
                const fieldCheckboxes = document.querySelectorAll(".field-checkbox");
                const courseFilters = document.querySelectorAll(".course-filter");
                const yearFilters = document.querySelectorAll(".year-filter");

                // Search functionality
                searchInput.addEventListener("input", function() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const rows = studentTableBody.querySelectorAll("tr");

                    rows.forEach(row => {
                        const cells = row.querySelectorAll("td");
                        const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(" ");
                        row.style.display = rowText.includes(searchTerm) ? "" : "none";
                    });
                });

                // Clear search functionality
                searchClear.addEventListener("click", function() {
                    searchInput.value = "";
                    searchInput.dispatchEvent(new Event("input"));
                });

                // Dropdown functionality
                document.getElementById("settingsDropdown").addEventListener("click", function() {
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle("active");
                });

                // Show/hide columns based on checkbox selection
                fieldCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener("change", function() {
                        const fieldClass = "field-" + this.getAttribute("data-field");
                        const cells = document.querySelectorAll("." + fieldClass);
                        cells.forEach(cell => {
                            cell.style.display = this.checked ? "" : "none";
                        });
                    });
                });

                // Filter rows based on course and year
                courseFilters.forEach(filter => {
                    filter.addEventListener("change", applyFilters);
                });

                yearFilters.forEach(filter => {
                    filter.addEventListener("change", applyFilters);
                });

                function applyFilters() {
                    const selectedCourse = document.querySelector(".course-filter:checked").value;
                    const selectedYear = document.querySelector(".year-filter:checked").value;

                    const rows = studentTableBody.querySelectorAll("tr");
                    rows.forEach(row => {
                        const rowCourse = row.getAttribute("data-course");
                        const rowYear = row.getAttribute("data-year");

                        const courseMatch = selectedCourse === "All" || rowCourse === selectedCourse;
                        const yearMatch = selectedYear === "All" || rowYear === selectedYear;

                        row.style.display = courseMatch && yearMatch ? "" : "none";
                    });
                }
            });
        </script>
    </section>
</main>



        <!--========== MAIN JS ==========-->
        <script src="secretary/main.js"></script>
        @include('secretary.footer')
    </body>
    </html>
    