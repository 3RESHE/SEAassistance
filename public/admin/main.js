


/*==================== SHOW NAVBAR ====================*/
const showMenu = (headerToggle, navbarId) => {
    const toggleBtn = document.getElementById(headerToggle),
        nav = document.getElementById(navbarId);

    // Validate that variables exist
    if (toggleBtn && nav) {
        toggleBtn.addEventListener('click', () => {
            // We add the show-menu class to the div tag with the nav__menu class
            nav.classList.toggle('show-menu');
            // change icon
            toggleBtn.classList.toggle('bx-x');
        });
    }
}
showMenu('header-toggle', 'navbar');

/*==================== LINK ACTIVE ====================*/
const linkColor = document.querySelectorAll('.nav__link');

function colorLink() {
    linkColor.forEach(l => l.classList.remove('active'));
    this.classList.add('active');
}

linkColor.forEach(l => l.addEventListener('click', colorLink));

/*==================== TOGGLE MODE ====================*/
const toggleMode = (modeToggleId) => {
    const modeToggleBtn = document.getElementById(modeToggleId);

    // Check if the modeToggleBtn exists
    if (modeToggleBtn) {
        // Set initial mode from localStorage
        if (localStorage.getItem('mode') === 'dark') {
            document.body.classList.add('dark-mode');
            modeToggleBtn.classList.remove('bx-sun');
            modeToggleBtn.classList.add('bx-moon');
        } else {
            document.body.classList.add('light-mode');
        }

        modeToggleBtn.addEventListener('click', () => {
            if (document.body.classList.contains('dark-mode')) {
                // Switch to light mode
                document.body.classList.remove('dark-mode');
                document.body.classList.add('light-mode');
                modeToggleBtn.classList.remove('bx-moon');
                modeToggleBtn.classList.add('bx-sun');
                localStorage.setItem('mode', 'light');
            } else {
                // Switch to dark mode
                document.body.classList.remove('light-mode');
                document.body.classList.add('dark-mode');
                modeToggleBtn.classList.remove('bx-sun');
                modeToggleBtn.classList.add('bx-moon');
                localStorage.setItem('mode', 'dark');
            }
        });
    }
}
toggleMode('mode-toggle');





