@props(['href' => '','text' => __('Add New'),'variant'=> 'primary'])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn btn-'.$variant]) }}><i class="fa fa-plus"></i> {{ $text }}</a>