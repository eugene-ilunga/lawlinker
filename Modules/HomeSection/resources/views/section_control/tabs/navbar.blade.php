@if ($code == $languages->first()->code)
    <li class="nav-item">
        <a class="nav-link active show" id="feature-tab" data-bs-toggle="tab" href="#feature_tab" role="tab"
            aria-controls="feature" aria-selected="true">{{ __('Feature Section') }}</a>
    </li>
@endif
<li class="nav-item">
    <a class="nav-link {{$code == $languages->first()->code ? '': 'active show'}}" id="work-tab" data-bs-toggle="tab" href="#work_tab" role="tab" aria-controls="work"
        aria-selected="true">{{ __('Work Section') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" id="service-tab" data-bs-toggle="tab" href="#service_tab" role="tab" aria-controls="service"
        aria-selected="true">{{ __('Service Section') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" id="department-tab" data-bs-toggle="tab" href="#department_tab" role="tab"
        aria-controls="department" aria-selected="true">{{ __('Department Section') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" id="client-tab" data-bs-toggle="tab" href="#client_tab" role="tab" aria-controls="client"
        aria-selected="true">{{ __('Testimonial Section') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" id="lawyer-tab" data-bs-toggle="tab" href="#lawyer_tab" role="tab" aria-controls="lawyer"
        aria-selected="true">{{ __('Lawyer Section') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" id="blog-tab" data-bs-toggle="tab" href="#blog_tab" role="tab" aria-controls="blog"
        aria-selected="true">{{ __('Blog Section') }}</a>
</li>
