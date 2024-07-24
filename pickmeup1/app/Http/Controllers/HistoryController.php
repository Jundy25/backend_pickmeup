<?php

namespace App\Http\Controllers;

use App\Models\RideHistory;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $rideHistories = RideHistory::with(['user', 'rider'])->get();
        return response()->json($rideHistories);
    }
}
