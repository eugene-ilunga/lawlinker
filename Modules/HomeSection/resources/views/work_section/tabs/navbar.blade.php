<li class="nav-item">
    <a class="nav-link active show" id="work-tab" data-bs-toggle="tab" href="#work_tab" role="tab" aria-controls="work"
        aria-selected="true">{{ __('Work Section') }}</a>
</li>
@if (checkAdminHasPermission('work.section.faq.view'))
    <li class="nav-item">
        <a class="nav-link" id="faq-tab" data-bs-toggle="tab" href="#faq_tab" role="tab" aria-controls="faq"
            aria-selected="true">{{ __('Work FAQ') }}</a>
    </li>
@endif
