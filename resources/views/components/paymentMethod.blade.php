<div class="paymentRow">
    <label for="{{ $id }}" class="paymentOpt flexRow">
        <span class="paymentLeft">
            <span class="payment-img">img</span>    
            <span class="bodyMain charcoalGrey"><b>{{ $label }}</b></span>
        </span>
        <input type="radio" id="{{ $id }}" name="payment_method" value="{{ $id }}" {{ isset($checked) && $checked ? 'checked' : '' }}>
    </label>
</div>
