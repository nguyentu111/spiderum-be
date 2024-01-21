@extends('layout.auth')

<div class='flex flex-col mx-auto justify-center w-[50%] h-full gap-4'>
    <h2 class='text-center text-2xl'>Chào mừng bạn đến với cộng đồng Blog</h2>
    <span class='text-center'>Hãy nhấn vào nút bên dưới để tạo tài khoản và tham gia cùng chúng tôi (đường link
        chỉ sử dụng được trong vòng
        30 phút):
    </span>
    <x-blog::link routeName='facebook-login'
        class='bg-sky-500 hover:bg-[#1d8acb] normal-case text-white hover:text-white hover:no-underline
        flex flex-row justify-center gap-1 p-2 rounded-sm font-semibold w-[60%] mx-auto'>
        <p class='my-auto text-center'>Đăng ký tài khoản với email</p>
        <div class='mt-0.5'>
            <x-blog::icons.right-arrow />
        </div>
    </x-blog::link>

</div>

@section('javascript')
    @vite('resources/js/mail.js')
@endsection
