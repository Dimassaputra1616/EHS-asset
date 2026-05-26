<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::withCount(['assets', 'consumables']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('items_count', function($row) {
                    return $row->type === 'asset' ? $row->assets_count : $row->consumables_count;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('categories.edit', $row->id).'" class="btn btn-warning btn-sm fw-bold me-1">Edit</a>';
                    $btn .= '<form action="'.route('categories.destroy', $row->id).'" method="POST" class="d-inline">
                                '.csrf_field().method_field('DELETE').'
                                <button type="submit" class="btn btn-danger btn-sm fw-bold" onclick="return confirm(\'Are you sure?\')">Delete</button>
                            </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('categories.index');
    }

    public function items(Category $category)
    {
        if ($category->type === 'asset') {
            $items = $category->assets()->with(['location', 'supplier'])->get();
        } else {
            $items = $category->consumables()->with(['supplier'])->get();
        }

        return response()->json([
            'category' => $category,
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'type' => 'required|in:asset,consumable',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'type' => 'required|in:asset,consumable',
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
