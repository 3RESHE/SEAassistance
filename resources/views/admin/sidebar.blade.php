<!--========== HEADER ==========-->
<header class="header">
    <div class="header__container">
        <a href="{{url('/')}}" class="header__logo">SEAassist: {{$user->name}}</a>
        <div class="header__toggle-mode">
            <i class='bx bx-sun' id="mode-toggle"></i>
        </div>
        <div class="header__toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
    </div>
</header>

<!--========== NAV ==========-->
<div class="nav" id="navbar">
    <nav class="nav__container">
        <div>
            <a href="{{url('/')}}" class="nav__link nav__logo" style="display: flex; justify-content: center; align-items: center;">
                <img src="{{ asset('SEAassistLogo/sea1.png') }}" style="width: 75px; height: auto;">
            </a>

            <div class="nav__list">
                <div class="nav__items">
                    <h3 class="nav__subtitle">Profile</h3>
                    <a href="{{url('/')}}" class="nav__link">
                        <i class='bx bx-home nav__icon'></i>
                        <span class="nav__name">Home</span>
                    </a>

                    <div class="nav__dropdown" id="profile-dropdown">
                        <a href="#" class="nav__link" id="profile-link">
                            <i class='bx bx-message-rounded nav__icon'></i>
                            <span class="nav__name">Manage Chat</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="{{route('chat.manage')}}" class="nav__dropdown-item">Manage Chat Bot Response</a>
                                <a href="{{route('chat.view_list')}}" class="nav__dropdown-item">Reply to Messages</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="nav__items">
                    <h3 class="nav__subtitle">Menu</h3>

                    <div class="nav__dropdown" id="departments-dropdown">
                        <a href="{{url('view_departments')}}" class="nav__link" id="departments-link">
                            <i class='bx bx-buildings nav__icon'></i>
                            <span class="nav__name">Departments</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="{{url('add_department')}}" class="nav__dropdown-item">Add</a>
                                <a href="{{url('view_departments')}}" class="nav__dropdown-item">View</a>
                            </div>
                        </div>
                    </div>

                    <div class="nav__dropdown" id="courses-dropdown">
                        <a href="{{url('view_course_department')}}" class="nav__link" id="courses-link">
                            <i class='bx bxs-graduation nav__icon'></i>
                            <span class="nav__name">Courses</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="{{url('choose_department')}}" class="nav__dropdown-item">Add</a>
                                <a href="{{url('view_course_department')}}" class="nav__dropdown-item">View</a>
                            </div>
                        </div>
                    </div>

                    <div class="nav__dropdown" id="add-user-dropdown">
                        <a href="#" class="nav__link" id="add-user-link">
                            <i class='bx bxs-user-detail nav__icon'></i>
                            <span class="nav__name">Manage Users</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="{{url('add_admin')}}" class="nav__dropdown-item">Admin</a>
                                <a href="{{url('add_secretary')}}" class="nav__dropdown-item">Secretary</a>
                                <a href="{{url('add_evaluator')}}" class="nav__dropdown-item">Evaluator</a>
                                <a href="{{url('add_students')}}" class="nav__dropdown-item">Student</a>
                            </div>
                        </div>
                    </div>

                    <div class="nav__dropdown" id="settings-dropdown">
                        <a href="#" class="nav__link" id="settings-link">
                            <i class='bx bx-cog nav__icon'></i>
                            <span class="nav__name">Advising</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <label for="semester">Select Open Semester:</label>
                                <form action="{{ route('update_semester') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    
                                    <select name="semester" id="semester" class="nav__dropdown-item">
                                        <option value="1st_sem">1st Semester</option>
                                        <option value="2nd_sem">2nd Semester</option>
                                        <option value="summer">Summer</option>
                                    </select>
                              
                            </div>
                            <div class="nav__dropdown-item">

                          
                                    <button type="submit" class="btn-save">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    
                    

                </div>
            </div>
        </div>

        <a href="#" class="nav__link nav__logout"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class='bx bx-log-out nav__icon'></i>
            <span class="nav__name">Log Out</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all dropdown links
        const dropdownLinks = document.querySelectorAll('.nav__link[id$="-link"]');
        dropdownLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const dropdownId = this.id.replace('-link', '-dropdown');
                const dropdown = document.getElementById(dropdownId);
                dropdown.classList.toggle('active');
                // Close other dropdowns
                dropdownLinks.forEach(otherLink => {
                    if (otherLink !== this) {
                        const otherDropdownId = otherLink.id.replace('-link', '-dropdown');
                        const otherDropdown = document.getElementById(otherDropdownId);
                        otherDropdown.classList.remove('active');
                    }
                });
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.nav__dropdown') && !event.target.closest('.nav__link')) {
                dropdownLinks.forEach(link => {
                    const dropdownId = link.id.replace('-link', '-dropdown');
                    const dropdown = document.getElementById(dropdownId);
                    dropdown.classList.remove('active');
                });
            }
        });
    });
</script>

<style>
.btn-save {
    background-color: #007bff; /* Example background color */
    color: white; /* Text color */
    border: none;
    cursor: pointer;
    padding: 8px 12px;
    width: 100%; /* Full width to align with the dropdown */
    text-align: center; /* Center align the button text */
    border-radius: 4px; /* Optional: Rounded corners */
}

.btn-save:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

</style>