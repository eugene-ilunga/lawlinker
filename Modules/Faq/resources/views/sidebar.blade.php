    <li class="{{ isRoute(['admin.faq.by.category', 'admin.faq-category*'], 'active') }}">
        <a class="nav-link" href="{{ route('admin.faq-category.index') }}">
            <i class="fas fa-question-circle"></i> <span>{{ __('FAQS') }}</span>
        </a>
    </li>
