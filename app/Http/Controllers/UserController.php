<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $tokenScript = $request->query('token');

        return view('user.create-form', [
            'errorMessage' => 'Có lỗi xảy ra. Xin vui lòng thử lại sau.'
        ]);
        // [$token, $expiryTime] = explode('|', $tokenScript);

        // $expiryTime = Carbon::parse($expiryTime);

        // if ($expiryTime->lt(Carbon::now())) {
        //     return view('user.create-form', [
        //         '' => 'Có lỗi xảy ra. Xin vui lòng thử lại sau.'
        //     ]);
        // } else {
        //     return view('user.create-form', [
        //         'errorMessage' => 'Có lỗi xảy ra. Xin vui lòng thử lại sau.'
        //     ]);
        // }
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
