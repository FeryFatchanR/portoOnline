<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['title', 'organization', 'period', 'description', 'sort_order', 'is_visible'];
}
