@if (Module::isEnabled('Currency') &&
        Route::has('set-currency') &&
        allCurrencies()?->where('status', 'active')->count() > 1)
    <li class="set-currency-header dropdown border rounded-2 mx-2"><a href="javascript:;" data-bs-toggle="dropdown"
            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <div class="d-sm-none d-md-inline-block">
                {{ allCurrencies()?->firstWhere('currency_code', getSessionCurrency())?->currency_name ?? __('Select Currency') }}
            </div>
        </a>
        <div class="dropdown-menu py-0 dropdown-menu-left">
            @forelse (allCurrencies()?->where('status', 'active') as $currency)
                <a href="{{ getSessionCurrency() == $currency->currency_code ? 'javascript:;' : route('set-currency', ['currency' => $currency->currency_code]) }}"
                    class="dropdown-item has-icon {{ getSessionCurrency() == $currency->currency_code ? 'bg-light' : '' }}">
                    {{ $currency->currency_name }}
                </a>
            @empty
                <a href="javascript:;"
                    class="dropdown-item has-icon {{ getSessionCurrency() == 'USD' ? 'bg-light' : '' }}">
                    {{ __('USD') }}
                </a>
            @endforelse
        </div>
    </li>
@endif
