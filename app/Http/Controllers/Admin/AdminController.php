<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminsRole;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard() {
        Session::put('page', 'dashboard');
        $invoiceCount = Invoice::count();
        $staffCount = Admin::getActiveStaffs()->count();
        $categoryCount = Category::getActiveCategories()->count();
        $productCount = count(Product::getActiveProducts());
        return view('admin.dashboard')->with(compact('invoiceCount', 'staffCount', 'categoryCount', 'productCount'));
    }

    public function login(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required|max:30',
            ];

            $customMessage = [
                'email.required' => 'Email is required',
                'email.email' => 'Valid email is required',
                'password.required' => 'Password is required',
            ];

            $this->validate($request, $rules, $customMessage);

            if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
                if (isset($data['remember']) && !empty($data['remember'])) {
                    setcookie('email', $data['email'], time() + 3600);
                    setcookie('password', $data['password'], time() + 3600);
                } else {
                    setcookie('email', '');
                    setcookie('password', '');
                }
                return redirect('admin/dashboard');
            } else {
                return redirect()->back()->with('error_message', 'Invalid Email or Password!');
            }
        }
        if(Auth::guard('admin')->check()){
            return redirect('admin/dashboard');
        }
        return view('admin.login');
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }

    public function updatePassword(Request $request) {
        Session::put('page', 'update-password');
        if ($request->isMethod('post')) {
            $data = $request->all();
            if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
                if ($data['new_pwd'] == $data['confirm_pwd']) {
                    Admin::where('id', Auth::guard('admin')->user()->id)->update(['password' => bcrypt($data['new_pwd'])]);
                    return redirect()->back()->with('success_message', 'Password has been updated successfully!');
                } else {
                    return redirect()->back()->with('error_message', 'New password and confirm password not match!');
                }
            } else {
                return redirect()->back()->with('error_message', 'Your current password is incorrect!');
            }
        }
        return view('admin.update_password');
    }

    public function updateDetails(Request $request) {
        Session::put('page', 'update-details');
        if ($request->isMethod('post')) {
            $data = $request->all();

            $rules = [
                'admin_name' => 'required|max:255',
                'admin_mobile' => 'required|numeric|digits_between:10,12',
                'admin_image' => 'image',
            ];

            $customMessage = [
                'admin_name.required' => 'Name is required',
                'admin_mobile.required' => 'Mobile is required',
                'admin_mobile.numeric' => 'Valid mobile is required',
                'admin_image.image' => 'Image is not valid',
            ];

            $this->validate($request, $rules, $customMessage);

            if ($request->hasFile('admin_image')) {
                $imageName = time() . '.' . $request->file('admin_image')->extension();
                $request->file('admin_image')->move(public_path('admin/images'), $imageName);
            } else if (!empty($data['current_image'])) {
                $imageName = $data['current_image'];
            } else {
                $imageName = '';
            }

            Admin::where('email', Auth::guard('admin')->user()->email)->update(['name' => $data['admin_name'], 'mobile' => $data['admin_mobile'],
                'image' => $imageName]);
            return redirect()->back()->with('success_message', 'Admin details has been updated successfully!');
        }
        return view('admin.update_details');
    }

    public function staffs() {
        Session::put('page', 'staffs');
        $staffs = Admin::where('type', 'staff')->get();

        // Set Admin/Staff permission for Staff management
        $staffModule = array();
        $permission = $this->checkPermission();
        if (isset($permission['success']) && $permission['success'] == false) {
            $message = $permission['message'];
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $staffModule = $permission['module'];
        }

        return view('admin.staffs.staffs')->with(compact('staffs', 'staffModule'));
    }

    // simple permission check, using middleware later
    private function checkPermission() {
        $success = false;
        $message = '';
        $moduleCount = AdminsRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'staff'])->count();
        $module = array();
        if (Auth::guard('admin')->user()->type == 'admin') {
            $module['view_access'] = 1;
            $module['edit_access'] = 1;
            $module['full_access'] = 1;
            $success = true;
        } else if ($moduleCount == 0) {
            $message = 'This module is restricted for you';
        } else {
            $module = AdminsRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'staff'])->first()->toArray();
            $success = true;
        }
        return ['success' => $success, 'module' => $module, 'message' => $message];
    }

    public function updateStaffStatus(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            Admin::where('id', $data['staff_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'staff_id' => $data['staff_id']]);
        }
    }

    public function editStaff(Request $request, $id = null) {
        Session::put('page', 'staffs');
        if ($id == '') {
            $title = 'Add Staff';
            $staff = new Admin();
            $message = 'Added staff successfully';
        } else {
            $title = 'Edit Staff';
            $staff = Admin::find($id);
            $message = 'Updated staff successfully';
        }

        if ($request->isMethod('post')) {
            $data = $request->all();

            $rules = [
                'name' => 'required|max:255',
                'mobile' => 'required|numeric|digits_between:10,12',
                'image' => 'image',
            ];

            $customMessage = [
                'name.required' => 'Name is required',
                'mobile.required' => 'Mobile is required',
                'mobile.numeric' => 'Valid mobile is required',
                'image.image' => 'Image is not valid',
            ];

            if ($id == '') {
                $staffCount = Admin::where('email', $data['email'])->count();
                if ($staffCount > 0) {
                    return redirect()->back()->with('error_message', 'Staff already exists!');
                }

                $newRules = [
                    'email' => 'required|email|max:255',
                    'password' => 'required|max:30',
                ];
    
                $newCustomMessage = [
                    'email.required' => 'Email is required',
                    'email.email' => 'Valid email is required',
                    'password.required' => 'Password is required',
                ];

                $rules = array_merge($rules, $newRules);
                $customMessage = array_merge($customMessage, $newCustomMessage);
            }

            $this->validate($request, $rules, $customMessage);

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->file('image')->extension();
                $request->file('image')->move(public_path('admin/images'), $imageName);
            } else if (!empty($data['current_image'])) {
                $imageName = $data['current_image'];
            } else {
                $imageName = '';
            }

            $staff->name = $data['name'];
            $staff->type = 'staff';
            $staff->mobile = $data['mobile'];
            if ($id == '') {
                $password = bcrypt($data['password']);
                $staff->email = $data['email'];
                $staff->password = $password;
            }
            $staff->image = $imageName;
            $staff->save();
            return redirect('admin/staffs')->with('success_message', $message);
        }
        return view('admin.staffs.add_edit_staff')->with(compact('title', 'staff'));
    }

    public function deleteStaff($id) {
        Admin::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Staff is deleted!');
    }

    public function updateRole($id, Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->all();

            // delete previous roles
            AdminsRole::where('admin_id', $id)->delete();
            // add new roles
            $modules = [
                'staff',
                'category',
                'product',
                'invoice',
            ];
            foreach ($data as $key => $value) {
                if (in_array($key, $modules)) {
                    if (isset($value['view'])) {
                        $view = $value['view'];
                    } else {
                        $view = 0;
                    }
                    if (isset($value['edit'])) {
                        $edit = $value['edit'];
                    } else {
                        $edit = 0;
                    }
                    if (isset($value['full'])) {
                        $full = $value['full'];
                    } else {
                        $full = 0;
                    }
                    $role = new AdminsRole();
                    $role->admin_id = $id;
                    $role->module = $key;
                    $role->view_access = $view;
                    $role->edit_access = $edit;
                    $role->full_access = $full;
                    $role->save();
                }
            }

            $message = 'Staff roles updated successfully';
            return redirect()->back()->with('success_message', $message);
        }

        $staffRoles = AdminsRole::where('admin_id', $id)->get()->toArray();
        $staffDetails = Admin::where('id', $id)->first()->toArray();
        $title = 'Update ' . $staffDetails['name'] . ' Role/Permission';

        return view('admin.staffs.update_roles')->with(compact('title', 'id', 'staffRoles'));
    }

    public function index() {
        // Set Admin/Staff permission for Category
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
    }
}
