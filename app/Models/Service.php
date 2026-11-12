<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasDisplayOrder;

    /** Ikon yang tersedia (kunci => label di admin). SVG-nya ada di components/service-icon. */
    public const ICONS = [
        'code' => 'Website & aplikasi',
        'network' => 'Jaringan',
        'shield' => 'Keamanan',
        'server' => 'Server & cloud',
        'design' => 'Desain',
        'support' => 'Dukungan teknis',
    ];

    protected $fillable = ['title', 'icon', 'description', 'features', 'sort_order', 'is_visible'];

    protected function casts(): array
    {
        return ['features' => 'array'];
    }
}
