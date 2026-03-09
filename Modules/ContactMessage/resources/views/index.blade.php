@extends('admin.master_layout')
@section('title')
    <title>{{ __('Contact Message') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Contact Message') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Contact Message') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.update-general-setting') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-2">
                                                <x-admin.form-input label="{{ __('Contact Message Receiver Email') }}"
                                                    type="email" id="contact_message_receiver_mail"
                                                    name="contact_message_receiver_mail"
                                                    placeholder="{{ __('Enter Email') }}"
                                                    value="{{ $setting?->contact_message_receiver_mail }}" />
                                            </div>
                                            <div class="form-group mb-3">
                                                <x-admin.form-switch name="save_contact_message"
                                                    label="{{ __('Save Contact Message in Database?') }}"
                                                    :checked="$setting->save_contact_message == '1'" />
                                            </div>
                                            <x-admin.update-button :text="__('Update')" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-invoice">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('SN') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Created at') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($messages as $index => $message)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ html_decode($message->name) }}</td>
                                            <td><a
                                                    href="mailto:{{ html_decode($message->email) }}">{{ html_decode($message->email) }}</a>
                                            </td>
                                            <td>{{ formattedDateTime($message->created_at) }}</td>
                                            <td>
                                                <a href="{{ route('admin.contact-message', $message->id) }}"
                                                    class="btn btn-success btn-sm"><i class="fa fa-eye"
                                                        aria-hidden="true"></i></a>
                                                <x-admin.delete-button :id="$message->id" onclick="deleteData" />
                                            </td>
                                        </tr>
                                    @empty
                                        <x-empty-table :name="__('')" route="" create="no" :message="__('No data found!')"
                                            colspan="5"></x-empty-table>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
    </div>
    </section>
    </div>

    <x-admin.delete-modal />
    @push('js')
        <script>
            function deleteData(id) {
                $("#deleteForm").attr("action", '{{ url('/admin/contact-message-delete/') }}' + "/" + id)
            }
        </script>
    @endpush
@endsection
