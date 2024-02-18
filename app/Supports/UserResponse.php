<?php

namespace App\Supports;
use App\Models\User;
use App\Models\UserInfo;

class UserResponse {
    private User $user;
    private UserInfo $userInfo;

    public function __construct(User $user, UserInfo $userInfo) {
        $this->user = $user;
        $this->userInfo = $userInfo;
    }

    public function getResponse(): array {
        return [
            'id' => $this->user->id,
            'alias' => $this->user->alias,
            'avatar_url' => $this->user->avatar_url,
            'email' => $this->userInfo->email,
            'phone_number' => $this->userInfo->phone_number,
            'dob' => $this->userInfo->dob,
            'description' => $this->userInfo->description,
            'id_number' => $this->userInfo->id_number,
        ];
    }
}
