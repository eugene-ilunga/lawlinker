@if (subscriberContent())
    <!--Subscribe Start-->
    <div class="subscribe-area"
        style="background-image: url({{ subscriberContent()?->image ? url(subscriberContent()?->image) : '' }})">
        <div class="container">
            <div class="row ov_hd">
                <div class="col-md-11 col-lg-8 col-xl-7 m-auto wow fadeInDown">
                    <div class="main-headline white">
                        <h2 class="title">{{ ucwords(subscriberContent()?->title) }}</h2>
                        <p>{{ subscriberContent()?->description }}</p>
                    </div>
                </div>
            </div>
            <div class="row ov_hd">
                <div class="col-md-12 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="subscribe-form">
                        <form method="POST" action="{{ route('newsletter-request') }}">
                            @csrf
                            <input type="email" required name="email" placeholder="{{ __('Email') }}">
                            <button type="submit" class="btn-sub">{{ __('Subscribe') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Subscribe Start-->
@endif

@if (getPartners()->isNotEmpty())
    <!--Brand-Area Start-->
    <div class="brand-area bg-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="brand-carousel owl-carousel">
                        @foreach (getPartners() as $item)
                            <a aria-label="Partner" target="_blank" href="{{ $item?->link ?? 'javascript:;' }}">
                                <div class="brand-item">
                                    <div class="brand-colume">
                                        <div class="brand-bg"></div>
                                        <img src="{{ url($item->image) }}" alt="Partner" loading="lazy">
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Brand-Area End-->
@endif

<!--Footer Start-->
<div class="main-footer">
    <div class="top-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer-address footer-address-first">
                        <ul>
                            <li>
                                <i class="far fa-envelope"></i>
                                <p class="title">{{ __('Email Address') }} </p>
                                <p>{!! nl2br(e($contactInfo?->email)) !!}</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-address">
                        <ul>
                            <li>
                                <i class="fas fa-phone"></i>
                                <p class="title">{{ __('Phone') }}</p>
                                <p>{!! nl2br(e($contactInfo?->phone)) !!}</p>

                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-address">
                        <ul>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <p class="title">{{ __('Address') }}</p>
                                <p>{!! nl2br(e($contactInfo?->address)) !!}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-area" style="background-image: url({{ url('client/img/shape-2.webp') }})">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xxl-3 col-lg-3">
                    <div class="footer-item">
                        <p class="title">{{ __('About Us') }}</p>
                        <div class="textwidget pe-0">
                            <p>{{ $contactInfo?->about }}</p>
                            <a aria-label="{{ __('Details') }}" class="sm_fbtn"
                                href="{{ url('about-us') }}">{{ __('Details') }} →</a>
                            <ul class="icon">
                                @foreach (getSocialLinks() as $social)
                                    <li><a target="_blank" aria-label="{{ $social?->link }}" href="{{ $social?->link }}"><i class="{{ $social?->icon }}"></i></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-lg-2 col-md-6">
                    <div class="footer-item">
                        <p class="title">{{ __('Important Link') }}</p>
                        @if ($footerFirstMenu = footerFirstMenu())
                            <ul>
                                @foreach ($footerFirstMenu as $menu)
                                    <li><a @if ($menu['open_new_tab']) target="_blank" @endif
                                            href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}">{{ $menu['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="col-xxl-2 col-lg-2 col-md-6">
                    <div class="footer-item">
                        <p class="title">{{ __('Account') }}</p>
                        @if ($footerSecondMenu = footerSecondMenu())
                            <ul>
                                @foreach ($footerSecondMenu as $menu)
                                    <li><a @if ($menu['open_new_tab']) target="_blank" @endif
                                            href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}">{{ $menu['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4">
                    <div class="footer-item">
                        <p class="title">{{ __('Recent Post') }}</p>
                        @foreach (footerLatestNews() as $item)
                            <div class="footer-recent-item">
                                <div class="footer-recent-photo">
                                    <a aria-label="{{ $item?->title }}"
                                        href="{{ route('website.blog.details', $item?->slug) }}"><img
                                            src="{{ url($item?->thumbnail_image) }}" alt="{{ $item?->title }}"
                                            loading="lazy"></a>
                                </div>
                                <div class="footer-recent-text">
                                    <a aria-label="{{ $item?->title }}"
                                        href="{{ route('website.blog.details', $item?->slug) }}">{{ $item?->title }}</a>
                                    <div class="footer-post-date">{{ formattedDate($item?->created_at) }}</div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="d-flex gap-2 app-store-area">
                        @if ($setting?->google_app_store_status)
                            <a aria-label="Google play store" href="{{$setting?->google_app_store_link}}"><img
                                    src="{{ asset($setting?->google_app_store_img) }}"
                                    alt="google-play-store" loading="lazy"></a>
                        @endif
                        @if ($setting?->apple_app_store_status)
                            <a aria-label="Apple play store" href="{{$setting?->apple_app_store_link}}"><img
                                    src="{{ asset($setting?->apple_app_store_img) }}"
                                    alt="apple-play-store" loading="lazy"></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyrignt">
        <div class="container">
            <div class="copyright-text text-center">
                <p>{{ $contactInfo?->copyright }}</p>
            </div>
        </div>
    </div>
</div>
<!--Footer End-->


<!--Scroll-Top-->
<div class="scroll-top">
    <i class="fas fa-angle-double-up"></i>
</div>
<!--Scroll-Top-->


<script>
    var isRtl = "{{ session()->get('text_direction') == 'rtl' }}"
    var rtlTrue = false;
    if (isRtl) {
        rtlTrue = true;
    }
</script>


<!--Js-->
<script src="{{ asset('global/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('client/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('client/js/popper.min.js') }}"></script>
<script src="{{ asset('client/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('client/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('client/js/jquery.collapse.js') }}"></script>
<script src="{{ asset('client/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('client/js/swiper-bundle.js') }}"></script>
<script src="{{ asset('client/js/jquery.filterizr.min.js') }}"></script>
<script src="{{ asset('client/js/select2.min.js') }}"></script>
<script src="{{ asset('client/js/wow.min.js') }}"></script>
<script src="{{ asset('client/js/slick.min.js') }}"></script>
<script src="{{ asset('client/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('client/js/viewportchecker.js') }}"></script>
<script src="{{ asset('client/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('client/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('client/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('client/js/custom.js') }}?v={{ $setting?->version }}"></script>
<script src="{{ asset('global/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('client/js/jquery-ui.js') }}"></script>
<script src="{{ asset('https://js.pusher.com/7.0/pusher.min.js') }}"></script>
@include('client.dynamic-js-variables')
<script src="{{ asset('client/js/ajax-request.js') }}"></script>
<script>
    @if (Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}"
        switch (type) {
            case 'info':
                toastr.info("{{ Session::get('message') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('message') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('message') }}");
                break;
            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
    @endif
</script>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif

<!--Start of Tawk.to Script-->
@if ($setting->tawk_status == 'active')
    <script type="text/javascript">
        "use strict";
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = '{{ $setting->tawk_chat_link }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endif
<!--End of Tawk.to Script-->


@if ($setting->cookie_status == 'active')
    <script src="{{ asset('client/js/cookieconsent.min.js') }}"></script>

    <script>
        "use strict";
        window.addEventListener("load", function() {
            window.wpcc.init({
                "border": "{{ $setting->border }}",
                "corners": "{{ $setting->corners }}",
                "colors": {
                    "popup": {
                        "background": "{{ $setting->background_color }}",
                        "text": "{{ $setting->text_color }} !important",
                        "border": "{{ $setting->border_color }}"
                    },
                    "button": {
                        "background": "{{ $setting->btn_bg_color }}",
                        "text": "{{ $setting->btn_text_color }}"
                    }
                },
                "content": {
                    "href": "{{ route('website.privacy-policy') }}",
                    "message": "{{ $setting->message }}",
                    "link": "{{ $setting->link_text }}",
                    "button": "{{ $setting->btn_text }}"
                }
            })
        });
    </script>
@endif
@stack('js')
@if (customCode()?->footer_javascript)
    <script>
        "use strict";
        {!! customCode()->footer_javascript !!}
    </script>
@endif

</body>

</html>
