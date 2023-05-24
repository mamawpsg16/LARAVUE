<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_ONGOING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = 3;
    
    public static $statusMap = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_ONGOING => 'Ongoing',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    protected $appends = ['status_description'];

    protected $fillable = ['user_id','title','description','due_date','status'];

    public function getStatusDescriptionAttribute()
    {
        if (isset($this->status)) {
            return self::$statusMap[$this->status];
        } else {
            // Return a default or fallback value if the status attribute is not set
            return 'Unknown';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // protected static function booted()
    // {
    //     static::retrieved(function ($model) {
    //         $model->status = $model->status_description;
    //     });
    // }

}
