@extends('layout.auth')

@section('content')
    <a class='mx-auto' href={{ route('welcome') }}>
        <img class='h-20' src={{ asset('assets/images/spiderum-logo.png') }}>
    </a>
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0 mt-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form class='w-full flex flex-col gap-4' method="POST" action='{{ route('handle-login') }}'>
        @csrf
        <x-blog::text-input placeholder="Tên đăng nhập hoặc email" name='email' required />
        <x-blog::text-input placeholder="Mật khẩu" type='password' name='password' required />

        <x-blog::button type='submit'>
            <span class='text-base font-normal'>Đăng nhập</span>
        </x-blog::button>
    </form>

    <span class='text-sm'>Đăng nhập bằng</span>

    <x-blog::link routeName='facebook-login'
        class='bg-[#43618c] hover:bg-[#1d8acb] normal-case text-white hover:text-white hover:no-underline flex flex-row justify-center
p-2 rounded-sm text-lg font-extrabold'>
        <x-blog::icons.facebook />
        <p class='my-auto'>Facebook</p>
    </x-blog::link>


    <x-blog::link routeName='forgot-password'>Quên mật khẩu?</x-blog::link>

    <p>
        Không có tài khoản?
        <x-blog::link routeName='sign-up'>Đăng kí ngay</x-blog::link>
    </p>
@stop
