<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index( Request $request )
    {
        $query_param = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $categories = Category::where(['position'=>1])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $categories=Category::where(['position'=>1]);
        }
        $categories = $categories->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.category.sub-category-view',compact('categories','search'));
    }

    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->slug = Str::slug($request->name[array_search('en', $request->lang)]);
        $category->parent_id = $request->parent_id;
        $category->position = 1;
        $category->save();

        foreach($request->lang as $index=>$key)
        {
            if($request->name[$index] && $key != 'en')
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Model\Category',
                        'translationable_id'    => $category->id,
                        'locale'                => $key,
                        'key'                   => 'name'],
                    ['value'                 => $request->name[$index]]
                );
            }
        }
        Toastr::success('Category updated successfully!');
        return back();
    }

    public function edit(Request $request)
    {
        $data = Category::where('id', $request->id)->first();

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->parent_id = $request->parent_id;
        $category->position = 1;
        $category->save();
        return response()->json();
    }

    public function delete(Request $request)
    {
        $categories = Category::where('parent_id', $request->id)->get();
        if (!empty($categories)) {

            foreach ($categories as $category) {
                $translation = Translation::where('translationable_type','App\Model\Category')
                                    ->where('translationable_id',$category->id);
                $translation->delete();
                Category::destroy($category->id);
            }
        }
        $translation = Translation::where('translationable_type','App\Model\Category')
                                    ->where('translationable_id',$request->id);
        $translation->delete();
        Category::destroy($request->id);
        return response()->json();
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::where('position', 1)->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }
}
