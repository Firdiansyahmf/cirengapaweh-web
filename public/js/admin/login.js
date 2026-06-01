document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');

    // Toggle password visibility
    togglePasswordBtn.addEventListener('click', function (e) {
        e.preventDefault();

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePasswordBtn.innerHTML =
                '<span class="material-symbols-outlined">visibility_off</span>';
        } else {
            passwordInput.type = 'password';
            togglePasswordBtn.innerHTML =
                '<span class="material-symbols-outlined">visibility</span>';
        }
    });
});
