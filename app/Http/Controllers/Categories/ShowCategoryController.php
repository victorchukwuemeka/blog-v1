<?php

namespace App\Http\Controllers\Categories;

use App\Models\Category;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ShowCategoryController extends Controller
{
    public function __invoke(Category $category) : View
    {
        return view('categories.show', compact('category') + [
            'posts' => $category
                ->posts()
                ->withCount('comments')
                ->latest('published_at')
                ->published()
                ->paginate(24),
        ]);
    }
}
