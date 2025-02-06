<?
namespace App\Http\Helpers;


class AppHelper{
    const STATUS = [
        'Opend',
        'Pending',
        'Resolved',
        'Closed'
    ];
    const STATUS_OPEND = 1;
    const STATUS_PENDING = 2;
    const STATUS_RESOLVED = 3;
    const STATUS_CLOSED = 4;
    
    const PRIORITY = [
        'Low',
        'Meduim',
        'High',
        'Urgent'
    ];
    const PRIORITY_LOW = 1;
    const PRIORITY_MEDUIM = 2;
    const PRIORITY_HIGH = 3;
    const PRIORITY_URGENT = 4;
}
