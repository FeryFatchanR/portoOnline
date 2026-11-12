<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasDisplayOrder;

    protected $table = 'educations';

    protected $fillable = ['institution', 'degree', 'period', 'faculty', 'location', 'gpa', 'gpa_scale', 'sort_order', 'is_visible'];
}
