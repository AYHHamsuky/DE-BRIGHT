<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'course_id', 'message', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /** Phone number in international format for a wa.me link (Nigerian numbers assumed). */
    public function whatsappNumber(): string
    {
        $digits = preg_replace('/\D/', '', $this->phone);

        if (str_starts_with($digits, '0')) {
            return '234'.substr($digits, 1);
        }

        return $digits;
    }
}
