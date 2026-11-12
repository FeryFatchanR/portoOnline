<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Item portfolio yang dapat diurutkan (sort_order) dan disembunyikan (is_visible).
 */
trait HasDisplayOrder
{
    /** Item baru otomatis diletakkan di urutan paling akhir. */
    public static function bootHasDisplayOrder(): void
    {
        static::creating(function ($model) {
            if (empty($model->sort_order)) {
                $model->sort_order = (int) static::query()->max('sort_order') + 1;
            }
        });
    }

    public function initializeHasDisplayOrder(): void
    {
        $this->mergeCasts([
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ]);
    }

    /** Hanya item yang tampil di halaman publik, urut sesuai sort_order. */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_visible', true)->orderBy('sort_order')->orderBy('id');
    }
}
