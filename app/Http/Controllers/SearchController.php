<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //search
    function search(Request $request) {
        // $search_products = Product::Paginate(6);
        $data = $request->all();

        $sorting = 'created_at';
        $type = 'DESC';

        if(!empty($data['sort']) && $data['sort'] != '' && $data['sort'] != 'undefined') {
            if($data['sort'] == 1) {
                $sorting = 'product_name';
                $type = 'ASC';
            }
            else if($data['sort'] == 2) {
                $sorting = 'product_name';
                $type = 'DESC';
            }
            else if($data['sort'] == 3) {
                $sorting = 'after_discount';
                $type = 'ASC';
            }
            else if($data['sort'] == 4) {
                $sorting = 'after_discount';
                $type = 'DESC';
            }
        }
        
        $search_products = Product::where(function ($q) use ($data) {
            $min = 0;
            if(!empty($data['min']) && $data['min'] != '' && $data['min'] != 'undefined') {
                $min = $data['min'];
            } else {
                $min = 1;
            }
            if(!empty($data['q']) && $data['q'] != '' && $data['q'] != 'undefined') {
                $q->where(function ($q) use ($data) {
                    $q->where('product_name', 'like', '%' . $data['q'] . '%');
                    $q->OrWhere('long_desp', 'like', '%' . $data['q'] . '%');
                });
            }
            if(!empty($data['min']) && $data['min'] != '' && $data['min'] != 'undefined' || !empty($data['max']) && $data['max'] != '' && $data['max'] != 'undefined') {
                $q->whereBetween('after_discount', [$min, $data['max']]);
            } 
            if(!empty($data['category_id']) && $data['category_id'] != '' && $data['category_id'] != 'undefined') {
                $q->where('category_id', $data['category_id']);
            }
            if(!empty($data['brand_id']) && $data['brand_id'] != '' && $data['brand_id'] != 'undefined') {
                $q->where('product_brand', $data['brand_id']);
            }
            if(!empty($data['color_id']) && $data['color_id'] != '' && $data['color_id'] != 'undefined' || !empty($data['size_id']) && $data['size_id'] != '' && $data['size_id'] != 'undefined') {
                $q->whereHas('rel_to_inventories', function($q) use ($data) {
                    if(!empty($data['color_id']) && $data['color_id'] != '' && $data['color_id'] != 'undefined') {
                        $q->whereHas('rel_to_color', function($q) use ($data) {
                            $q->where("colors.id", $data['color_id']);
                        });
                    }
                    if(!empty($data['size_id']) && $data['size_id'] != '' && $data['size_id'] != 'undefined') {
                        $q->whereHas('rel_to_size', function($q) use ($data) {
                            $q->where("sizes.id", $data['size_id']);
                        });
                    }
                });
            } 
        })->orderBy($sorting, $type)->get();
        $categories = Category::all();
        $sizes = Size::all();
        $colors = Color::all();
        $brands = Brand::all();
        return view('frontend.search.search', [
            'search_products' => $search_products,
            'categories' => $categories,
            'sizes' => $sizes,
            'colors' => $colors,
            'brands' => $brands,
        ]);
    }
}
