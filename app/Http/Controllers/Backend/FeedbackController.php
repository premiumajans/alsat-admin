<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Helpers\CRUDHelper;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::all();
        return view('backend.feedback.index', get_defined_vars());
    }

    public function show(string $id)
    {
        $feedback = Feedback::find($id);
        return view('backend.feedback.show', get_defined_vars());
    }

    public function delete(string $id)
    {
        return CRUDHelper::remove_item('\App\Models\Feedback', $id);
    }
}
