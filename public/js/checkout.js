document.addEventListener('DOMContentLoaded', function () {
    const payBtn = document.getElementById('payNow');
    const paymentInputs = document.querySelectorAll('input[name="payment_method"]');

    const refreshButtonState = () => {
        const selected = document.querySelector('input[name="payment_method"]:checked');
        if (payBtn) {
            payBtn.textContent = selected ? `Bayar dengan ${selected.value.toUpperCase()}` : 'Bayar Sekarang';
        }
    };

    paymentInputs.forEach((input) => {
        input.addEventListener('change', refreshButtonState);
    });

    refreshButtonState();

    if (payBtn) {
        payBtn.addEventListener('click', function (e) {
            e.preventDefault();
            
            const method = document.querySelector('input[name="payment_method"]:checked');
            const selected = method ? method.value : 'none';
            alert('Memproses pembayaran via: ' + selected + '\nTeruskan ke integrasi pembayaran.');
        });
    }
});
