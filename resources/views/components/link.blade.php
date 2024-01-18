@props([
    'routeName' => '/',
])
<a {!! $attributes->merge([
    'class' => 'text-sky-500 hover:text-sky-700 hover:underline',
]) !!} href={{ route($routeName) }}>{{ $slot }}</a>
