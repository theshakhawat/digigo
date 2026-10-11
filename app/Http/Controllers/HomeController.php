<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application homepage with dynamic banners and featured categories.
     */
    public function index(): View
    {
        $banners = Banner::active()->orderBy('order', 'asc')->get();
        $featuredCategories = Category::active()->where('is_featured', true)->take(10)->get();

        return view('homepage', compact('banners', 'featuredCategories'));
    }
}
