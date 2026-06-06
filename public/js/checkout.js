document.addEventListener("DOMContentLoaded", function () {
    const payBtn = document.getElementById("payNow");
    const paymentInputs = document.querySelectorAll(
        'input[name="payment_method"]',
    );

    const refreshButtonState = () => {
        const selected = document.querySelector(
            'input[name="payment_method"]:checked',
        );
        if (payBtn) {
            payBtn.textContent = selected
                ? `Bayar dengan ${selected.value.toUpperCase()}`
                : "Bayar Sekarang";
        }
    };

    paymentInputs.forEach((input) => {
        input.addEventListener("change", refreshButtonState);
    });

    refreshButtonState();

    if (payBtn) {
        payBtn.addEventListener("click", function (e) {
            const method = document.querySelector(
                'input[name="payment_method"]:checked',
            );
            const selected = method ? method.value : "none";

            const form = document.getElementById("checkoutForm");
            if (form && form.checkValidity()) {
                payBtn.disabled = true;
                payBtn.innerText = "Memproses...";
                payBtn.style.opacity = "0.7";
                form.submit();
            }
        });
    }

    const whatsappInput = document.getElementById("inputWa");

    if (whatsappInput) {
        whatsappInput.addEventListener("input", function () {
            this.value = this.value.replace(/[^0-9]/g, "");
        });
    }

    const addressInput = document.getElementById("inputAddress");
    const addressCounter = document.getElementById("addressCount");

    if (addressInput && addressCounter) {
        addressInput.addEventListener("input", function () {
            addressCounter.innerText = this.value.length + "/200";
        });
    }

    const postalInput = document.getElementById("inputPostal");

    if (postalInput) {
        postalInput.addEventListener("input", function () {
            this.value = this.value.replace(/[^0-9]/g, "");
        });
    }

    const nameInput = document.getElementById("inputName");
    const nameCounter = document.getElementById("nameCount");

    if (nameInput && nameCounter) {
        nameInput.addEventListener("input", function () {
            nameCounter.innerText = this.value.length + "/50";
        });
    }
});
