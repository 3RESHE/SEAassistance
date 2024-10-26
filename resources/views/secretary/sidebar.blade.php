<!--========== HEADER ==========-->
<header class="header">
    <div class="header__container">
        <a href="{{ url('/') }}" class="header__logo">SEAassist: Secretary</a>
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
            <a href="{{ url('/') }}" class="nav__link nav__logo" style="display: flex; justify-content: center; align-items: center;">
                <img src="{{ asset('SEAassistLogo/sea1.png') }}" style="width: 75px; height: auto;">
            </a>

            <div class="nav__list">
                <div class="nav__items">
                    <h3 class="nav__subtitle">Profile</h3>

                    <a href="#" class="nav__link active">
                        <i class='bx bx-home nav__icon'></i>
                        <span class="nav__name">Home</span>
                    </a>
                </div>

                <div class="nav__items">
                    <h3 class="nav__subtitle">Menu</h3>

                    <div class="nav__dropdown" id="subjects-dropdown">
                        <a href="#" class="nav__link" id="subjects-link">
                            <i class='bx bx-user-plus nav__icon'></i>
                            <span class="nav__name">Subjects</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="{{ url('/view_departments_sec') }}" class="nav__dropdown-item">Add Subject</a>
                                <a href="{{ url('/view_department_sub') }}" class="nav__dropdown-item">View Subjects</a>
                                <a href="{{ url('/manage_subjects_dep') }}" class="nav__dropdown-item">Manage Subjects</a>
                            </div>
                        </div>
                    </div>

                    <div class="nav__dropdown" id="advising-dropdown">
                        <a href="#" class="nav__link" id="advising-link">
                            <i class='bx bxs-user-detail nav__icon'></i>
                            <span class="nav__name">Advising</span>
                            <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                        </a>
                        <div class="nav__dropdown-collapse">
                            <div class="nav__dropdown-content">
                                <a href="{{ url('manage_advising_request') }}" class="nav__dropdown-item">Manage Advising Request</a>
                                <a href="{{ url('advising_records') }}" class="nav__dropdown-item">Advising History</a>
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
            });
        });
    });
</script>

<style>
    /* Base styles for dropdown */
    .nav__dropdown-collapse {
        display: none; /* Hidden by default */
    }

    /* Styles for showing dropdown on hover for desktop */
    @media (min-width: 768px) {
        .nav__dropdown:hover .nav__dropdown-collapse {
            display: block; /* Show dropdown on hover */
        }
    }

    /* Styles for mobile to show dropdown when clicked */
    @media (max-width: 767px) {
        .nav__dropdown.active .nav__dropdown-collapse {
            display: block; /* Show dropdown when active */
        }
    }
</style>
