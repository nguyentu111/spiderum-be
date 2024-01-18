@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-slate-200 border px-2 py-2 outline-none focus:bg-sky-50 focus:border-sky-200',
]) !!} />
