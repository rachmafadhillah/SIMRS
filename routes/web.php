<?php

use App\Livewire\Dashboard;
use App\Livewire\PatientManager;
use App\Livewire\PoliManager;
use App\Livewire\DoctorManager;
use App\Livewire\ScheduleManager;
use App\Livewire\RegistrationManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/patients', PatientManager::class)->name('patients');
Route::get('/polis', PoliManager::class)->name('polis');
Route::get('/doctors', DoctorManager::class)->name('doctors');
Route::get('/schedules', ScheduleManager::class)->name('schedules');
Route::get('/registrations', RegistrationManager::class)->name('registrations');