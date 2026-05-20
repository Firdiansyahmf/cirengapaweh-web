document.querySelectorAll('.tabButton').forEach(button => {
    button.addEventListener('click', function() {
        const tabId = this.getAttribute('data-tab');
        
        document.querySelectorAll('.tabButton').forEach(btn => btn.classList.remove('tabButton-active'));
        document.querySelectorAll('.tabContent').forEach(content => content.classList.remove('tabContent-active'));
        
        this.classList.add('tabButton-active');
        document.getElementById(tabId).classList.add('tabContent-active');
    });
});
