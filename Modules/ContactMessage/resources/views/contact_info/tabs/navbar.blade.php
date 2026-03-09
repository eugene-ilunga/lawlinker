<li class="nav-item">
    <a class="nav-link active show" id="contact-tab" data-bs-toggle="tab" href="#contact_info" role="tab"
        aria-controls="contact" aria-selected="true">{{ __('Contact Info') }}</a>
</li>
@if ($code == $languages->first()->code)
    <li class="nav-item">
        <a class="nav-link" id="top-tab" data-bs-toggle="tab" href="#top_bar" role="tab" aria-controls="top"
            aria-selected="true">{{ __('Top Bar Info') }}</a>
    </li>
@endif
