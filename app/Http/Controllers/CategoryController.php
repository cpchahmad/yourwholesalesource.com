<?php

namespace App\Http\Controllers;

use App\Category;
use App\SubCategory;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function index()
    {

        $categories = Category::latest()->get();
        return view('setttings.category.create')->with([
            'categories' => $categories
        ]);
    }

    public function save(Request $request)
    {
//        dd($request);
        if (Category::where('title', $request->cat_title)->exists()) {
            $category = Category::where('title', $request->cat_title)->first();
        } else {
            $category = new Category();
        }
        $category->title = $request->cat_title;
        $category->save();
        return redirect()->back()->with('success','Category created successfully!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if ($request->hasFile('icon')) {
            $image =  $request->file('icon');
            $destinationPath = 'categories-icons/';
            $filename = now()->format('YmdHi') . str_replace([' ','(',')'], '-', $image->getClientOriginalName());
            $image->move($destinationPath, $filename);
            $category->icon = $filename;
        }
        $category->title = $request->title;
        $category->save();
        return redirect()->back()->with('success','Category updated successfully!');
    }

    public function delete($id)
    {
        $category = Category::find($id);
        $category->delete();
        $subcategories = SubCategory::where('category_id', $id)->get();
        foreach ($subcategories as $subcategory) {
            $subcategory->delete();
        }
        return redirect()->back()->with('error','Category Deleted!');
    }

    public function subsave(Request $request)
    {
        foreach ($request->sub_title as $sub) {
            if (!empty($sub)) {
                $subcategory = new SubCategory();
                $subcategory->title = $sub;
                $subcategory->category_id = $request->category_id;
                $subcategory->save();
            }
        }
        return redirect()->back()->with('success','Sub Category created successfully!');
    }

    public function subupdate(Request $request, $id)
    {
        $category = SubCategory::find($id);
        $category->title = $request->title;
        $category->save();
        return redirect()->back()->with('success','Sub Category updated successfully!');
    }

    public function subdelete($id)
    {
        $category = SubCategory::find($id);
        $category->delete();
        return redirect()->back()->with('error','Deleted!');
    }
}
