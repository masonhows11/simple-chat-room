<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');


Route::get('/chat',\App\Livewire\ChatPage::class)->name('chatHome')->middleware('auth:web');

Route::get('/auth',\App\Livewire\RegisterPage::class)->name('login')->middleware('guest');
