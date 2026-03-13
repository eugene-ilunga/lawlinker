@php
    $method = $paymentMethod;
    $operators = ['airtel', 'orange', 'mpesa', 'africell'];

    if (isset($token)) {
        $actionUrl = route('payment-api.freshpay-webview', [
            'bearer_token' => $token,
            'order_id' => $order_id,
        ]);
    } else {
        $actionUrl = route('pay-via-freshpay');
    }
@endphp
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Paiement FreshPay</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($setting->favicon) }}">
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}?v={{ $setting?->version }}">
</head>

<body>
    <section class="vh-100 d-flex align-items-center justify-content-center">
        <div class="container d-flex justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Paiement FreshPay</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ $actionUrl }}" method="post">
                            @csrf

                            <div class="my-1 form-group">
                                <label for="customer_number">Numéro de téléphone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer_number" name="customer_number"
                                    placeholder="0900000000/900000000" required>
                            </div>

                            <div class="my-1 form-group">
                                <label for="method">Opérateur <span class="text-danger">*</span></label>
                                <select id="method" name="method" class="form-control" required>
                                    <option value="">Sélectionnez un opérateur</option>
                                    @foreach ($operators as $operator)
                                        <option value="{{ $operator }}">{{ strtoupper($operator) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="mt-2 btn btn-primary">Payer maintenant</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
