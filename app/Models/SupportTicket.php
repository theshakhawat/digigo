<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SupportTicket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ticket_number',
        'user_id',
        'order_id',
        'department',
        'priority',
        'status',
        'subject',
        'message',
        'attachments',
        'last_reply_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'last_reply_at' => 'datetime',
        ];
    }

    /**
     * Generate unique ticket code like TCK-261022-XXXX.
     */
    public static function generateTicketNumber(): string
    {
        do {
            $number = 'TCK-'.date('ymd').'-'.strtoupper(Str::random(4));
        } while (static::where('ticket_number', $number)->exists());

        return $number;
    }

    /**
     * Ticket owner relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Related order relation.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Ticket conversation replies.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->oldest();
    }

    /**
     * Department readable label.
     */
    public function getDepartmentLabelAttribute(): string
    {
        return match ($this->department) {
            'refund' => 'Refund Request',
            'return' => 'Product Return',
            'technical' => 'Technical Support',
            default => 'General Inquiry',
        };
    }

    /**
     * Status readable badge classes.
     *
     * @return array{label: string, class: string}
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'open' => ['label' => 'Open', 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300 border-blue-200 dark:border-blue-800'],
            'in_progress' => ['label' => 'In Progress', 'class' => 'bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300 border-purple-200 dark:border-purple-800'],
            'answered' => ['label' => 'Answered', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'],
            'closed' => ['label' => 'Closed', 'class' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200'],
        };
    }

    /**
     * Priority badge classes.
     *
     * @return array{label: string, class: string}
     */
    public function getPriorityBadgeAttribute(): array
    {
        return match ($this->priority) {
            'urgent' => ['label' => 'Urgent', 'class' => 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300 border-rose-200 dark:border-rose-800'],
            'high' => ['label' => 'High', 'class' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border-amber-200 dark:border-amber-800'],
            'medium' => ['label' => 'Medium', 'class' => 'bg-sky-100 text-sky-700 dark:bg-sky-950 dark:text-sky-300 border-sky-200 dark:border-sky-800'],
            'low' => ['label' => 'Low', 'class' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border-slate-200 dark:border-slate-700'],
            default => ['label' => ucfirst($this->priority), 'class' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border-slate-200'],
        };
    }
}
