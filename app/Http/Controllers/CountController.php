<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Count;

class CountController extends Controller
{
    function create(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:2', 'max:50'],
        ]);

        $user_id = Auth::id();
        $title = $request->title;
        $order_number = Count::where('user_id', $user_id)->count();

        $count = Count::create([
            'user_id' => $user_id,
            'order' => $order_number + 1,
            'title' => $title,
            'number' => 0,
        ]);

        return response()->json($count->id, 200);
    }

    function update(Request $request)
    {
        $validated = $request->validate([
            'count_id' => ['integer'],
            'number' => ['integer']
        ]);

        $user_id = Auth::id();
        $count_id = $request->count_id;
        $number = $request->number;

        $count = Count::where('id', $count_id)->where('user_id', $user_id)->first();

        if (!isset($count)) {
            return response()->json('', 500);
        }

        $count->number = $number;
        $count->save();

        return response()->json('', 200);
    }

    function delete(Request $request)
    {
        $validated = $request->validate([
            'count_id' => ['integer'],
        ]);

        $user_id = Auth::id();
        $count_id = $request->count_id;

        Count::where('id', $count_id)->where('user_id', $user_id)->delete();

        return response()->json('', 200);
    }

    function list()
    {
        $user_id = Auth::id();
        $count = Count::where('user_id', $user_id)->orderBy('order', 'desc')->get();

        return response()->json($count, 200);
    }

    function view($id)
    {
        $user_id = Auth::id();

        $count = Count::where('id', $id)->where('user_id', $user_id)->first();

        if (!isset($count)) {
            return response()->json('', 500);
        }

        return response()->json($count, 200);
    }

    function order(Request $request)
    {
        $user_id = Auth::id();
        $list = json_decode($request->list);
        $order_number = 0;

        $order_number = Count::where('user_id', $user_id)->count();

        foreach ($list as $data) {

            $count = Count::where('user_id', $user_id)->where('id', $data->id)->first();

            $count->order = $order_number;
            $count->save();

            $order_number -= 1;
        }

        return response()->json($list, 200);
    }
}
