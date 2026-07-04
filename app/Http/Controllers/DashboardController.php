<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'hrd'   => redirect()->route('hrd.dashboard'),
            default => redirect()->route('pegawai.dashboard'),
        };
    }
}
