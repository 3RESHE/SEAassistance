<!--========== HEADER ==========-->
<header class="header">
    <div class="header__container">
        <a href="{{url('/')}}" class="header__logo">SEAassist: Evaluator</a>
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
                    <a href="{{url('/')}}" class="nav__link active">
                        <i class='bx bx-home nav__icon'></i>
                        <span class="nav__name">Home</span>
                    </a>
                    {{--    
                    <div class="nav__dropdown" id="profile-dropdown">
                        <a href="#" class="nav__link" id="profile-link">
                            <i class='bx bx-user nav__icon'></i>
                            <span class="nav__name">Profile</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="#" class="nav__dropdown-item">Passwords</a>
                                <a href="#" class="nav__dropdown-item">Mail</a>
                                <a href="#" class="nav__dropdown-item">Accounts</a>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <div class="nav__items">
                    <h3 class="nav__subtitle">Menu</h3>

                    {{-- 
                    <div class="nav__dropdown" id="notifications-dropdown">
                        <a href="#" class="nav__link" id="notifications-link">
                            <i class='bx bx-bell nav__icon'></i>
                            <span class="nav__name">Notifications</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="#" class="nav__dropdown-item">Blocked</a>
                                <a href="#" class="nav__dropdown-item">Silenced</a>
                                <a href="#" class="nav__dropdown-item">Publish</a>
                                <a href="#" class="nav__dropdown-item">Program</a>
                            </div>
                        </div>
                    </div> --}}

                    <div class="nav__dropdown" id="students-dropdown">
                        <a href="#" class="nav__link" id="students-link">
                            <i class='bx bxs-user-detail nav__icon'></i>
                            <span class="nav__name">Students</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="{{url('manage_students_dep')}}" class="nav__dropdown-item">Manage Students</a>
                            </div>
                        </div>
                    </div>

                    <div class="nav__dropdown" id="curriculum-dropdown">
                        <a href="#" class="nav__link" id="curriculum-link">
                            <i class='bx bxs-graduation nav__icon'></i>
                            <span class="nav__name">Curriculum</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="{{url('add_curriculum_dep')}}" class="nav__dropdown-item">Add</a>
                                <a href="{{url('view_curriculum_dep')}}" class="nav__dropdown-item">View</a>
                            </div>
                        </div>
                    </div>

                    <div class="nav__dropdown" id="enrollment-dropdown">
                        <a href="#" class="nav__link" id="enrollment-link">
                            <i class='bx bxs-user-check nav__icon'></i>
                            <span class="nav__name">Pending</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="{{url('view_pending_enrollees')}}" class="nav__dropdown-item">View Pending Enrollees</a>
                                <a href="{{url('view_enrolled_students')}}" class="nav__dropdown-item">View Enrolled Students</a>
                            </div>
                        </div>
                    </div>

                    <div class="nav__dropdown">
                        <a href="{{ route('tor.view') }}" class="nav__link">
                            <i class='bx bx-bookmark nav__icon'></i>
                            <span class="nav__name">Request</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" class="nav__link nav__logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
        // Select all dropdowns and links
        const dropdownLinks = document.querySelectorAll('.nav__link[id$="-link"]');

        dropdownLinks.forEach(link => {
            // For desktop: hover effect
            link.addEventListener('mouseenter', function() {
                const dropdownId = this.id.replace('-link', '-dropdown');
                const dropdown = document.getElementById(dropdownId);
                dropdown.classList.add('active');
            });

            link.addEventListener('mouseleave', function() {
                const dropdownId = this.id.replace('-link', '-dropdown');
                const dropdown = document.getElementById(dropdownId);
                dropdown.classList.remove('active');
            });

            // For touch devices: click event
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const dropdownId = this.id.replace('-link', '-dropdown');
                const dropdown = document.getElementById(dropdownId);
                dropdown.classList.toggle('active');
            });
        });
    });
</script>
