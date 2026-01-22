<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionReportController extends Controller
{
    public function index()
    {
        return view('reports.transaction');
    }
}
