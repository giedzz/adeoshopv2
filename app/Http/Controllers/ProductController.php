<?Php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Review;


class ProductController extends Controller
{
    public function index()
    {
        $data = Product::all();
        return view('products.index', ['data' => $data]);
    }
    public function gridItem($id)
    {
        // $id = $request->input('id');
        $product = Product::where('id', $id)->first();
        $reviews = Review::where('fk_product', $id)->get();
        $average_star = round($reviews->avg('rating'));
        if($product->status < 1){
            return redirect()->route('frontend');
        }
            return view('frontend.gridItem', compact('product', 'reviews', 'average_star'));
    }
    function getAllItems()
    {
        return Product::all();
    }

    public function frontend()
    {
        $products = Product::where('status', 1)->get();

        return view('frontend.gridList', compact('products'));
    }
}
