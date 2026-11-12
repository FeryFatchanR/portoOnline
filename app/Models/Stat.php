<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['number', 'label', 'sort_order', 'is_visible'];
}
