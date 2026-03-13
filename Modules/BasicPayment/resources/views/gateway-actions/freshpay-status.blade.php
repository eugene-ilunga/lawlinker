<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Statut du paiement FreshPay</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($setting->favicon) }}">
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}?v={{ $setting?->version }}">
    <style>
        .freshpay-status-card { max-width: 640px; }
        .freshpay-badge { font-size: 0.8rem; letter-spacing: 0.04em; text-transform: uppercase; }
    </style>
</head>

<body>
    <section class="vh-100 d-flex align-items-center justify-content-center">
        <div class="container d-flex justify-content-center">
            <div class="card shadow-sm freshpay-status-card w-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Paiement FreshPay</h5>
                    <span id="status-badge" class="badge bg-warning text-dark freshpay-badge">{{ strtoupper($transaction->status) }}</span>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">Votre paiement est en cours de traitement</h4>
                    <p class="mb-2">Référence : <strong>{{ $transaction->reference }}</strong></p>
                    <p class="mb-2">Commande : <strong>#{{ $transaction->order_public_id }}</strong></p>
                    <p class="mb-3">Montant : <strong>{{ number_format((float) $transaction->amount, 2) }} {{ $transaction->currency }}</strong></p>

                    <div class="alert alert-info mb-3" id="status-message">
                        {{ $transaction->message ?: 'Le paiement est en cours de traitement par FreshPay.' }}
                    </div>

                    <div class="small text-muted">
                        Veuillez confirmer le paiement dans votre popup mobile money. Cette page se mettra a jour automatiquement des que FreshPay nous enverra le statut final.
                    </div>

                    <div class="mt-4">
                        <a href="{{ $retryUrl }}" class="btn btn-outline-secondary">Changer de moyen de paiement</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        (function() {
            const pollUrl = @json($pollUrl);
            const successUrl = @json($successUrl);
            const retryUrl = @json($retryUrl);
            const badge = document.getElementById('status-badge');
            const messageBox = document.getElementById('status-message');

            const badgeClassMap = {
                processing: 'bg-warning text-dark',
                success: 'bg-success',
                failed: 'bg-danger',
                cancelled: 'bg-secondary',
                error: 'bg-danger'
            };

            const paintStatus = (status, message) => {
                badge.className = 'badge freshpay-badge ' + (badgeClassMap[status] || 'bg-warning text-dark');
                badge.textContent = String(status || 'processing').toUpperCase();
                messageBox.className = 'alert ' + (status === 'success' ? 'alert-success' : ['failed', 'cancelled', 'error'].includes(status) ? 'alert-danger' : 'alert-info') + ' mb-3';
                messageBox.textContent = message || '';
            };

            const poll = async () => {
                try {
                    const response = await fetch(pollUrl, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin'
                    });
                    const data = await response.json();

                    paintStatus(data.status, data.message);

                    if (data.status === 'success') {
                        window.location.href = successUrl;
                        return;
                    }

                    if (['failed', 'cancelled', 'error'].includes(data.status)) {
                        setTimeout(() => { window.location.href = retryUrl; }, 1800);
                        return;
                    }
                } catch (error) {
                    messageBox.className = 'alert alert-warning mb-3';
                    messageBox.textContent = 'Impossible d\'actualiser le statut du paiement pour le moment. Nouvelle tentative...';
                }

                window.setTimeout(poll, 3000);
            };

            window.setTimeout(poll, 1500);
        })();
    </script>
</body>

</html>
