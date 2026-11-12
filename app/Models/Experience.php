<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['title', 'company', 'location', 'period', 'description', 'sort_order', 'is_visible'];
}
