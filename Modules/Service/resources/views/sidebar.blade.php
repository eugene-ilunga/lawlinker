<li class="{{ isRoute(['admin.service.*','admin.service.gallery','admin.service.videos','admin.faq.by.service'], 'active') }}">
    <a class="nav-link" href="{{ route('admin.service.index') }}">
        <i class="fas fa-life-ring"></i>
        <span>{{ __('Services') }} </span>
    </a>
</li>
