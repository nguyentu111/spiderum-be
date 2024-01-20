@extends('layout.auth')

@section('content')
    <a class='mx-auto' href={{ route('welcome') }}>
        <img class='h-20' src={{ asset('assets/images/spiderum-logo.png') }}>
    </a>

    <span class='text-sm'>Vui lòng nhập địa chỉ email của bạn để được cấp lại mật khẩu</span>

    <x-blog::text-input placeholder="email@gmail.com" name='email' />

    <div class="flex flex-row gap-4">
        <span class='text-sm'>Thư xác nhận sẽ được gửi vào hòm thư của bạn</span>
        <x-blog::button class='bg-sky-500 hover:bg-sky-600 text-base !font-normal px-4 py-2'>
            <span class='text-sm'>Gửi</span>
        </x-blog::button>
    </div>
@stop
