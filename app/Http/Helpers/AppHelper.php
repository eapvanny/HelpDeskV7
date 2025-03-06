<?php
namespace App\Http\Helpers;

class AppHelper {

    const USER_SUPER_ADMIN = 1;
    const USER_ADMIN_SUPPORT = 2;
    const USER_ADMIN = 3;
    const USER_MANAGER = 4;
    const USER_DIRECTOR = 5;    
    const USER_EMPLOYEE = 6;

    const USER = [
        self::USER_SUPER_ADMIN => 'Super Admin',
        self::USER_ADMIN_SUPPORT => 'Admin Support',
        self::USER_ADMIN => 'Admin',
        self::USER_DIRECTOR => 'Manager',
        self::USER_MANAGER => 'Director',
        self::USER_EMPLOYEE => 'Employee',
    ];


    const IT_DEPARTMENT = 1;
    const SALE_DEPARTMENT = 2;
    const FINANCE_DEPARTMENT = 3;
    const MARKETING_DEPARTMENT = 4;
    const PRODUCTION_DEPARTMENT = 5;    
    const WH_LOGISTIC_DEPARTMENT = 6;
    const HR_DEPARTMENT = 7;

    const DEPARTMENT = [
        self::IT_DEPARTMENT => 'IT Department',
        self::SALE_DEPARTMENT => 'Sale Department',
        self::FINANCE_DEPARTMENT => 'Finance Department',
        self::MARKETING_DEPARTMENT => 'Marketing Department',
        self::PRODUCTION_DEPARTMENT => 'Production Department',
        self::WH_LOGISTIC_DEPARTMENT => 'WH & Logistic Department',
        self::HR_DEPARTMENT => 'Human Resource Department',
    ];


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



