@include('chat.chat_support')

<!--========== HEADER ==========-->
<header class="header">
    <div class="header__container">
        <a href="{{url('/')}}" class="header__logo">SEAassist: Student</a>
        <div class="header__toggle-mode">
            <a href="#" class="nav__link" id="notificationDropdownToggle">
                <i class='bx bx-bell nav__icon'></i>
            </a>
        
            <div class="nav__dropdown-collapse" id="notificationDropdown" style="display: none;">
                <div class="nav__dropdown-content">
                    @if($notifications->isEmpty())
                        <p>No notifications.</p>
                    @else
                        @foreach ($notifications as $notification)
                            <div class="notification">
                                <p>{{ $notification->data['message'] }}</p>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <i class='' id="mode-toggle"></i>
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
                            <i class='bx bx-home nav__icon' ></i>
                            <span class="nav__name">Home</span>
                        </a>


                        

                    </div>
                    

                    <div class="nav__items">
                        <h3 class="nav__subtitle">Menu</h3>

                        
                        

                        <a href="{{url('view_subjects')}}" class="nav__link">
                            <i class='bx bx-user-plus nav__icon' ></i>
                            <span class="nav__name">Subjects</span>
                        </a>

                        <form action="{{url('advise')}}" method="POST" class="nav__link-form">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="nav__link">
                                <i class='bx bxs-user-detail nav__icon'></i>
                                <span class="nav__name">Advising</span>
                            </a>
                        </form>


                        
                        



                    

                        {{-- <a href="#" class="nav__link">
                            <i class='bx bx-compass nav__icon' ></i>
                            <span class="nav__name">Explore</span>
                        </a>
                        <a href="#" class="nav__link">
                            <i class='bx bx-bookmark nav__icon' ></i>
                            <span class="nav__name">Saved</span>
                        </a> --}}
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
        document.getElementById('notificationDropdownToggle').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default anchor action
            const dropdown = document.getElementById('notificationDropdown');
            
            // Toggle the display property
            dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
        });
    </script>
    

    <style>
        .nav__dropdown-collapse {
            position: absolute; /* Positioning for dropdown */
            background-color: #fff; /* Background color */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
            z-index: 1000; /* Ensure it appears above other elements */
            width: 200px; /* Default width of the dropdown */
            max-height: 300px; /* Set max height for the dropdown */
            overflow-y: auto; /* Enable vertical scroll when content overflows */
            overflow-x: hidden; /* Hide horizontal scroll */
            right: 0; /* Ensure it's aligned to the right side */
        }
    
        /* Responsive styles for smaller screens */
        @media (max-width: 768px) {
            .nav__dropdown-collapse {
                width: 90%; /* Make the dropdown take up 90% of the viewport width */
                left: 5%; /* Center the dropdown on small screens */
                right: auto; /* Override right alignment */
            }
    
            .notification {
                padding: 8px; /* Adjust padding for smaller screens */
                font-size: 14px; /* Adjust font size */
            }
        }
    
        .nav__dropdown-content {
            max-height: 300px; /* Ensure the content stays within the dropdown's height */
            overflow-y: auto; /* Enable scrolling for the notifications */
            overflow-x: hidden; /* Hide horizontal scroll */
        }
    
        .notification {
            padding: 10px; /* Space around each notification */
            border-bottom: 1px solid #eee; /* Divider between notifications */
            cursor: pointer; /* Change cursor on hover */
            transition: background-color 0.3s; /* Smooth background transition */
        }
    
        .notification:hover {
            background-color: #f5f5f5; /* Change background on hover */
        }
    
        .notification:last-child {
            border-bottom: none; /* Remove border for the last notification */
        }
    </style>