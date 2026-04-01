<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('user:promote-admin {email}', function (string $email) {
    $user = User::query()->where('email', $email)->first();

    if (! $user) {
        $this->error('Utilisateur introuvable.');

        return 1;
    }

    if ($user->is_admin) {
        $this->info('Déjà admin.');

        return 0;
    }

    $user->forceFill(['is_admin' => true])->save();

    $this->info('OK: admin activé.');

    return 0;
})->purpose('Promouvoir un utilisateur en administrateur');
