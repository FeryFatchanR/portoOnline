<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['name', 'icon', 'score', 'issuer', 'credential_url', 'sort_order', 'is_visible'];
}
