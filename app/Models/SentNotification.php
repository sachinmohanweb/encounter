<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentNotification extends Model
{
    protected $table = 'sent_notifications';

    protected $fillable = [
        'user_id',
        'course_id',
        'batch_id',
        'type_id',
        'type',
        'date_sent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
