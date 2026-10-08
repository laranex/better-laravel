<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('blogs', fn (): string => 'blogs')->name('better-laravel-fixture.blogs');
