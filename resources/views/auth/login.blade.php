@extends('layout.auth')

@section('content')
    <a class='mx-auto' href={{ route('welcome') }}>
        <img class='h-20' src={{ asset('assets/images/spiderum-logo.png') }}>
    </a>
    <x-blog::text-input placeholder="Tên đăng nhập hoặc email" name='email' />
    <x-blog::text-input placeholder="Mật khẩu" type='password' name='password' />

    <x-blog::button>
        <span class='text-base font-normal'>Đăng nhập</span>
    </x-blog::button>

    <p>Đăng nhập bằng</p>

    <x-blog::button class='bg-[#43618c] hover:bg-[#1d8acb]'>
        <x-blog::icons.facebook />
        <p class='my-auto'>Facebook</p>
    </x-blog::button>

    <x-blog::link routeName='forgot-password'>Quên mật khẩu?</x-blog::link>

    <p>
        Không có tài khoản?
        <x-blog::link routeName='sign-up'>Đăng kí ngay</x-blog::link>
    </p>
@stop
