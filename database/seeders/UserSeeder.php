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
            'id' => '627f3002-ab34-4e01-a51b-10dda3cefcf4',
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

        $user1 = User::create([
            'id' => 'cc937bdd-0bcb-4583-bd69-31b4d3bdf413',
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

        $user1->followers()->attach('627f3002-ab34-4e01-a51b-10dda3cefcf4');

        $user = User::create([
            'id' => '2c617ce9-1bad-4a7f-b240-1fdd89e1aeb3',
            'username' => 'tunguyen123',
            'password' => bcrypt(env('PASSWORD_DEFAULT')),
            'alias' => 'Anh Tu',
        ]);

        UserInfo::create([
            'email' => 'anhtu123@gmail.com',
            'phone_number' => '0398745145',
            'user_id' => $user->getKey()
        ]);

        $user1->followers()->attach('2c617ce9-1bad-4a7f-b240-1fdd89e1aeb3');

        $user = User::create([
            'id' => 'fcf9279d-6495-4495-a6de-8e336ed025c2',
            'username' => 'anhkiet123',
            'password' => bcrypt(env('PASSWORD_DEFAULT')),
            'alias' => 'Kiet Phan',
        ]);

        UserInfo::create([
            'email' => 'messi123@gmail.com',
            'phone_number' => '0987777888',
            'user_id' => $user->getKey()
        ]);

        $user1->followers()->attach('fcf9279d-6495-4495-a6de-8e336ed025c2');
    }
}
