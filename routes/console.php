<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:user', function () {
    tap((new User)->forceFill([
        'name' => $this->ask('Name'),
        'email' => $this->ask('Email Address'),
        'password' => Hash::make($this->secret('Password')),
        'email_verified_at' => Date::now(),
        'role' => 0,
    ]))->save();

    $this->info('User created successfully.');
})->purpose('Create a new user');