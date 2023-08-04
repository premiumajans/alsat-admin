<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'feedback' => 'required',
                'category' => 'required',
                'advert_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }
            $id = $request->advert_id;
            $advert = Advert::find($id);
            $feedback = new Feedback();
            $feedback->feedback = $request->feedback;
            $feedback->category_id = $request->category;
            $advert->feedback()->save($feedback);
            return response()->json([
                'message' => 'feedback-sent-successfully',
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
