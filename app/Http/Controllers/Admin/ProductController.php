<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\AdminsRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;

class ProductController extends Controller
{
    public function products() {
        Session::put('page', 'products');
        $products = Product::with(['category', 'unit'])->get()->toArray();

        // Set Admin/Staff permission for Product management
        $productModuleCount = AdminsRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'product'])->count();
        $productModule = array();
        if (Auth::guard('admin')->user()->type == 'admin') {
            $productModule['view_access'] = 1;
            $productModule['edit_access'] = 1;
            $productModule['full_access'] = 1;
        } else if ($productModuleCount == 0) {
            $message = 'This module is restricted for you';
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $productModule = AdminsRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'product'])->first()->toArray();
        }

        return view('admin.products.products')->with(compact('products', 'productModule'));
    }

    public function updateProductStatus(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            Product::where('id', $data['product_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'product_id' => $data['product_id']]);
        }
    }

    public function addProduct(Request $request) {
        Session::put('page', 'products');
        $product = new Product();
        $message = 'Added product successfully';
        $getCategories = Category::getActiveCategories();
        $getUnits = Unit::getUnits();

        if ($request->isMethod('post')) {
            $data = $request->all();

            $productCount = Product::where('product_name', $data['product_name'])->count();
            if ($productCount > 0) {
                return redirect()->back()->with('error_message', 'Product already exists!');
            }
            
            $rules = [
                'product_name' => 'required|max:255',
            ];

            $customMessage = [
                'product_name.required' => 'Product name is required',
            ];

            $this->validate($request, $rules, $customMessage);

            $product->product_name = $data['product_name'];
            $product->unit_id = $data['unit_id'];
            $product->price = $data['price'];
            $product->category_id = $data['category_id'];
            $product->save();
            return redirect('admin/products')->with('success_message', $message);
        }
        return view('admin.products.add_product')->with(compact('getCategories', 'getUnits'));
    }
}
