<div class="payment-row">
    <label for="{{ $id }}" class="payment-option">
        <span class="payment-left">
            <span class="payment-img">img</span>    
            <span class="payment-label">{{ $label }}</span>
        </span>
        <input type="radio" id="{{ $id }}" name="payment_method" value="{{ $id }}" {{ isset($checked) && $checked ? 'checked' : '' }}>
    </label>
</div>
