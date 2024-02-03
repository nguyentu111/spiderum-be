@extends('layout.auth')

@section('content')
    <a class='mx-auto' href={{ route('welcome') }}>
        <img class='h-20' src={{ asset('assets/images/spiderum-logo.png') }}>
    </a>

    <form class='w-full flex flex-col gap-4' method="POST">
        @csrf
        <x-blog::text-input placeholder="Tên đăng nhập" type='email' name='username' required />
        <x-blog::text-input placeholder="Tên hiển thị" type='text' name='name' required />
        <x-blog::text-input placeholder="Mật khẩu" type='password' name='password' required />
        <x-blog::text-input placeholder="Nhập lại mật khẩu" type='password' name='confirmation_password' required />

        <x-blog::button type='button' id='toggle-more-info'
            class='!bg-gray-100 hover:!bg-gray-200 !text-gray-600 !border border-slate-300'>
            <span class='text-base font-normal !text-black'>Thông tin khác</span>
        </x-blog::button>

        <div id='card' class='none'>
            <div class='flex flex-col gap-4 '>
                <x-blog::text-input placeholder="Số chứng minh nhân dân" type='text' name='id_card' required />
                <x-blog::text-input placeholder="Số điện thoại" type='email' name='phone_number' required />
            </div>
        </div>
        <p>
            Bằng việc nhấn vào Đăng ký, bạn đã đồng ý với
            <x-blog::link routeName='sign-up'>Điều khoản sử dụng</x-blog::link>
            và
            <x-blog::link routeName='sign-up'> Chính sách bảo mật</x-blog::link>
            của
            <span class='font-semibold'>Spiderum</span>
        </p>
        <x-blog::button type='submit' id='register-btn'>
            <span class='text-base font-normal'>Đăng ký</span>
        </x-blog::button>
    </form>
@stop

@section('javascript')
    @vite('resources/js/user.js')
@endsection
