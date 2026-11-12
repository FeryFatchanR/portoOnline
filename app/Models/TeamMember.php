<?php

namespace App\Models;

use App\Models\Concerns\CleansUpFiles;
use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    use CleansUpFiles, HasDisplayOrder;

    /** @var list<string> */
    protected array $fileAttributes = ['photo_path'];

    protected $fillable = ['name', 'position', 'bio', 'photo_path', 'linkedin_url', 'sort_order', 'is_visible'];

    public function photoUrl(): ?string
    {
        return $this->photo_path ? Storage::disk('public')->url($this->photo_path) : null;
    }
}
