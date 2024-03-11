<?php

namespace App\Enums;

enum CommentOrder: string {
    case Latest = 'latest';

    case Hottest = 'hottest';
}
