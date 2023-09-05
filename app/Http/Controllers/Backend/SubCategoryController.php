<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SubCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SubCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.sub-category.index', compact('dataTable'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories=  Category::where('status','1')->get();
        return view('admin.sub-category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category'=>'required',
            'name'=>'required|max:200|unique:sub_categories,name',
            'status'=>'required',
        ]);
        $subCategory= new SubCategory();
        $subCategory->category_id= $request->category;
        $subCategory->name= $request->name;
        $subCategory->status= $request->status;
        $subCategory->slug= Str::slug($request->name);
        $subCategory->save();
        toastr('Created Successfully','success');

        return redirect()->route('admin.sub-category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
        $categories=  Category::where('status','1')->get();
        return view('admin.sub-category.edit',compact('subCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        if ($request->has('switch_status')) {
            $subCategory->status = $request->switch_status;
            $subCategory->save();
            return response(['message' =>'Status has been updated!']);
        } else {
            $request->validate([
                'category'=>'required',
                'name'=>'required|max:200|unique:sub_categories,name, '.$subCategory->id,
                'status'=>'required',
            ]);
            $subCategory->category_id= $request->category;
            $subCategory->name = $request->name;
            $subCategory->slug = Str::slug($request->name);
            $subCategory->status = $request->status;
            $subCategory->save();
            toastr('Updated Successfully', 'success');
            return redirect()->route('admin.sub-category.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        toastr('Deleted Successfully');

        return redirect()->back();
    }
}
