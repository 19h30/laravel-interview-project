<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function unit() {
        return $this->belongsTo('App\Models\Unit', 'unit_id');
    }

    public function invoices()
    {
        return $this->belongsToMany('App\Models\Invoice', 'invoices_details');
    }

    public static function getActiveProducts() {
        return Product::where('status', 1)->get()->toArray();
    }
}