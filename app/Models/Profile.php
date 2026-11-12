<?php

namespace App\Models;

use App\Models\Concerns\CleansUpFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Profil pemilik portfolio (hanya satu baris).
 */
class Profile extends Model
{
    use CleansUpFiles;

    /** @var list<string> */
    protected array $fileAttributes = ['photo_path', 'cv_path'];

    protected $fillable = [
        'logo_text', 'name', 'badge_text', 'subtitle', 'description', 'photo_path', 'cv_path',
        'email', 'phone', 'whatsapp_number', 'linkedin_url', 'address',
        'skills_subtitle', 'experience_subtitle', 'organization_subtitle', 'projects_subtitle',
        'education_subtitle', 'certification_subtitle',
        'contact_title', 'contact_subtitle',
    ];

    /** Ambil baris profil tunggal (dibuat kosong jika belum ada). */
    public static function current(): self
    {
        return static::query()->firstOrCreate([], ['name' => config('app.name')]);
    }

    /** Nomor telepon dalam format tautan tel: (hanya digit dan +). */
    public function phoneHref(): ?string
    {
        return $this->phone ? preg_replace('/[^0-9+]/', '', $this->phone) : null;
    }

    public function photoUrl(): ?string
    {
        return $this->photo_path ? Storage::disk('public')->url($this->photo_path) : null;
    }

    public function cvUrl(): ?string
    {
        return $this->cv_path ? Storage::disk('public')->url($this->cv_path) : null;
    }

    /** Nama dalam satu baris (kolom name boleh berisi baris baru). */
    public function singleLineName(): string
    {
        return trim(preg_replace('/\s+/', ' ', (string) $this->name));
    }
}
