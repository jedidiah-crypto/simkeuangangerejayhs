<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donatur;

class DonaturController extends Controller
{
    public function index()
    {
        $items = Donatur::withCount('pemasukan')->paginate(15);
        return view('donatur.index', compact('items'));
    }

    public function show(Donatur $donatur)
    {
        $donatur->load('pemasukan');
        return view('donatur.show', compact('donatur'));
    }
}
