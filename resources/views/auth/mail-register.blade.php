<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Blog</title>
    <style>
        body div {
            display: flex;
            flex-direction: column;
            margin: 0 auto;
            justify-content: center;
            width: 400px;
            height: 100%;
            gap: 16px;
        }

        span,
        p,
        h2 {
            text-align: center;
            justify-content: center
        }

        span {
            color: rgb(100 116 139)
        }

        p {
            margin: auto 0;
            width: 100%;
            white-space: nowrap
        }

        a {
            background-color: rgb(14 165 233);
            color: white;
            width: 60%;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            padding: 16px 20px;
            gap: 4px;
            margin: 0 auto;
            border-radius: 4px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none
        }

        a:hover {
            background-color: #1d8acb;
        }

        .icon {
            margin-top: 2px;
        }

        .btn-text {
            flex-grow: 10
        }
    </style>
</head>

<body>
    <div>
        <h2 class='text-center text-2xl'>Chào mừng bạn đến với cộng đồng Blog</h2>
        <span>Hãy nhấn vào nút bên dưới để tạo tài khoản và tham gia cùng chúng tôi (đường link
            chỉ sử dụng được trong vòng
            30 phút):
        </span>
        <a href='{{ route('mail-send-register') }}'>
            <p class='btn-text'>Đăng ký tài khoản với email</p>
            <div class='icon'>
                <svg class="w-24 h-24" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z">
                    </path>
                </svg>
            </div>
        </a>
    </div>
</body>
