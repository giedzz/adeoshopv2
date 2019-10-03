<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    function getAllReviews(){
        return Review::all();
    }
    function submitReview(Request $request){
        $review = new Review();
        $id = $request->get('fk_product');
        $review->rating = $request->get('reviewRating');
        $review->review = $request->get('review');
        $review->fk_product = $id;

        $review->save();

        return redirect()->route('frontend.getGridItem', $id);
    }

    
}
