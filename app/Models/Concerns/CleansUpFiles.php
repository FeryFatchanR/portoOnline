<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Hapus file lama di disk "public" saat diganti/dikosongkan atau saat record dihapus.
 * Model mendefinisikan: protected array $fileAttributes = ['photo_path', ...];
 */
trait CleansUpFiles
{
    public static function bootCleansUpFiles(): void
    {
        static::updated(function ($model) {
            foreach ($model->fileAttributes as $attribute) {
                $old = $model->getOriginal($attribute);

                if ($model->wasChanged($attribute) && filled($old)) {
                    Storage::disk('public')->delete($old);
                }
            }
        });

        static::deleted(function ($model) {
            foreach ($model->fileAttributes as $attribute) {
                if (filled($model->{$attribute})) {
                    Storage::disk('public')->delete($model->{$attribute});
                }
            }
        });
    }
}
