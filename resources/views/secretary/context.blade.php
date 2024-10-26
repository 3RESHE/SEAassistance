@include('secretary.css')


@include('secretary.sidebar')

    <!--========== CONTENTS ==========-->
    <br>
    <main>
        <section>


            @include('secretary.dashboard')
  

                
                <!-- Upload Subject -->

                <div class="card">
                    <h3 class="card__title">Upload Subjects for : "COURSE NAME"</h3>
                    <form action="/your-upload-url" method="POST" enctype="multipart/form-data" class="form">
                        <input type="hidden" name="course" value="your-course-value">
                        
                        <div class="form__group">
                            <label for="excel_file" class="form__label">Upload Excel File</label>
                            <input type="file" name="excel_file" id="excel_file" class="form__input" accept=".xls,.xlsx" required>
                        </div>
                
                        <button type="submit" class="form__button">Upload</button>
                    </form>
                </div>
                      <!-- Upload Subject -->
        
                    
                    <!-- DEPARTMENTS -->
                      <div class="big-card">
                        <h1 style="text-align: center;">DEPARTMENTS</h1>
                        <div class="department-cards">
                            
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                
                            
                
                            
                
                            <!-- Add more department cards as needed -->
                        </div>
                    </div>

                    <!-- DEPARTMENTS -->

                    <!-- Courses -->
                    <div class="big-card">
                        <h1 style="text-align: center;">Courses</h1>
                        <div class="department-cards">
                            
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>
                                        
                            <a href="#" class="department-card-link">
                                <div class="department-card">
                                    <img src="assets/img/coi.png" alt="Department Logo" class="department-logo">
                                    <span class="department-name">Department 1</span>
                                </div>
                            </a>

                            <!-- Add more department cards as needed -->
                        </div>
                    </div>







                    <!-- Courses -->

                            
                            <div class="curriculum-container">
                                <h2 class="curriculum-title">Manage Pre Requisite and Co Requisite Subjects</h2>
                                <form action="#" method="POST" class="curriculum-form">
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
                                                <!-- Sample Row 1 -->
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
                                                    <td><input type="text" name="descriptive_title[]" class="curriculum-form__input" value="Introduction to Programming" readonly></td>
                                                    <td><input type="text" name="course_code[]" class="curriculum-form__input" value="IT101" readonly></td>
                                                    <td><input type="text" name="units[]" class="curriculum-form__input" value="3" readonly></td>
                                                    <td><button  type="button" class="curriculum-form__btn" onclick="openModal('preReqModal', this)">Select Pre Requisites</button></td>
                                                    <td><button type="button" class="curriculum-form__btn" onclick="openModal('coReqModal', this)">Select Co Requisites</button></td>
                                                </tr>
                                                <!-- Sample Row 2 -->
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
                                                    <td><input type="text" name="descriptive_title[]" class="curriculum-form__input" value="Fundamentals of Computing" readonly></td>
                                                    <td><input type="text" name="course_code[]" class="curriculum-form__input" value="IT102" readonly></td>
                                                    <td><input type="text" name="units[]" class="curriculum-form__input" value="3" readonly></td>
                                                    <td><button type="button" class="curriculum-form__btn" onclick="openModal('preReqModal', this)">Select Pre Requisites</button></td>
                                                    <td><button type="button" class="curriculum-form__btn" onclick="openModal('coReqModal', this)">Select Co Requisites</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="submit" class="curriculum-form__submit">Generate New Curriculum</button>
                                </form>
                            </div>

                            <!-- Pre Requisite Subjects Modal -->
                            <div id="preReqModal" class="modal">
                                <div class="modal-content">
                                    <span class="close-btn" onclick="closeModal('preReqModal')">&times;</span>
                                    <h3>Select Pre Requisite Subjects</h3>
                                    <input type="text" id="preReqSearch" class="modal-search" placeholder="Search subjects...">
                                    <div id="preReqList" class="modal-list">
                                        <!-- Example subjects -->
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT101"> Introduction to Programming</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT102"> Fundamentals of Computing</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT103"> Data Structures</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT104"> Algorithms</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT105"> Web Development</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT106"> Database Systems</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT107"> Software Engineering</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT108"> Computer Networks</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT109"> Operating Systems</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT110"> Cyber Security</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT111"> Artificial Intelligence</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT112"> Machine Learning</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT113"> Mobile Application Development</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT114"> Cloud Computing</label>
                                        <label><input type="checkbox" name="pre_requisite_subjects" value="IT115"> Internet of Things</label>
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
                                        <!-- Example subjects -->
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT101"> Introduction to Programming</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT102"> Fundamentals of Computing</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT103"> Data Structures</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT104"> Algorithms</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT105"> Web Development</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT106"> Database Systems</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT107"> Software Engineering</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT108"> Computer Networks</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT109"> Operating Systems</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT110"> Cyber Security</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT111"> Artificial Intelligence</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT112"> Machine Learning</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT113"> Mobile Application Development</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT114"> Cloud Computing</label>
                                        <label><input type="checkbox" name="co_requisite_subjects" value="IT115"> Internet of Things</label>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="modal-save-btn" onclick="saveSelection('coReqModal')">Save</button>
                                    </div>
                                </div>
                            </div>

                            <script>
                                        let currentRow; // Store the current row being edited

                                        function openModal(modalId, button) {
                                            currentRow = button.closest('tr'); // Find the closest row
                                            document.getElementById(modalId).style.display = 'block'; // Open the modal

                                            // Clear previous selections and update modal
                                            resetModal(modalId);
                                            updateModalSelections(modalId);
                                        }

                                        function closeModal(modalId) {
                                            document.getElementById(modalId).style.display = 'none'; // Close the modal
                                        }

                                        function saveSelection(modalId) {
                                            const selectedCheckboxes = Array.from(document.querySelectorAll(`#${modalId} input[type="checkbox"]:checked`));
                                            const selectedSubjects = selectedCheckboxes.map(cb => cb.nextSibling.textContent.trim()).join(', ');

                                            if (modalId === 'preReqModal') {
                                                currentRow.cells[5].querySelector('button').textContent = selectedSubjects || 'Select Pre Requisites';
                                            } else if (modalId === 'coReqModal') {
                                                currentRow.cells[6].querySelector('button').textContent = selectedSubjects || 'Select Co Requisites';
                                            }

                                            closeModal(modalId);
                                        }

                                        function filterSubjects(inputId, listId) {
                                            const input = document.getElementById(inputId);
                                            const filter = input.value.toLowerCase();
                                            const list = document.getElementById(listId);
                                            const labels = list.getElementsByTagName('label');

                                            Array.from(labels).forEach(label => {
                                                const text = label.textContent || label.innerText;
                                                label.style.display = text.toLowerCase().indexOf(filter) > -1 ? '' : 'none';
                                            });
                                        }

                                        function updateModalSelections(modalId) {
                                            const selectedSubjects = currentRow.cells[modalId === 'preReqModal' ? 5 : 6].querySelector('button').textContent.trim();

                                            if (selectedSubjects && selectedSubjects !== 'Select Pre Requisites' && selectedSubjects !== 'Select Co Requisites') {
                                                const selectedItems = selectedSubjects.split(', ').map(subject => subject.trim());
                                                document.querySelectorAll(`#${modalId} input[type="checkbox"]`).forEach(cb => {
                                                    cb.checked = selectedItems.includes(cb.nextSibling.textContent.trim());
                                                });
                                            }
                                        }

                                        function resetModal(modalId) {
                                            document.querySelectorAll(`#${modalId} input[type="checkbox"]`).forEach(cb => {
                                                cb.checked = false; // Uncheck all checkboxes
                                            });
                                        }

                                        document.getElementById('preReqSearch').addEventListener('keyup', function() {
                                            filterSubjects('preReqSearch', 'preReqList');
                                        });

                                        document.getElementById('coReqSearch').addEventListener('keyup', function() {
                                            filterSubjects('coReqSearch', 'coReqList');
                                        });

                            </script>


                    <!-- Courses -->
                     


</section>
</main>




    
        <!--========== MAIN JS ==========-->
        <script src="secretary/main.js"></script>

    </body>
    </html>
    