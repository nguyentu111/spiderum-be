<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Blog</title>
    <style>
        body div {
            margin: 0 auto;
            width: 400px;
            height: 100%;
            gap: 16px;
        }

        span,
        p,
        h2 {
            text-align: center;
            justify-content: center;
        }

        span {
            color: rgb(100 116 139)
        }

        p {
            margin: auto 0;
            width: 100%;
            white-space: nowrap
        }

        #btn {
            background-color: rgb(14 165 233);
            color: white;
            width: 60%;
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
        <h2>Xin chào {{$username}}</h2>
        <span>Gần đây bạn có yêu cầu lập lại mật khẩu tài khoản của bạn trên hệ thống Spiderum, để tiếp tục quá trình này, hãy nhấn vào nút bên dưới (đường link chỉ sử dụng được trong vòng 30 phút):
        </span>
        <a id='btn' href="{{env('FRONT_END_URL').'/reset-password?token='.$token}}">
            <p class='btn-text'>Lấy lại mật khẩu</p>
            <div class='icon'>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z">
                    </path>
                </svg>
            </div>
        </a>
    </div>
</body>

</html>
