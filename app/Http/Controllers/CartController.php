<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use DB;
use App\Item;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()) {
            $user_id = Auth::id();
            $user = User::find($user_id);

            // check if user add item before login
            $items = $request->session()->pull('items');
            if ($items) {
                $json = json_decode($items);
                foreach($json as $key => $val) {
                    $exists = DB::table('item_user')->where('item_id', $key)->where('user_id', $user_id)->count() > 0;
                    if ($exists) {
                        $user->items()->updateExistingPivot($key, ['number' => $val]);
                    } else {
                        $user->items()->syncWithoutDetaching([$key => ['number' => $val]]);
                    }
                }
            }

            $items = $user->items()->get();
            $total = 0;
            foreach ($items as $item) {
                $total += $item->price*$item->pivot->number;
            }
            return view('cart/index')->withItems($items)->withTotal($total);
        } else {
            $item_array = array();
            $total = 0;
            $items = $request->session()->get('items');
            if ($items) {
                $json = json_decode($items);
                foreach($json as $key => $val) {
                    $item = Item::find($key);
                    array_push(
                        $item_array,
                        [
                            "id" => $key,
                            "number" => $val,
                            "name" => $item->name,
                            "price" => $item->price
                        ]
                    );
                    $total += $item->price*$val;
                }
            }
            return view('cart/index')->withItems($item_array)->withTotal($total);
        }
    }
    public function add(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required',
            'number' => 'required',
        ]);

        $item_id = $request->get('item_id');
        $number = $request->get('number');

        if (Auth::user()) {
            $user_id = Auth::id();            
            $user = User::find($user_id);
            $exists = DB::table('item_user')->where('item_id', $item_id)->where('user_id', $user_id)->count() > 0;
            if ($exists) {
                $original_number = $user->items()->find($item_id)->pivot->number;
                $user->items()->updateExistingPivot($item_id, ['number' => $original_number += $number]);
            } else {
                $user->items()->syncWithoutDetaching([$item_id => ['number' => $number]]);
            }
        } else {
            $items = $request->session()->get('items');
            if ($items) {
                $json = json_decode($items);
                if (property_exists($json, $item_id)) {
                    $json->{$item_id} = $json->{$item_id} + $number;
                } else {
                    $json->{$item_id} = $number;
                }
            } else {
                $json = [$item_id => $number];                
            }
            $request->session()->put('items', json_encode($json));
        }
        return redirect('items');
    }
    public function delete(Request $request, $item_id)
    {
        if (Auth::user()) {
            $user_id = Auth::id();
            $user = User::find($user_id);
            $user->items()->detach($item_id);
        } else {
            $items = $request->session()->get('items');
            if ($items) {
                $json = json_decode($items);
                unset($json->{$item_id});
                $request->session()->put('items', json_encode($json));
            }
        }

        return redirect('cart');
    }
    public function update(Request $request, $item_id)
    {
        if (Auth::user()) {
            $user_id = Auth::id();
            $user = User::find($user_id);
            $number = $request->get('number');
            $user->items()->syncWithoutDetaching([$item_id => ['number' => $number]]);
        } else {
            $items = $request->session()->get('items');
            if ($items) {
                $json = json_decode($items);
                $json->{$item_id} = $request->get('number');
                $request->session()->put('items', json_encode($json));
            }            
        }

        return redirect('cart');
    }          
}
