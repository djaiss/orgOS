<?php

declare(strict_types=1);

namespace App\Http\Controllers\Marketing\Docs;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminlandOfficeController extends Controller
{
    public function index(): View
    {
        return view('marketing.docs.features.offices.index');
    }
}
