<button {!! $attributes->merge([
    'class' =>
        'flex flex-row justify-center bg-[#2fb5fa] hover:bg-[#1d8acb] text-white p-2 rounded-sm text-lg font-extrabold',
]) !!}>
    {{ $slot }}
</button>
