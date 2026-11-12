<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['name', 'level_label', 'percentage', 'sort_order', 'is_visible'];
}
