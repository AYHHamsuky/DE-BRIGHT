<?php

namespace App\Models;

use Database\Factories\FeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fee extends Model
{
    /** @use HasFactory<FeeFactory> */
    use HasFactory;

    protected $fillable = ['name', 'session', 'course_id', 'amount', 'description', 'is_active'];

    protected $casts = ['amount' => 'integer', 'is_active' => 'boolean'];

    /** @return BelongsTo<Course, $this> */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /** @return HasMany<Payment, $this> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function label(): string
    {
        return "{$this->name} ({$this->session})";
    }
}
