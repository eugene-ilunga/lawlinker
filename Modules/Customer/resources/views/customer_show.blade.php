@extends('admin.master_layout')
@section('title')
    <title>{{ __('Client Details') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Client Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Client Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card shadow customer_card">
                            <div class="customer_card_img">
                                <img src="{{ !empty($user?->image) ? asset($user->image) : asset($setting->default_avatar) }}"
                                    class="w-100">
                            </div>

                            <div class="customer_card_text">
                                <h4>{{ html_decode($user->name) }}</h4>

                                @if ($user?->details?->phone)
                                    <p class="title">{{ html_decode($user?->details?->phone) }}</p>
                                @endif

                                <p class="title">{{ html_decode($user?->email) }}</p>

                                <p class="title">{{ __('Joined') }} : {{ formattedDateTime($user?->created_at) }}</p>

                                @if ($user->is_banned == 'yes')
                                    <p class="title">{{ __('Banned') }} : <b>{{ __('Yes') }}</b></p>
                                @else
                                    <p class="title">{{ __('Banned') }} : <b>{{ __('No') }}</b></p>
                                @endif
                                @adminCan('client.update')
                                    @if ($user->email_verified_at)
                                        <p class="title">{{ __('Email verified') }} : <b>{{ __('Yes') }}</b> </p>
                                    @else
                                        <p class="title">{{ __('Email verified') }} : <b>{{ __('None') }}</b> </p>

                                        <x-admin.button variant="success" class="mb-3" data-bs-toggle="modal"
                                            data-bs-target="#verifyModal" :text="__('Send Verify Link to Mail')" />
                                    @endif
                                @endadminCan


                                @adminCan('client.bulk.mail')
                                    <x-admin.button class="sendMail mb-3" data-bs-toggle="modal" data-bs-target="#sendMailModal"
                                        :text="__('Send Mail To Client')" />
                                @endadminCan

                                @adminCan('client.update')
                                    @if ($user->is_banned == 'yes')
                                        <x-admin.button variant="warning" class="mb-3" data-bs-toggle="modal"
                                            data-bs-target="#bannedModal" :text="__('Remove to Banned')" />
                                    @else
                                        <x-admin.button variant="warning" class="mb-3" data-bs-toggle="modal"
                                            data-bs-target="#bannedModal" :text="__('Make a Banned')" />
                                    @endif
                                @endadminCan

                                @adminCan('client.delete')
                                    @if (checkAdminHasPermission('client.delete') && $user?->appointments->count() == 0)
                                        <x-admin.button onclick="deleteData('{{ $user->id }}')" variant="danger"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal" :text="__('Delete Account')" />
                                    @endif
                                @endadminCan
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        {{-- profile information card area --}}
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Profile Information')" />
                            </div>
                            <div class="card-body">
                                <form action="{{ checkAdminHasPermission('client.update') ? route('admin.customer-info-update', $user->id) : '' }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="name" name="name" label="{{ __('Name') }}"
                                                placeholder="{{ __('Enter Name') }}"
                                                value="{{ html_decode($user->name) }}" required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="email" name="email" label="{{ __('Email') }}"
                                                placeholder="{{ __('Enter Email') }}"
                                                value="{{ html_decode($user?->email) }}" required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="phone" name="phone" label="{{ __('Phone') }}"
                                                placeholder="{{ __('Enter Phone') }}"
                                                value="{{ html_decode($user?->details?->phone) }}" required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="guardian_name" name="guardian_name"
                                                label="{{ __('Guardian Name') }}"
                                                placeholder="{{ __('Enter Guardian Name') }}"
                                                value="{{ html_decode($user?->details?->guardian_name) }}" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="guardian_phone" name="guardian_phone"
                                                label="{{ __('Guardian Phone') }}"
                                                placeholder="{{ __('Enter Guardian Phone') }}"
                                                value="{{ html_decode($user?->details?->guardian_phone) }}" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input type="number" id="age" name="age"
                                                label="{{ __('Age') }}" placeholder="{{ __('Enter Age') }}"
                                                value="{{ html_decode($user?->details?->age) }}" required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="occupation" name="occupation"
                                                label="{{ __('Occupation') }}" placeholder="{{ __('Enter Occupation') }}"
                                                value="{{ html_decode($user?->details?->occupation) }}" required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-select name="gender" id="gender" class="select2"
                                                label="{{ __('Gender') }}" required="true">
                                                <x-admin.select-option value="" text="{{ __('Select gender') }}" />
                                                <x-admin.select-option :selected="$user?->details?->gender == 'male'" value="male"
                                                    text="{{ __('Male') }}" />
                                                <x-admin.select-option :selected="$user?->details?->gender == 'female'" value="female"
                                                    text="{{ __('Female') }}" />
                                                <x-admin.select-option :selected="$user?->details?->gender == 'others'" value="others"
                                                    text="{{ __('Others') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="address" name="address"
                                                label="{{ __('Address') }}" placeholder="{{ __('Enter Address') }}"
                                                value="{{ html_decode($user?->details?->address) }}" required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="country" name="country"
                                                label="{{ __('Country') }}" placeholder="{{ __('Enter Country') }}"
                                                value="{{ html_decode($user?->details?->country) }}" required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input id="city" name="city"
                                                label="{{ __('City') }}" placeholder="{{ __('Enter City') }}"
                                                value="{{ html_decode($user?->details?->city) }}" required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input class="datepicker" id="date_of_birth"
                                                name="date_of_birth" label="{{ __('Date Of Birth') }}"
                                                placeholder="{{ __('Enter Date Of Birth') }}"
                                                value="{{ html_decode($user?->details?->date_of_birth) }}" />
                                        </div>
                                        @adminCan('client.update')
                                            <div class="col-md-12 mt-4">
                                                <x-admin.update-button :text="__('Update Profile')" />
                                            </div>
                                        @endadminCan
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- change password card area --}}
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Change Password')" />
                            </div>
                            <div class="card-body">
                                <form action="{{ checkAdminHasPermission('client.update') ? route('admin.customer-password-change', $user->id) : '' }}" method="post">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input type="password" id="password" name="password"
                                                label="{{ __('Password') }}" placeholder="{{ __('Enter Password') }}"
                                                required="true" />
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input type="password" id="password_confirmation"
                                                name="password_confirmation" label="{{ __('Confirm Password') }}"
                                                placeholder="{{ __('Enter Confirm Password') }}" required="true" />
                                        </div>
                                        @adminCan('client.update')
                                            <div class="col-md-12 mt-4">
                                                <x-admin.update-button :text="__('Change Password')" />
                                            </div>
                                        @endadminCan
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- banned history card area --}}
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Banned History')" />
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="30%">{{ __('Subject') }}</th>
                                            <th width="30%">{{ __('Description') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($banned_histories as $banned_history)
                                            <tr>
                                                <td>{{ $banned_history->subject }}</td>
                                                <td>{!! nl2br($banned_history->description) !!}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    @adminCan('client.update')
        <!-- Start Banned modal -->
        <div class="modal fade" id="bannedModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Banned request confirmation') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="{{ route('admin.send-banned-request', $user->id) }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <x-admin.form-input id="banned_user_subject" name="subject" label="{{ __('Subject') }}"
                                        placeholder="{{ __('Enter Subject') }}" value="{{ old('subject') }}"
                                        required="true" />
                                </div>

                                <div class="form-group">
                                    <x-admin.form-textarea id="banned_user_description" name="description"
                                        label="{{ __('Description') }}" placeholder="{{ __('Enter Description') }}"
                                        value="{{ old('description') }}" maxlength="1000" required="true" />
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Send Mail') }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Banned modal -->

        <!-- Start Verify modal -->
        <div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Send verify link to customer mail') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <p>{{ __('Are you sure want to send verify link to customer mail?') }}</p>

                            <form action="{{ route('admin.send-verify-request', $user->id) }}" method="POST">
                                @csrf

                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Send Mail') }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Verify modal -->
    @endadminCan

    @adminCan('client.bulk.mail')
        <!-- Start Send Mail modal -->
        <div class="modal fade" id="sendMailModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Send mail to client') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="{{ route('admin.send-mail-to-customer', $user->id) }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <x-admin.form-input id="mail_send_subject" name="subject" label="{{ __('Subject') }}"
                                        placeholder="{{ __('Enter Subject') }}" value="{{ old('subject') }}"
                                        required="true" />
                                </div>

                                <div class="form-group">
                                    <x-admin.form-textarea id="mail_send_description" name="description"
                                        label="{{ __('Description') }}" placeholder="{{ __('Enter Description') }}"
                                        value="{{ old('description') }}" maxlength="1000" />
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Send Mail') }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Send Mail modal -->
    @endadminCan

    <x-admin.delete-modal />

    @push('js')
        <script>
            function deleteData(id) {
                $("#deleteForm").attr("action", '{{ url('/admin/client-delete/') }}' + "/" + id)
            }
        </script>
    @endpush
@endsection
