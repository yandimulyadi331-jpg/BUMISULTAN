@props([
    'icon' => '',
    'name' => '',
    'label' => '',
    'value' => '',
    'readonly' => false,
    'type' => 'text',
    'align' => '',
    'datepicker' => '',
    'money' => false,
    'required' => false,
])
<div class="form-group mb-3">
    <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">
        {{ $label }}
        @if($required || $errors->has($name))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group input-group-merge">
        <span class="input-group-text" id="basic-addon-search31"><i class="{{ $icon }}"></i></span>
        <input type="{{ $type }}" class="form-control {{ $money ? 'money' : '' }}  {{ $datepicker }} @error($name) is-invalid @enderror"
            id="{{ $name }}" name="{{ $name }}" placeholder="{{ $label }}"
            {{ $readonly ? 'readonly' : '' }} autocomplete="off" aria-autocomplete="none" value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            style="text-align: {{ $align }}">
    </div>
    @error($name)
        <small class="text-danger d-block mt-1">
            <i class="ti ti-alert-circle me-1"></i>{{ $message }}
        </small>
    @enderror
</div>
