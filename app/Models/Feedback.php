<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes;

    protected $table = 'feedbacks';

    protected $fillable = [
        'feedbackable_id',
        'feedbackable_type',
        'user_id',
        'feedback',
        'fecha_feedback'
    ];

    protected $dates = [
        'fecha_feedback',
        'deleted_at'
    ];

    protected $casts = [
        'fecha_feedback' => 'datetime',
    ];

    // Relación polimórfica
    public function feedbackable()
    {
        return $this->morphTo();
    }

    // Relación con el usuario que creó el feedback
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}