<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = ['title', 'image', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function imageUrl(): string
    {
        return asset($this->image);
    }
}
