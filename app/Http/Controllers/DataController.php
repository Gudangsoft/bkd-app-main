<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function dataUser(Request $request)
    {
        return view('admin.data.master-data.index', [
            'category' => $request->category
        ]);
    }
}
