@extends('emails.mail-layout')
@section('title', 'RESET PASSWORD EMAIL')
@section('content')
    {!! clean($mail_message) !!}
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
        <tbody>
            <tr>
                <td align="left">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td> <a href="{{ route('admin.password.reset', $from_user->forget_password_token) }}"
                                        target="_blank">{{ __('RESET PASSWORD') }}</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
@endsection