const navItems = document.querySelectorAll('.navItem');

navItems.forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();

        const pageId = this.getAttribute('data-page');

        if (pageId === 'logout') {
            window.location.href = './login.html';
            return;
        }

        navItems.forEach(nav => nav.classList.remove('active'));

        this.classList.add('active');

        const allPages = document.querySelectorAll('.page, .pageActive');
        allPages.forEach(page => {
            page.classList.remove('pageActive');
            page.classList.add('page');
        });

        const selectedPage = document.getElementById(pageId);
        if (selectedPage) {
            selectedPage.classList.remove('page');
            selectedPage.classList.add('pageActive');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const firstNavItem = document.querySelector('.navItem');
    if (firstNavItem) {
        firstNavItem.click();
    }
});
