<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetLog extends Model
{
    use HasFactory;

    protected $table = 'password_reset_logs';

    protected $fillable = [
        'admin_id',
        'user_id',
        'email',
        'new_password',
        'notes',
        'ip_address',
        'email_sent'
    ];

    protected $casts = [
        'email_sent' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship with admin who performed the reset
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relationship with user whose password was reset
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for logs by admin
     */
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Scope for logs by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get formatted created at
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Get email status badge
     */
    public function getEmailStatusBadgeAttribute()
    {
        if ($this->email_sent) {
            return '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Sent</span>';
        }
        return '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Not Sent</span>';
    }

    /**
     * Check if email was sent
     */
    public function wasEmailSent()
    {
        return $this->email_sent;
    }

    /**
     * Mark email as sent
     */
    public function markEmailAsSent()
    {
        $this->email_sent = true;
        return $this->save();
    }
}