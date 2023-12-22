<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hotels = Hotel::query()
                       ->orderBy('updated_at', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->where('is_available', 1)
                       ->get();

        return view('user.dashboard', compact('hotels'));
    }


}
