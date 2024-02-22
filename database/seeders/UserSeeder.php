<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'username' => 'nghi1421',
            'password' => bcrypt(env('PASSWORD_DEFAULT')),
            'alias' => 'Hubert Nguyen',
        ]);

        UserInfo::create([
            'email' => 'hubertnguyen.service@gmail.com',
            'phone_number' => '0336593650',
            'id_number' => '05412436147541',
            'user_id' => $user->getKey()
        ]);

        $user = User::create([
            'username' => 'thanhnghi1421',
            'password' => bcrypt(env('PASSWORD_DEFAULT')),
            'alias' => 'Thanh Nghi',
        ]);

        UserInfo::create([
            'email' => 'thanhnghi.dev@gmail.com',
            'phone_number' => '0336589451',
            'id_number' => '05127846147541',
            'user_id' => $user->getKey()
        ]);

        $user = User::create([
            'username' => 'tunguyen123',
            'password' => bcrypt(env('PASSWORD_DEFAULT')),
            'alias' => 'Anh Tu',
        ]);

        UserInfo::create([
            'email' => 'anhtu123@gmail.com',
            'phone_number' => '0398745145',
            'user_id' => $user->getKey()
        ]);

        $user = User::create([
            'username' => 'anhkiet123',
            'password' => bcrypt(env('PASSWORD_DEFAULT')),
            'alias' => 'Kiet Phan',
        ]);

        UserInfo::create([
            'email' => 'messi123@gmail.com',
            'phone_number' => '0987777888',
            'user_id' => $user->getKey()
        ]);
    }
}
