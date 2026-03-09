@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Message') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Message') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Message') }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Dashboard Start-->
    <div class="dashboard-area pt_70 pb_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('client.profile.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="detail-dashboard add-form">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="user-wrapper">
                                    <ul class="users">
                                        @foreach ($lawyers as $lawyer)
                                            <li class="user" id="{{ $lawyer->lawyer->id }}">
                                                @php
                                                    $count = App\Models\Message::where([
                                                        'user_id' => $user?->id,
                                                        'lawyer_id' => $lawyer?->lawyer?->id,
                                                        'user_view' => 0,
                                                    ])->count();
                                                @endphp

                                                <div class="media d-flex">
                                                    <div class="media-left profile-wrapper"
                                                        data-id="{{ $lawyer?->lawyer?->id }}">
                                                        <span
                                                            class="pending @if ($count <= 0) d-none @endif">{{ $count }}</span>
                                                        <img src="{{ !empty($lawyer?->lawyer?->image) && file_exists(public_path($lawyer?->lawyer?->image)) ? asset($lawyer?->lawyer?->image) : asset($setting?->default_avatar) }}"
                                                            alt="{{ $lawyer?->lawyer?->name }}" class="media-object">
                                                        <span class="status inactive"></span>
                                                    </div>

                                                    <div class="media-body">
                                                        <p class="name">{{ $lawyer?->lawyer?->name }}</p>
                                                        <p class="email">{{ $lawyer?->lawyer?->email }}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-7" id="messages">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Dashboard End-->
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
            color: #ffffff !important;
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
            max-width: 70%;
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
            display: inline-block;
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
        }

        .send_text {
            position: relative;
            margin-top: 15px;
        }

        .send_text input {
            border: 1px solid #ddd !important;
            margin: 0;
            border-radius: 6px !important;
            padding: 11px 150px 11px 20px !important;
        }

        .send_text button {
            position: absolute;
            right: 0;
            padding: 11px 40px !important;
        }
        .profile-wrapper {
            position: relative;
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
            top: auto;
            left: auto;
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
            top: auto;
            left: auto;
        }
    </style>
@endpush
@if ($setting?->pusher_status == 'active')
    @push('js')
        @include('global.pusher')
        @vite('resources/js/app.js')
    @endpush
@endif
