<?php

namespace App\Modules\Sample\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;

class SampleController extends Controller
{
    public function index()
    {
        return Inertia::render('Sample::Index');
    }
}
