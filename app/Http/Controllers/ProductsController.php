<?php

namespace App\Http\Controllers;
use App;
use App\Http\Controllers\Controller;
use App\Models\Products;

use Illuminate\Http\Request;

class ProductsController extends Controller
{
    //
    public function index() {
        $products = Products::all();

        return view('products', compact('products'));
    }

    // To View Products Cart
    public function cart() {
        return view('products');
    }

    // To Add Products to Cart
    public function addToCart($id) {
       $product = Products::find($id);

       if(!$product) {
           abort(404);
       }

       $cart = session()->get('cart');

       if(!$cart) {
            $cart = [
                $id => [
                    "prod_name" => $product->prod_name,
                    "qty" => 1,
                    "prod_price" => $product->prod_price,
                    "prod_img" => $product->prod_img
                ]
            ];

        session()->put('cart', $cart);

        return redirect()->back()->with('Success', 'Product added to cart successfully');
       }

       if(isset($cart[$id])) {

           $cart[$id]['qty']++;

           session()->put('cart', $cart);

           return redirect()->back()->with('Success', 'Product added to cart successfully');
       }

        $cart[$id] = [
            "prod_name" => $product->prod_name,
            "qty" => 1,
            "prod_price" => $product->prod_price,
            "prod_img" => $product->prod_img
        ];

        session()->put('cart', $cart);

        return redirect()->back()->with('Success', 'Product added to cart successfully');
    }

    public function update(Request $request) {
        if($request->id and $request->qty) {
            $cart = session()->get('cart');

            $cart[$request->id]["qty"] = $request->qty;

            session()->put('cart', $cart);

            session()->flash('Success', "Cart updated successfully");
        }
    }

    public function remove(Request $request) {
        if($request->id) {
            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            session()->flash('Success', "Cart removed successfully");
        }
    }

}
