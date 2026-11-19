<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id', 'fee_id', 'reference', 'amount', 'method', 'status', 'paid_at',
        'recorded_by', 'note', 'gateway_response',
    ];

    protected $casts = [
        'amount' => 'integer',
        'method' => PaymentMethod::class,
        'status' => PaymentStatus::class,
        'paid_at' => 'datetime',
        'gateway_response' => 'array',
    ];

    /** @return BelongsTo<Student, $this> */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /** @return BelongsTo<Fee, $this> */
    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    /** @return BelongsTo<User, $this> */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public static function newReference(string $prefix = 'DBC'): string
    {
        return $prefix.'-'.now()->format('ymd').'-'.Str::upper(Str::random(10));
    }

    public function isSuccessful(): bool
    {
        return $this->status === PaymentStatus::Successful;
    }

    /**
     * Apply a verified Paystack transaction to this payment.
     *
     * Safe to call more than once (callback and webhook can both arrive):
     * only a pending payment changes, and only when the amount and currency match.
     *
     * @param  array<string, mixed>  $transaction  The "data" object Paystack returns.
     */
    public function applyGatewayResult(array $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $payment = static::whereKey($this->getKey())->lockForUpdate()->first();

            if ($payment->status !== PaymentStatus::Pending) {
                return;
            }

            $succeeded = ($transaction['status'] ?? null) === 'success'
                && (int) ($transaction['amount'] ?? 0) === $payment->amount
                && strtoupper((string) ($transaction['currency'] ?? '')) === 'NGN';

            $payment->update([
                'status' => $succeeded ? PaymentStatus::Successful : PaymentStatus::Failed,
                'paid_at' => $succeeded ? ($transaction['paid_at'] ?? now()) : null,
                'gateway_response' => $transaction,
            ]);

            $this->setRawAttributes($payment->getAttributes(), true);
        });
    }
}
