@extends('layout.auth')


@section('content')
    <a class='mx-auto' href={{ route('welcome') }}>
        <img class='h-20' src={{ asset('assets/images/spiderum-logo.png') }}>
    </a>

    <form id="register-form" class='w-full flex flex-col gap-4' method="POST" action="{{ route('store-user') }}">
        @csrf

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0 mt-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-blog::text-input placeholder="Tên đăng nhập" type='text' name='username' required />
        <x-blog::text-input placeholder="Tên hiển thị" type='text' name='alias' required />
        <x-blog::text-input placeholder="Mật khẩu" type='text' name='email' value='{{ $email }}' required class='hidden'/>
        <x-blog::text-input placeholder="Mật khẩu" type='password' name='password' required />
        <x-blog::text-input placeholder="Nhập lại mật khẩu" type='password' name='password_confirmation' required />

        <x-blog::button type='button' id='toggle-more-info'
            class='!bg-gray-100 hover:!bg-gray-200  !border border-slate-300 !justify-start'>
            <span class='text-base font-normal !text-gray-600'>Thông tin khác</span>
        </x-blog::button>

        <div id='card' class='none '>
            <div class='flex flex-col gap-4 '>
                <x-blog::text-input placeholder="Số chứng minh nhân dân" type='text' name='id_number' />
                <x-blog::text-input placeholder="Số điện thoại" type='text' name='phone_number' />
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
        <x-blog::button class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"
            data-callback='onSubmit' data-action='submitContact' id='register-btn'>
            <span class='text-base font-normal'>Đăng ký</span>
        </x-blog::button>

    </form>
@stop

<script>
    let errorMessage = @json($errorMessage);
</script>

@if($errors->any())
    <script>
        let errors = @json($errors->all());
    </script>
@endif

@section('javascript')
    @vite('resources/js/user.js')
@endsection
