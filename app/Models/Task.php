<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    
    public static $statusMap = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    protected $fillable = ['user_id','title','description','due_date','status'];

    public function getStatusDescriptionAttribute()
    {
        return self::$statusMap[$this->status];
    }

    // protected static function booted()
    // {
    //     static::retrieved(function ($model) {
    //         $model->status = $model->status_description;
    //     });
    // }

}
