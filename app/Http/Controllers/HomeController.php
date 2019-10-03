<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use Validator;
use App\Product;


class HomeController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = Product::all();
        return view('home', ['data' => $data]);
    }

    
    function getAllItems(){
        return Product::all();
    }

    function getdata()
    {
        $products = Product::all();
        return view('products.productList', compact('products'));
    }

    function postdata(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'sku' => 'required',
            'status' => 'required',
            'base_price' => 'required',
            'special_price' => 'required',
            'select_file' => 'image|mimes:jpeg,png,jpg,gif|max: 2048',
            'description' => 'required'
        ]);

        $error_array = array();
        $success_output = '';

        if ($validation->fails()) {
            foreach ($validation->messages()->getMessages() as $field_name => $messages) {
                $error_array[] = $messages;
            };
        } else {
            if ($request->get('button_action') == "insert") {
                $image = $request->file('select_file');
                if ($image != null) {
                    $new_name = time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images'), $new_name);
                } else {
                    $new_name = 'noimageavailable.png';
                }
                $product = new Product([
                    'name' => $request->get('name'),
                    'sku' => $request->get('sku'),
                    'status' => $request->get('status'),
                    'base_price' => $request->get('base_price'),
                    'special_price' => $request->get('special_price'),
                    'image' => $new_name,
                    'description' => $request->get('description')
                ]);
                $product->save();
                $success_output = '<div class="alert alert-success"> Item inserted </div>';
            }
            if ($request->get('button_action') == "update") {
                $product = Product::find($request->get('id'));
                $product->name = $request->get('name');
                $product->sku = $request->get('sku');
                $product->status = $request->get('status');
                $product->base_price = $request->get('base_price');
                $product->special_price = $request->get('special_price');
                $product->image = $product->image;
                $product->description = $request->get('description');
                $product->save();
                $success_output = '<div class="alert alert-success"> Item has been updated </div>';
            }
        }
        $output = array(
            'error' => $error_array,
            'success' => $success_output
        );
        echo json_encode($output);
    }

    function massRemove(Request $request)
    {
        $product_id_array = $request->input('id');
        $product = Product::whereIn('id', $product_id_array);
        $images = $product->pluck('image');

        foreach ($images as $image) {
            if ($image == 'noimageavailable.png') {
                if ($product->delete()) {
                    echo 'Data deleted';
                }
            } else {
                $image_path = "public/images/" . $image;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                if ($product->delete()) {
                    echo 'Data deleted';
                }
            }
        }
        // if($product->delete()){
        //     echo 'Data deleted';
        // }
    }
    function removeData(Request $request)
    {
        $product = Product::find($request->input('id'));
        if ($product->image == 'noimageavailable.png') {
            if ($product->delete()) {
                echo 'Data deleted';
            }
        } else {
            $image_path = "public/images/" . $product->image;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            if ($product->delete()) {
                echo 'Data deleted';
            }
        }
    }

    function fetchData(Request $request){
        $id = $request->input('id');
        $product = Product::find($id);
        $output = array(
            'name' => $product->name,
            'sku' => $product->sku,
            'status' => $product->status,
            'base_price' => $product->base_price,
            'special_price' => $product->special_price,
            'image' => $product->image,
            'description' => $product->description,
            'id' => $id
        );
        echo json_encode($output);
    }
}
