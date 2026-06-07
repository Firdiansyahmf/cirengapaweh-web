function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Berhasil disalin ke clipboard!');
    }, function(err) {
        console.error('Gagal menyalin text: ', err);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    /* timer */
    const config = window.paymentConfig || {};
    let timeRemaining = Math.floor(config.timeRemaining || 0);
    const countdownElement = document.getElementById('countdownTimer');
    const timerContainer = document.getElementById('timerContainer');

    if (countdownElement && timeRemaining > 0) {
        const timerInterval = setInterval(function() {
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                countdownElement.innerText = "EXPIRED";
                if (timerContainer) {
                    timerContainer.innerHTML = '<b class="textDanger">Waktu pembayaran telah habis</b>';
                }
                return;
            }

            timeRemaining--;
            const hours = Math.floor(timeRemaining / 3600);
            const minutes = Math.floor((timeRemaining % 3600) / 60);
            const seconds = Math.floor(timeRemaining % 60);

            const formattedTime =
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');

            countdownElement.innerText = formattedTime;
        }, 1000);
    }

    window.checkPaymentStatus = function() {
        if (!config.statusUrl) return;
        fetch(config.statusUrl)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'paid' || data.payment_status === 'settlement') {
                    alert('Pembayaran sukses terkonfirmasi!');
                    if (config.successUrl) {
                        const form = document.getElementById('paymentSuccessForm');
                        if (form) {
                            form.submit();
                        } else {
                            window.location.href = config.successUrl;
                        }
                    }
                } else if (data.status === 'cancelled' || data.payment_status === 'expire' || data.payment_status === 'cancel') {
                    alert('Transaksi ini telah dibatalkan atau kedaluwarsa.');
                    window.location.reload();
                } else {
                    alert('Menunggu pembayaran Anda. Silahkan selesaikan pembayaran terlebih dahulu.');
                }
            })
            .catch(error => {
                console.error('Error checking status:', error);
            });
    };

    /* cek setiap 5 detik jika pembayaran sudah dilakukan atau belum */
    if (config.isPending && config.statusUrl) {
        const autoPollInterval = setInterval(function() {
            fetch(config.statusUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'paid' || data.payment_status === 'settlement') {
                        clearInterval(autoPollInterval);
                        if (config.successUrl) {
                            const form = document.getElementById('paymentSuccessForm');
                            if (form) {
                                form.submit();
                            } else {
                                window.location.href = config.successUrl;
                            }
                        }
                    }
                })
                .catch(error => console.error('Error in auto-poll:', error));
        }, 5000);
    }
});
