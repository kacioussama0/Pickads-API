<?php

namespace App\Http\Controllers\Api;

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
        $categories = Category::where('is_published',1)->latest()->get();

        return response()->json($categories,200);
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
            return response()->json([
                'message'=> 'category created successfully',
                'category'=> $created
            ],200);
        }

        return response()->json(['message'=> 'server error'],500);
    }

    public function show(Category $category)
    {
        return response()->json(new CategoryResource($category));
    }

    public function showUsers(Category $category)
    {
        $category->load('users');
        return response()->json(new CategoryResource($category));
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

        if($updated) {
            return response()->json([
                'message'=> 'category updated successfully',
            ],200);
        }

        return response()->json(['message'=> 'server error'],500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if($category->delete()) {
            Storage::delete('public/' . $category->icon);
            return response()->json([
                'message'=> 'category deleted successfully',
            ],200);
        }

        return response()->json(['message'=> 'server error'],500);
    }
}
