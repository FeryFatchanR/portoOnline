<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('app:create-admin {--email= : Email admin} {--name= : Nama admin}')]
#[Description('Buat akun admin baru, atau jadikan user yang sudah ada sebagai admin')]
class CreateAdmin extends Command
{
    public function handle(): int
    {
        $email = $this->option('email') ?: text('Email admin', required: true);

        if (Validator::make(['email' => $email], ['email' => 'required|email|max:255'])->fails()) {
            $this->error('Format email tidak valid.');

            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->forceFill(['is_admin' => true])->save();
            $this->info("User {$email} sekarang memiliki akses admin.");

            return self::SUCCESS;
        }

        $name = $this->option('name') ?: text('Nama', required: true);
        $plain = password('Password (min. 12 karakter)', required: true, validate: fn (string $v) => strlen($v) < 12 ? 'Minimal 12 karakter.' : null);

        $user = new User(['name' => $name, 'email' => $email, 'password' => $plain]);
        $user->forceFill(['is_admin' => true, 'email_verified_at' => now()])->save();

        $this->info("Admin {$email} berhasil dibuat. Login di ".url('/admin'));

        return self::SUCCESS;
    }
}
