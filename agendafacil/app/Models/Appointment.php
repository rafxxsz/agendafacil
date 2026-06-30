<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'professional_id',
        'start_at',
        'end_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    /**
     * Agendamentos ativos (não cancelados) que se sobrepõem ao intervalo
     * informado para um mesmo profissional. Dois intervalos colidem quando
     * start_at < fim_novo E end_at > inicio_novo.
     */
    public function scopeConflicting(Builder $query, int $professionalId, $start, $end): Builder
    {
        return $query
            ->where('professional_id', $professionalId)
            ->where('status', '!=', 'cancelado')
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start);
    }

    public function scopeFuture(Builder $query): Builder
    {
        return $query->where('start_at', '>', now());
    }

    public function isCancellable(): bool
    {
        return $this->status === 'agendado' && $this->start_at->isFuture();
    }
}
