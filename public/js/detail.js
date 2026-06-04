const quantityWrapper = document.querySelector('.quantityInput');
const quantityInput = quantityWrapper.querySelector('input');
const minusButton = quantityWrapper.querySelector('button:first-child');
const plusButton = quantityWrapper.querySelector('button:last-child');

const minValue = parseInt(quantityInput.min, 10) || 1;
const maxValue = parseInt(quantityInput.max, 10) || 99;

const clampValue = (value) => {
    if (Number.isNaN(value) || value < minValue) {
        return minValue;
    }

    if (value > maxValue) {
        return maxValue;
    }

    return value;
};

minusButton.addEventListener('click', () => {
    const currentValue = parseInt(quantityInput.value, 10) || minValue;
    quantityInput.value = clampValue(currentValue - 1);
});

plusButton.addEventListener('click', () => {
    const currentValue = parseInt(quantityInput.value, 10) || minValue;
    quantityInput.value = clampValue(currentValue + 1);
});

quantityInput.addEventListener('input', function () {
    this.value = clampValue(parseInt(this.value, 10));
});