<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->orderBy('name')->get();

        return view('Categories.index', compact('categories'));
    }

    public function show(Category $category){
        $posts = $category->posts()->with('user')->published()->latest('published_at')->paginate(10);

        return view('Categories.show', compact('category', 'posts'));
    }
}
