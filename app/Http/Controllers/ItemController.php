<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemController extends Controller
{
    public function index()
    {
        return view('item/index')->withItems(Item::all());
    }
}
