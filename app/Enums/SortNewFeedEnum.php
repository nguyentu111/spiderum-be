<?php

namespace App\Enums;

enum SortNewFeedEnum: string {
    case Hot = 'hot';

    case Follow = 'follow';

    case New = 'new';

    case Controversial = 'controversial';

    case Top = 'top';
}
