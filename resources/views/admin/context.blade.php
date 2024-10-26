<!-- Profile Form Card -->
<div class="card">
    <h3 class="card__title">Create Department</h3>
    <form action="#" class="form">
        <div class="form__group">
            <label for="department_name" class="form__label">Department Name</label>
            <input type="text" name="department_name" id="department_name" class="form__input" placeholder="Enter Department Name">
        </div>
        <div class="form__group">
            <label for="department_logo" class="form__label">Department Logo</label>
            <input type="file" name="department_logo" id="department_logo" class="form__input">
        </div>
        <button type="submit" class="form__button">Update Profile</button>
    </form>
</div>



<!-- DEPARTMENTS -->

    <div class="big-card">
        <h1 style="text-align: center;">DEPARTMENTS</h1>
        <div class="department-cards">
            
            <a href="#" class="department-card-link">
                <div class="department-card">
                    <img src="admin/coi.png" alt="Department Logo" class="department-logo">
                    <span class="department-name">Department 1</span>
                </div>
            </a>
                        
            <a href="#" class="department-card-link">
                <div class="department-card">
                    <img src="admin/coi.png" alt="Department Logo" class="department-logo">
                    <span class="department-name">Department 1</span>
                </div>
            </a>
                        
            <a href="#" class="department-card-link">
                <div class="department-card">
                    <img src="admin/coi.png" alt="Department Logo" class="department-logo">
                    <span class="department-name">Department 1</span>
                </div>
            </a>
                        
            <a href="#" class="department-card-link">
                <div class="department-card">
                    <img src="admin/coi.png" alt="Department Logo" class="department-logo">
                    <span class="department-name">Department 1</span>
                </div>
            </a>
                        
            <a href="#" class="department-card-link">
                <div class="department-card">
                    <img src="admin/coi.png" alt="Department Logo" class="department-logo">
                    <span class="department-name">Department 1</span>
                </div>
            </a>
                        
            <a href="#" class="department-card-link">
                <div class="department-card">
                    <img src="admin/coi.png" alt="Department Logo" class="department-logo">
                    <span class="department-name">Department 1</span>
                </div>
            </a>
                        
            <a href="#" class="department-card-link">
                <div class="department-card">
                    <img src="admin/coi.png" alt="Department Logo" class="department-logo">
                    <span class="department-name">Department 1</span>
                </div>
            </a>
                        
            <a href="#" class="department-card-link">
                <div class="department-card">
                    <img src="admin/coi.png" alt="Department Logo" class="department-logo">
                    <span class="department-name">Department 1</span>
                </div>
            </a>

            

            

            <!-- Add more department cards as needed -->
        </div>
    </div>

<!-- DEPARTMENTS -->


<div class="card">
<h3 class="card__title">Create Course for : "DEPARTMENT NAME"</h3>
<form action="/your-submit-url" method="POST" class="form">
<input type="hidden" name="department" value="your-department-value">

<div class="form__group">
    <label for="course_name" class="form__label">Course Name</label>
    <input type="text" name="course_name" id="course_name" class="form__input" placeholder="Enter Course Name" required>
</div>

<div class="form__group">
    <label for="course_acronym" class="form__label">Course Acronym</label>
    <input type="text" name="course_acronym" id="course_acronym" class="form__input" placeholder="Enter Course Acronym" required>
</div>

<button type="submit" class="form__button">Submit</button>
</form>
</div>






<div class="card">
<h3 class="card__title">Create Evaluator</h3>
<form action="/your-submit-url" method="POST" class="form">

<div class="form__group">
    <label for="school_id" class="form__label">School ID</label>
    <input type="text" name="school_id" id="school_id" class="form__input" placeholder="Enter School ID" required>
</div>

<div class="form__group">
    <label for="email" class="form__label">Email</label>
    <input type="email" name="email" id="email" class="form__input" placeholder="Enter Email" required>
</div>

<div class="form__group">
    <label for="first_name" class="form__label">First Name</label>
    <input type="text" name="first_name" id="first_name" class="form__input" placeholder="Enter First Name" required>
</div>

<div class="form__group">
    <label for="last_name" class="form__label">Last Name</label>
    <input type="text" name="last_name" id="last_name" class="form__input" placeholder="Enter Last Name" required>
</div>

<div class="form__group">
    <label for="middle_name" class="form__label">Middle Name</label>
    <input type="text" name="middle_name" id="middle_name" class="form__input" placeholder="Enter Middle Name">
</div>

<div class="form__group">
    <label for="department" class="form__label">Department</label>
    <select name="department" id="department" class="form__input" required>
        <option value="" disabled selected>Select Department</option>
        <!-- Add your department options here -->
        <option value="department_1">Department 1</option>
        <option value="department_2">Department 2</option>
        <!-- Add more options as needed -->
    </select>
</div>

<button type="submit" class="form__button">Create Evaluator</button>
</form>
</div>




<div class="card">
<h3 class="card__title">Create Secretary</h3>
<form action="/your-submit-url" method="POST" class="form">

<div class="form__group">
    <label for="school_id" class="form__label">School ID</label>
    <input type="text" name="school_id" id="school_id" class="form__input" placeholder="Enter School ID" required>
</div>

<div class="form__group">
    <label for="email" class="form__label">Email</label>
    <input type="email" name="email" id="email" class="form__input" placeholder="Enter Email" required>
</div>

<div class="form__group">
    <label for="first_name" class="form__label">First Name</label>
    <input type="text" name="first_name" id="first_name" class="form__input" placeholder="Enter First Name" required>
</div>

<div class="form__group">
    <label for="last_name" class="form__label">Last Name</label>
    <input type="text" name="last_name" id="last_name" class="form__input" placeholder="Enter Last Name" required>
</div>

<div class="form__group">
    <label for="middle_name" class="form__label">Middle Name</label>
    <input type="text" name="middle_name" id="middle_name" class="form__input" placeholder="Enter Middle Name">
</div>

<div class="form__group">
    <label for="department" class="form__label">Department</label>
    <select name="department" id="department" class="form__input" required>
        <option value="" disabled selected>Select Department</option>
        <!-- Add your department options here -->
        <option value="department_1">Department 1</option>
        <option value="department_2">Department 2</option>
        <!-- Add more options as needed -->
    </select>
</div>

<button type="submit" class="form__button">Create Evaluator</button>
</form>
</div>





<div class="card">
<h3 class="card__title">Create Student Account</h3>
<form action="/your-submit-url" method="POST" enctype="multipart/form-data" class="form">

<!-- Hidden Fields -->
<input type="hidden" name="department" id="department" value="hidden_department_value">
<input type="hidden" name="course" id="course" value="hidden_course_value">

<div class="form__group">
    <label for="year" class="form__label">Year</label>
    <select name="year" id="year" class="form__input" required>
        <option value="" disabled selected>Select Year</option>
        <option value="1st">1st Year</option>
        <option value="2nd">2nd Year</option>
        <option value="3rd">3rd Year</option>
        <option value="4th">4th Year</option>
    </select>
</div>

<!-- <div class="form__group">
    <label for="start_year" class="form__label">Academic Year Start</label>
    <input type="number" name="start_year" id="start_year" class="form__input" placeholder="Enter start year (e.g., 2023)" min="2000" required>
</div>

<div class="form__group">
    <label for="end_year" class="form__label">Academic Year End</label>
    <input type="number" name="end_year" id="end_year" class="form__input" placeholder="Enter end year (e.g., 2024)" min="2000" required>
</div> -->
<!--     
<div class="form__group">
    <label for="semester" class="form__label">Semester</label>
    <select name="semester" id="semester" class="form__input" required>
        <option value="" disabled selected>Select Semester</option>
        <option value="1st">1st Semester</option>
        <option value="2nd">2nd Semester</option>
    </select>
</div> -->

<div class="form__group">
    <label for="excel_file" class="form__label">Upload Excel File</label>
    <input type="file" name="excel_file" id="excel_file" class="form__input" accept=".xlsx, .xls" required>
</div>

<button type="submit" class="form__button">Create Student Account</button>
</form>
</div>




<div class="card">
<h3 class="card__title">Update Students</h3>
<form action="/your-submit-url" method="POST" enctype="multipart/form-data" class="form">

<!-- Hidden Fields -->
<input type="hidden" name="department" id="department" value="hidden_department_value">
<input type="hidden" name="course" id="course" value="hidden_course_value">

<div class="form__group">
    <label for="year" class="form__label">Year</label>
    <select name="year" id="year" class="form__input" required>
        <option value="" disabled selected>Select Year</option>
        <option value="1st">1st Year</option>
        <option value="2nd">2nd Year</option>
        <option value="3rd">3rd Year</option>
        <option value="4th">4th Year</option>
    </select>
</div>

<div class="form__group">
    <label for="start_year" class="form__label">Academic Year Start</label>
    <input type="number" name="start_year" id="start_year" class="form__input" placeholder="Enter start year (e.g., 2023)" min="2000" required>
</div>

<div class="form__group">
    <label for="end_year" class="form__label">Academic Year End</label>
    <input type="number" name="end_year" id="end_year" class="form__input" placeholder="Enter end year (e.g., 2024)" min="2000" required>
</div>



<div class="form__group">
    <label for="semester" class="form__label">Semester</label>
    <select name="semester" id="semester" class="form__input" required>
        <option value="" disabled selected>Select Semester</option>
        <option value="1st">1st Semester</option>
        <option value="2nd">2nd Semester</option>
    </select>
</div>

<div class="form__group">
    <label for="excel_file" class="form__label">Upload Excel File</label>
    <input type="file" name="excel_file" id="excel_file" class="form__input" accept=".xlsx, .xls" required>
</div>

<button type="submit" class="form__button">Update Student</button>
</form>
</div>



<!-- SHOW SECRETARY -->
<div class="big-card">
<h3 class="card__title">Secretary Users</h3>
<div class="table-responsive">
<table class="table">
<thead>
    <tr>
        <th>School Id</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Middle Name</th>
        <th>Department</th>
        <th>Email</th>
    </tr>
</thead>
<tbody>
    <!-- Example row, you can add more rows here -->
    <tr>
        <td>001</td>
        <td>John</td>
        <td>Doe</td>
        <td>A.</td>
        <td>Science</td>
        <td>johndoe@example.com</td>
    </tr>
    
</tbody>
</table>
</div>
</div>

<!-- SHOW SECRETARY -->

<!-- SHOW ADMIN -->
<div class="big-card">
<h3 class="card__title">Admin Users</h3>
<div class="table-responsive">
<table class="table">
<thead>
    <tr>
        <th>School Id</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Middle Name</th>
        <th>Email</th>
    </tr>
</thead>
<tbody>
    <!-- Example row, you can add more rows here -->
    <tr>
        <td>001</td>
        <td>John</td>
        <td>Doe</td>
        <td>A.</td>
        <td>johndoe@example.com</td>
    </tr>
    
</tbody>
</table>
</div>
</div>

<!-- SHOW SECRETARY -->