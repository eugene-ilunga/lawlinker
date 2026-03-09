@props([
    'id' => '',
    'name' => '',
    'label' => null,
    'type' => 'text',
    'value' => '',
    'required' => false,
    'error' => true,
])

@if ($label)
    <label for="{{ $id }}">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
@endif
<input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" {{ $attributes->merge(['class' => 'form-control']) }}>
@error($name)
    <span class="text-danger">{{ $message }}</span>
@enderror
