<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.categories.index',compact('categories'));
    }


    public function create() {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:categories',
            'slug' => 'required|unique:categories',
            'description' => 'max:255',
            'icon' => 'max:5120|mimes:jpeg,jpg,gif,webp,png'
        ]);

        $validatedData['is_published'] = $request->is_published ? 1 : 0;


        if($request->hasFile('icon')) {
           $validatedData['icon'] = $request->file('icon')->store('categories/icons','public');
        }

        $created = Category::create($validatedData);

        if($created) {

            return redirect()->to('categories')->with([
                'success'=> 'category created successfully',
            ]);

        }

    }

    public function show(Category $category)
    {
        return response()->json(new CategoryResource($category));
    }


    public function edit(Category $category) {
        return view('admin.categories.edit',compact('category'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'slug' => 'required|unique:categories,slug,' . $category->id,
            'description' => 'max:255',
            'icon' => 'max:5120|mimes:jpeg,jpg,gif,webp,png'
        ]);

        $validatedData['is_published'] = $request->is_published ? 1 : 0;


        if($request->hasFile('icon')) {
            Storage::delete('public/' . $category->icon);
            $validatedData['icon'] = $request->file('icon')->store('categories/icons','public');
        }

        $updated = $category->update($validatedData);

        if($updated) {  return redirect()->to('categories')->with([
            'success'=> 'category updated successfully',
        ]);

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if($category->delete()) {
            Storage::delete('public/' . $category->icon);
            return redirect()->to('categories')->with([
                'success'=> 'category deleted successfully',
            ]);
        }
    }
}
