<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\AdminsRole;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function invoices() {
        Session::put('page', 'invoices');
        $invoices = Invoice::with(['products'])->get()->toArray();

        foreach ($invoices as &$invoice) {
            $invoice['products_purchased'] = count($invoice['products']);
        }

        // dd($invoices);
        // $invoices = Invoice::find(1)->products()->with(['category', 'unit', 'invoices'])->get()->toArray();
        // dd($invoices);

        // Set Admin/Staff permission for Invoice management
        $invoiceModuleCount = AdminsRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'invoice'])->count();
        $invoiceModule = array();
        if (Auth::guard('admin')->user()->type == 'admin') {
            $invoiceModule['view_access'] = 1;
            $invoiceModule['edit_access'] = 1;
            $invoiceModule['full_access'] = 1;
        } else if ($invoiceModuleCount == 0) {
            $message = 'This module is restricted for you';
            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $invoiceModule = AdminsRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'invoice'])->first()->toArray();
        }

        return view('admin.invoices.invoices')->with(compact('invoices', 'invoiceModule'));
    }

    public function viewInvoice($id) {
        $invoice = Invoice::with(['products'])->find($id);
        return view('admin.invoices.view_invoice')->with(compact('invoice'));
    }

    public function editInvoice(Request $request, $id = null) {
        Session::put('page', 'invoices');
        if ($id == '') {
            $title = 'Add Invoice';
            $invoice = new Invoice();
            $message = 'Added invoice successfully';
        } else {
            $title = 'Edit Invoice';
            $invoice = Invoice::with(['products'])->find($id);
            $message = 'Updated invoice successfully';
        }

        $getProducts = Product::getActiveProducts();

        if ($request->isMethod('post')) {
            $data = $request->all();

            // $invoiceCount = Invoice::where('invoice_name', $data['invoice_name'])->count();
            // if ($invoiceCount > 0) {
            //     return redirect()->back()->with('error_message', 'Invoice already exists!');
            // }
            
            $rules = [
                'customer_name' => 'required|max:255',
                'product.*' => 'required',
                'quantity.*' => 'required|numeric',
            ];

            $customMessage = [
                'customer_name.required' => 'Customer name is required',
            ];

            $this->validate($request, $rules, $customMessage);

            // edit
            if ($id != '') {
                foreach ($data['product'] as $key => $value) {
                    InvoiceDetail::where(['invoice_id' => $id, 'product_id'=> $value])->delete();
                }
            }

            // format invoice details
            $invoiceDetails = [];
            $ids = array_column($getProducts, 'id');
            foreach ($data['product'] as $key => $value) {
                $foundKey = array_search($value, $ids);
                if ($foundKey === false) {
                    continue;
                }
                $qty = $data['quantity'][$key];
                $amount = $qty * $getProducts[$foundKey]['price'];
                if (isset($invoiceDetails[$value])) {
                    $invoiceDetails[$value]['quantity'] += $qty;
                    $invoiceDetails[$value]['amount'] += $amount;
                } else {
                    $invoiceDetails[$value] = ['quantity' => $qty, 'amount' => $amount];
                }
            }
            // calculate invoice total amount
            $totalAmount = 0;
            foreach ($invoiceDetails as $productId => $info) {
                $totalAmount += $info['amount'];
            }

            $invoice->customer_name = $data['customer_name'];
            $invoice->total_amount = $totalAmount;
            $invoice->save();

            foreach ($invoiceDetails as $prodId => $detail) {
                $invoiceDetail = new InvoiceDetail();
                $invoiceDetail->invoice_id = $invoice->id;
                $invoiceDetail->product_id = $prodId;
                $invoiceDetail->quantity = $detail['quantity'];
                $invoiceDetail->amount = $detail['amount'];
                $invoiceDetail->save();
            }

            return redirect('admin/invoices')->with('success_message', $message);
        }

        return view('admin.invoices.add_edit_invoice')->with(compact('getProducts', 'invoice', 'title'));
    }

    public function deleteInvoice($id) {
        Invoice::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Invoice is deleted!');
    }
}
