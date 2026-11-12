<?php

namespace App\Models;

use App\Models\Concerns\CleansUpFiles;
use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use CleansUpFiles, HasDisplayOrder;

    /** @var list<string> */
    protected array $fileAttributes = ['image_path'];

    protected $fillable = [
        'title', 'role', 'period', 'summary', 'description', 'tech_stack',
        'image_path', 'url', 'repo_url', 'sort_order', 'is_visible',
    ];

    protected function casts(): array
    {
        return ['tech_stack' => 'array'];
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }
}
