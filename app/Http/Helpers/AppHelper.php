<?php
namespace App\Http\Helpers;

class AppHelper {

    const USER_SUPER_ADMIN = 1;
    const USER_ADMIN = 2;
    const USER_EMPLOYEE = 3;
    const USER_ADMIN_SUPPORT = 4;
    const GENDER = [
        1 => 'Male',
        2 => 'Female'
    ];
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    const LANGUAGES = ['en', 'kh'];

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



