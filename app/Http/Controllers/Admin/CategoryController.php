<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\AdminsRole;

class CategoryController extends Controller
{
    public function categories() {
        Session::put('page', 'categories');
        $categories = Category::get();

        // Set Admin/Staff permission for Category management
        $categoryModuleCount = AdminsRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'category'])->count();
        $categoryModule = array();
        if (Auth::guard('admin')->user()->type == 'admin') {
            $categoryModule['view_access'] = 1;
            $categoryModule['edit_access'] = 1;
            $categoryModule['full_access'] = 1;
        } else if ($categoryModuleCount == 0) {
            $message = 'This module is restricted for you';
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $categoryModule = AdminsRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'category'])->first()->toArray();
        }

        return view('admin.categories.categories')->with(compact('categories', 'categoryModule'));
    }

    public function updateCategoryStatus(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            Category::where('id', $data['category_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'category_id' => $data['category_id']]);
        }
    }

    public function addCategory(Request $request) {
        Session::put('page', 'categories');
        $category = new Category();
        $message = 'Added category successfully';

        if ($request->isMethod('post')) {
            $data = $request->all();

            $categoryCount = Category::where('category_name', $data['category_name'])->count();
            if ($categoryCount > 0) {
                return redirect()->back()->with('error_message', 'Category already exists!');
            }
            
            $rules = [
                'category_name' => 'required|max:255',
            ];

            $customMessage = [
                'category_name.required' => 'Category name is required',
            ];

            $this->validate($request, $rules, $customMessage);

            $category->category_name = $data['category_name'];
            $category->save();
            return redirect('admin/categories')->with('success_message', $message);
        }
        return view('admin.categories.add_category');
    }

    // public function deleteCategory($id) {
    //     Category::where('id', $id)->delete();
    //     return redirect()->back()->with('success_message', 'Category is deleted!');
    // }
}
