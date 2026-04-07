document.addEventListener('DOMContentLoaded', function () {
  const loginForm = document.getElementById('loginForm');
  const passwordInput = document.getElementById('password');
  const togglePasswordBtn = document.getElementById('togglePasswordBtn');
  const emailInput = document.getElementById('email');

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

  loginForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();

    if (!email) {
      alert('Email harus diisi!');
      emailInput.focus();
      return;
    }

    if (!password) {
      alert('Password harus diisi!');
      passwordInput.focus();
      return;
    }

    window.location.href = './dashboard.html';
  });
});
