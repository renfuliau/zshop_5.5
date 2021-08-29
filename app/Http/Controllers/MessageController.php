<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function messageStore(Request $request)
    {
        // dd($request);

        $this->validate($request, [
            'name' => 'string|required|min:2',
            'email' => 'email|required',
            'message' => 'required|min:10|max:200'
        ]);
        // dd($request);
        $message = new Message;
        $message->name = $request->name;
        $message->email = $request->email;
        $message->message = $request->message;
        $check = $message->save();
        if ($check) {
            request()->session()->flash('success', __('frontend.response-success'));
            return redirect()->route('index');
        } else {
            request()->session()->flash('error', __('frontend.response-error'));
            return back();
        }
    }
}
