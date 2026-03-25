<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'subject',
        'description',
        'priority',
        'status',
        'user_id',
        'category_id',
        'assigned_to'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function getSlaAttribute()
    {
        $hours = ['Urgent' => 1, 'High' => 2, 'Normal' => 4, 'Low' => 8][$this->priority] ?? 4;
        $target = $this->created_at->addHours($hours);

        if ($this->status === 'Resolved') {
            return ['status' => 'Met', 'message' => 'SLA Met'];
        }

        $diff = now()->diffInMinutes($target, false);

        if ($diff < 0) {
            return ['status' => 'Breached', 'message' => 'Overdue by ' . abs(round($diff / 60, 1)) . 'h'];
        }

        return ['status' => 'Active', 'message' => round($diff / 60, 1) . 'h remaining'];
    }

    protected $appends = ['sla'];
}
