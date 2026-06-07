<div class="paymentRow">
    <label for="{{ $id }}" class="paymentOpt flexRow">
        <span class="paymentLeft">
            <img class="paymentImg" src="{{ asset('assets/img/logo/' . $id . '-logo.svg') }}"></img>    
            <span class="bodyMain charcoalGrey"><b>{{ $label }}</b></span>
        </span>
        <input type="radio" id="{{ $id }}" name="payment_method" value="{{ $id }}" {{ isset($checked) && $checked ? 'checked' : '' }}>
    </label>
</div>
