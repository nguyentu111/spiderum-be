<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class='h-screen'>

    <div id='head-alert'
        class='fixed hidden flex-row w-full justify-between p-2 bg-lime-50 border border-lime-400 text-lime-600'>
        <span></span>
        <div id='close-alert' class='cursor-pointer opacity-40 hover:opacity-80 duration-200'>
            <x-blog::icons.close />
        </div>
    </div>

    <div class='flex flex-col mx-auto justify-center w-80 h-full gap-4'>
        @yield('notification')
        @yield('content')
    </div>

    @yield('javascript')
</body>

</html>
