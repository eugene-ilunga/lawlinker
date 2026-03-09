<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Consultation') }}</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        background: #fff;
        padding: 35px;
        font-size: 16px;
        line-height: 1.5;
    }

    .prescription .top .prescription-logo img {
        height: 50px;
        margin-bottom: 1rem;
    }

    .prescription table {
        width: 100%;
    }

    .prescription .top .right {
        float: right;
        text-align: end;
    }

    .prescription .top .right h2 {
        font-size: 24px;
        font-weight: 700;
    }

    .prescription .client-info {
        border-top: 2px solid #afafaf;
        border-bottom: 2px solid #afafaf;
        margin-top: 20px;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .prescription .main-section {
        padding: 30px 0;
        border-bottom: 2px solid #afafaf;
    }

    .prescription .main-section .problem {
        margin-bottom: 40px;
    }

    .prescription .main-section .problem h2 {
        font-size: 22px;
        font-weight: 700;
    }

    .prescription .main-section .test h2 {
        font-size: 22px;
        font-weight: 700;
    }

    .prescription .main-section .test p,
    .prescription .main-section .problem p {
        padding-left: 20px;
    }

    .prescription .footer {
        margin-top: 80px;
    }

    .prescription .footer h2 {
        font-size: 18px;
        font-weight: 700;
    }

    .date-area {
        float: right;
    }

    p {
        font-size: 16px;
        line-height: 1.6;
    }
</style>


<body>
    <div class="prescription">
        <div class="top">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div class="prescription-logo"><img src="{{ public_path($setting?->logo) }}"
                                    alt="{{ $setting?->app_name }}"></div>
                            <div class="address">
                                {{ $contactInfo?->address }}
                            </div>
                            <div class="phone">
                                {{ $setting?->prescription_phone }}
                            </div>
                            <div class="email">
                                {{ $setting?->prescription_email }}
                            </div>
                        </td>
                        <td>
                            <div style="text-align: right;">
                                <h2>{{ $appointment?->lawyer?->name }}</h2>
                                <p>
                                    {{ $appointment?->lawyer?->designations }}
                                </p>
                            </div>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
        <div class="client-info">
            <table>
                <tbody>
                    <tr>
                        <td>{{ __('Client Name') }}: {{ $appointment?->user?->name }}</td>
                        <td>{{ __('Age') }}: {{ $appointment?->user?->details?->age }}
                            {{ __('Years') }}</td>
                        <td class="date-area" style="text-align: right;">{{ __('Date') }}:
                            {{ formattedDate($appointment?->date) }}</td>
                    </tr>
                </tbody>

            </table>
        </div>
        <div class="main-section">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div class="problem">
                                <h2>{{ __('Subject') }}: {{ $appointment?->subject }}</h2>
                                <p>
                                    {!! clean($appointment?->description) !!}
                                </p>
                            </div>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
        <div class="footer">
            <h2>{{ __('Signature') }}</h2>
            <p>
                {{ $appointment?->lawyer->name }}<br> {{ $appointment?->lawyer?->designations }}
            </p>
        </div>
    </div>
</body>

</html>
