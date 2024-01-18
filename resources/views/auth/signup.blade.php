@extends('layout.auth')

@section('content')
    <a class='mx-auto' href={{ route('welcome') }}>
        <img class='h-20' src={{ asset('assets/images/spiderum-logo.png') }}>
    </a>

    <p>Đăng ký bằng</p>

    <x-blog::button class='bg-[#43618c] hover:bg-[#1d8acb]'>
        <x-blog::icons.facebook />
        <p class='my-auto'>Facebook</p>
    </x-blog::button>

    <p class='pt-1'>Đăng ký bằng email</p>
    <x-blog::text-input placeholder="Tên đăng nhập hoặc email" type='email' name='email' />


    <div class="flex flex-row gap-4">
        <span class='text-sm'>Thư xác nhận sẽ được gửi vào hòm thư của bạn</span>
        <x-blog::button class='bg-sky-500 hover:bg-sky-600 text-base !font-normal px-4 py-2'>
            <span class='text-sm'>Gửi</span>
        </x-blog::button>
    </div>
    <p>
        Đã có tài khoản?
        <x-blog::link routeName='login'>Đăng nhập</x-blog::link>
    </p>
@stop
