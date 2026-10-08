<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('v1/posts', fn (): string => 'posts')->name('better-laravel-fixture.posts');
