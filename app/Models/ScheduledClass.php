<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledClass extends Model
{
    use HasFactory;

    protected $guarded = null;

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'bookings');
    }

    public function scopeNotBooked(Builder $query)
    {
        return $query->whereDoesntHave('students', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
    }

    public function scopeUpcoming(Builder $query)
    {
        return $query->where('date_time', '>', now());
    }

    public function scopePast(Builder $query)
    {
        return $query->where('date_time', '<', now());
    }
}
