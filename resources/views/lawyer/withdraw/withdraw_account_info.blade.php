<div class="alert alert-primary mb-0" role="alert">
    <h5>{{ __('Withdraw Limit') }} : {{ specific_currency_with_icon('USD', $method?->min_amount) }} - {{ specific_currency_with_icon('USD',$method?->max_amount) }}</h5>
    <h5>{{ __('Withdraw Charge') }} : {{ $method?->withdraw_charge }}%</h5>
    {!! $method?->description !!}
</div>