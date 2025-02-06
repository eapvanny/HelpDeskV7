<?php
namespace App\Http\Helpers;

class AppHelper {
    const STATUS_OPEN = 1;
    const STATUS_PENDING = 2;
    const STATUS_RESOLVED = 3;
    const STATUS_CLOSED = 4;

    const STATUS = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_RESOLVED => 'Resolved',
        self::STATUS_CLOSED => 'Closed',
    ];

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;
    const PRIORITY_URGENT = 4;

    const PRIORITY = [
        self::PRIORITY_LOW => 'Low',
        self::PRIORITY_MEDIUM => 'Medium',
        self::PRIORITY_HIGH => 'High',
        self::PRIORITY_URGENT => 'Urgent',
    ];
}

