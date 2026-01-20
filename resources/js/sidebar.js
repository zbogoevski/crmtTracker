// Sidebar functionality extracted from Blade views

// Toggle sidebar submenu
function toggleCRMTSubmenu(element) {
    event.preventDefault();
    const submenu = element.nextElementSibling;
    const chevron = element.querySelector('.fa-chevron-down');

    // Close all other submenus
    document.querySelectorAll('.nav-submenu').forEach(s => {
        if (s !== submenu) {
            s.style.maxHeight = '0';
            const prevChevron = s.previousElementSibling.querySelector('.fa-chevron-down');
            if (prevChevron) prevChevron.style.transform = 'rotate(0deg)';
        }
    });

    // Toggle current submenu
    if (submenu.style.maxHeight === '0px' || submenu.style.maxHeight === '') {
        submenu.style.maxHeight = '500px';
        chevron.style.transform = 'rotate(180deg)';
    } else {
        submenu.style.maxHeight = '0';
        chevron.style.transform = 'rotate(0deg)';
    }
}

// Toggle navbar collapsed state
function toggleCRMTNavbar() {
    const sidebar = document.getElementById('crmt-nav-sidebar');
    const main = document.getElementById('main-content');
    const isCollapsed = sidebar.classList.contains('w-20');

    if (isCollapsed) {
        sidebar.classList.remove('w-20');
        sidebar.classList.add('w-96');
        main.classList.remove('ml-20');
        main.classList.add('ml-96');
        document.querySelectorAll('.nav-text').forEach(el => el.classList.remove('hidden'));
        localStorage.setItem('crmt_nav_collapsed', 'false');
    } else {
        sidebar.classList.remove('w-96');
        sidebar.classList.add('w-20');
        main.classList.remove('ml-96');
        main.classList.add('ml-20');
        document.querySelectorAll('.nav-text').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.nav-submenu').forEach(s => s.style.maxHeight = '0');
        localStorage.setItem('crmt_nav_collapsed', 'true');
    }
}

// Initialize collapsed state
document.addEventListener('DOMContentLoaded', function () {
    const isCollapsed = localStorage.getItem('crmt_nav_collapsed') === 'true';
    if (isCollapsed) {
        const sidebar = document.getElementById('crmt-nav-sidebar');
        const main = document.getElementById('main-content');
        if (sidebar && main) {
            sidebar.classList.remove('w-96');
            sidebar.classList.add('w-20');
            main.classList.remove('ml-96');
            main.classList.add('ml-20');
            document.querySelectorAll('.nav-text').forEach(el => el.classList.add('hidden'));
        }
    }
});
