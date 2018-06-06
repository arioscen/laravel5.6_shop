<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        $items = $user->items()->get();
        $total = 0;
        foreach ($items as $item) {
            $total += $item->price*$item->pivot->number;
        }
        return view('cart/index')->withItems($items)->withTotal($total);
    }
    public function add(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required',
            'number' => 'required',
        ]);
        
        $user_id = Auth::id();
        $item_id = $request->get('item_id');
        $number = $request->get('number');
        
        $user = User::find($user_id);
        $exists = DB::table('item_user')->where('item_id', $item_id)->where('user_id', $user_id)->count() > 0;
        if ($exists) {
            $original_number = $user->items()->find($item_id)->pivot->number;
            $user->items()->updateExistingPivot($item_id, ['number' => $original_number+=$number]);
        } else {
            $user->items()->syncWithoutDetaching([$item_id => ['number' => $number]]);
        }
        
        $item_number = $user->items()->count();
        $request->session()->put('item_number', $item_number);

        return redirect('items');
    }
    public function delete(Request $request, $item_id)
    {
        $user_id = Auth::id();

        $user = User::find($user_id);
        $user->items()->detach($item_id);

        $item_number = $user->items()->count();
        $request->session()->put('item_number', $item_number);

        return redirect('cart');
    }
    public function update(Request $request, $item_id)
    {
        $user_id = Auth::id();

        $user = User::find($user_id);
        $number = $request->get('number');
        $user->items()->syncWithoutDetaching([$item_id => ['number' => $number]]);

        return redirect('cart');
    }          
}
