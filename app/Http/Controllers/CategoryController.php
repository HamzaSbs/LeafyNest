<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('plants')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category added.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $category = Category::find($id);
        if (!$category) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->category_id . ',category_id'],
        ]);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = Category::find($id);
        if (!$category) {
            abort(404);
        }

        if ($category->plants()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete a category that still has plants.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category removed.');
    }
}