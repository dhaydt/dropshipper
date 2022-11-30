<?php

namespace App\Http\Controllers\Web;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Review;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function App\CPU\translate;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $image_array = [];
        if ($request->has('fileUpload')) {
            foreach ($request->file('fileUpload') as $image) {
                array_push($image_array, ImageManager::upload('review/', 'png', $image));
            }
        }

        if (auth('customer')->check()) {
            $review = new Review;
            $review->customer_id = auth('customer')->id();
            $review->product_id = $request->product_id;
            $review->comment = $request->comment;
            $review->rating = $request->rating;
            $review->attachment = json_encode($image_array);
            $review->save();
            Toastr::success(translate('successfully_added'));
            return redirect()->back();
        } else {
            Toastr::error(translate('login_first'));
            return redirect()->back();
        }
    }
}
