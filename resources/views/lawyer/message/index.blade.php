@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Message') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Message') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('Message') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('lawyer.dashboard')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="user-wrapper">
                                            <ul class="users">
                                                @foreach ($users as $user)
                                                    <li class="user" id="{{ $user->user->id }}">
                                                        @php
                                                            $lawyer = lawyerAuth();
                                                            $count = App\Models\Message::where([
                                                                'lawyer_id' => $lawyer->id,
                                                                'user_id' => $user->user->id,
                                                                'lawyer_view' => 0,
                                                            ])->count();
                                                        @endphp
                                                        
                                                        <div class="d-flex align-items-center mt-2">
                                                            <div class="media-left profile-wrapper" data-id="{{$user?->user->id}}">
                                                                <span class="pending @if ($count <= 0) d-none @endif">{{ $count }}</span>
                                                                <img src="{{ !empty($user?->user->image) && file_exists(public_path($user?->user->image)) ? asset($user?->user->image) : asset($setting?->default_avatar) }}"
                                                                    alt="{{ $user?->user->name }}" class="media-object">
                                                                    <span class="status inactive"></span>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $user?->user->name }}</h6>
                                                                <p class="mb-0">{{ $user?->user->email }}</p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-8" id="messages"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('css')
    <style>
        /* width */
        ::-webkit-scrollbar {
            width: 7px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 50px
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #a7a7a7;
            border-radius: 50px
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #929292;
            border-radius: 50px
        }

        ul {
            margin: 0;
            padding: 0;
        }

        li {
            list-style: none;
        }

        .user-wrapper,
        .message-wrapper {
            border: 1px solid #dddddd;
            overflow-y: auto;
        }

        .user-wrapper {
            height: 600px;
            border-radius: 10px;
        }

        .user {
            cursor: pointer;
            padding: 5px 0;
            position: relative;
        }

        .user:hover {
            background: #eeeeee;
        }

        .user:last-child {
            margin-bottom: 0;
        }

        .pending {
            position: absolute;
            left: 0px;
            top: 4px;
            background: #b600ff;
            margin: 0;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            line-height: 18px;
            padding-left: 5px;
            color: #ffffff;
            font-size: 12px;
        }

        .pending.d-none {
            display: none;
        }

        .media-left {
            margin: 0 10px;
        }

        .media-left img {
            width: 64px;
            border-radius: 64px;
            object-fit: cover;
            height: 64px;
        }

        .media-body p {
            margin: 6px 0;
        }

        .message-wrapper {
            padding: 15px;
            height: 536px;
            background: #eeeeee;
            border-radius: 10px;
        }

        .messages .message {
            margin-bottom: 15px;
        }

        .messages .message:last-child {
            margin-bottom: 0;
        }

        .received,
        .sent {
            max-width: 50%;
            padding: 3px 10px;
            border-radius: 10px;
            display: inline-block;
        }

        .message p {
            background: #fff;
            padding: 3px 15px;
            border-radius: 7px;
            margin: 0;
            position: relative;
            display: inline-block
        }

        .message p::after {
            position: absolute;
            content: "";
            width: 0;
            height: 0;
            border-top: 5px solid transparent;
            border-right: 16px solid #ffffff;
            border-bottom: 7px solid transparent;
            top: -3px;
            left: -10px;
            transform: rotate(18deg);
        }

        .sent {
            float: right;
            text-align: right;
        }

        .sent p {
            background: #6679f0;
            color: #fff;
        }

        .sent p::after {
            width: 0;
            height: 0;
            border-top: 5px solid transparent;
            border-left: 16px solid #6679f0;
            border-right: 0;
            border-bottom: 7px solid transparent;
            left: auto;
            right: -10px;
            transform: rotate(-18deg);
        }

        .message p {}

        .date {
            color: #777777 !important;
            font-size: 11px;
            background: none !important;
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
        }

        .date::after {
            display: none !important;
        }

        .active {
            background: #eeeeee;
        }

        input[type=text] {
            width: 100%;
            padding: 12px 20px;
            margin: 15px 0 0 0;
            display: inline-block;
            border-radius: 4px;
            box-sizing: border-box;
            outline: none;
            border: 1px solid #cccccc;
        }

        input[type=text]:focus {
            border: 1px solid #aaaaaa;
        }

        #sentMessageBtn {
            padding: 10px;
            border-radius: 0 6px 6px 0;
        }

        .send_text {
            position: relative;
            margin-top: 15px;
        }

        .send_text input {
            border: 1px solid #ddd !important;
            margin: 0;
            border-radius: 6px !important;
            padding: 11px 150px 12px 20px !important;
        }

        .send_text button {
            position: absolute;
            right: 0;
            padding: 10px 40px !important;
        }

        /* active inactive design */
        .status.inactive {
            height: 15px !important;
            width: 15px !important;
            display: block;
            background: rgb(223, 156, 14);
            border-radius: 50%;
            position: absolute;
            bottom: 15px;
            right: 1px;
        }

        .status.active {
            height: 15px !important;
            width: 15px !important;
            display: block;
            background: rgb(9, 185, 38);
            border-radius: 50%;
            position: absolute;
            bottom: 15px;
            right: 1px;
        }

        .profile-wrapper {
            position: relative;
        }
        .breadcrumb-item.active {
            background: transparent;
        }
    </style>
@endpush
@push('js')
    @if ($setting?->pusher_status == 'active')
        @include('global.pusher')
        @vite('resources/js/app.js')
    @endif
    <script>
        var receiver_id = '';
        var my_id = "{{ lawyerAuth()?->id }}";

        (function($) {
            "use strict";
            // ajax setup form csrf token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).ready(function() {

                $('.user').on('click', function() {
                    $('.user').removeClass('active');
                    $(this).addClass('active');
                    $(this).find('.pending').addClass('d-none');

                    receiver_id = $(this).attr('id');
                    $.ajax({
                        type: "get",
                        url: "{{ url('lawyer/get-message/') }}" + "/" + receiver_id,
                        data: "",
                        cache: false,
                        success: function(data) {
                            $('#messages').html(data);
                            scrollToBottomFunc();
                        }
                    });
                });

                // send message by key presss
                $(document).on('keyup', '.input-text input', function(e) {
                    var message = sanitizeInput($(this).val());

                    if (message != '') {
                        $("#sentMessageBtn").prop("disabled", false);
                    } else {
                        $("#sentMessageBtn").prop("disabled", true);
                    }

                    if (e.keyCode == 13 && message != '' && receiver_id != '') {
                        $(this).val('');

                        var data = {
                            receiver_id: receiver_id,
                            message: message
                        };

                        sentMessageItemAppend(`#lawyer-${my_id}`, message);

                        // project demo mode check
                        var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}"
                        if (isDemo == 'DEMO') {
                            toastr.error(
                                "{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                            return;
                        }
                        // end


                        $.ajax({
                            type: "POST",
                            url: "{{ url('/lawyer/send-message') }}",
                            data: data,
                            cache: false,
                            success: function(data) {
                                scrollToBottomFunc();
                                $('#' + data.user_id).click();
                            },
                            error: function(jqXHR, status, err) {}
                        })
                    }
                });


            });

        })(jQuery);

        function sanitizeInput(input) {
            return input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }

        // make a function to scroll down auto
        function scrollToBottomFunc() {
            $('.message-wrapper').animate({
                scrollTop: $('.message-wrapper').get(0).scrollHeight
            }, 50);
        }

        function sentMessageItemAppend(boxId, message) {
            scrollToBottomFunc();
            const messageList = $(boxId);
            const messageItem = `<li class="message clearfix">
                    <div class="sent">
                        <p>${message}</p>
                        <p class="date"><i class="fas fa-spinner fa-spin"></i></p>
                    </div>
                </li>`;
            messageList.append(messageItem);
        }

        function sendMessage() {
            var message = sanitizeInput($(".submit").val());
            $(".submit").val('')
            var data = {
                receiver_id: receiver_id,
                message: message
            };

            sentMessageItemAppend(`#lawyer-${my_id}`, message);

            // project demo mode check
            var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}"
            if (isDemo == 'DEMO') {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }

            $.ajax({
                type: "POST",
                url: "{{ url('/lawyer/send-message') }}",
                data: data,
                cache: false,
                success: function(data) {
                    scrollToBottomFunc();
                    $('#' + data.user_id).click();

                },
                error: function(jqXHR, status, err) {}
            })
        }
    </script>
@endpush
