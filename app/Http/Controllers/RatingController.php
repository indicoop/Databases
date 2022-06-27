<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'user_id' => 'required|integer|exists:users,id',
            'rating_value' => 'required|integer',
        ]);

        try {
            $rating = new Rating();
            $rating->product_id = $request->product_id;
            $rating->user_id = $request->user_id;
            $rating->rating_value = $request->rating_value;
            $rating->save();

            return response()->json([
                'success' => true,
                'message' => 'Rating created successfully',
                'data' => $rating,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rating creation failed',
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating_value' => 'required|integer',
        ]);

        try {
            $rating = Rating::findOrFail($id);
            $rating->rating_value = $request->rating_value;
            $rating->save();

            return response()->json([
                'success' => true,
                'message' => 'Rating updated successfully',
                'data' => $rating,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rating update failed',
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ]);
        }
    }
}
