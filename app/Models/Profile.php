<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Profil pemilik portfolio (hanya satu baris).
 */
class Profile extends Model
{
    protected $fillable = [
        'logo_text', 'name', 'badge_text', 'subtitle', 'description',
        'email', 'phone', 'whatsapp_number', 'linkedin_url', 'address',
        'skills_subtitle', 'experience_subtitle', 'organization_subtitle',
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
}
