<?php
namespace MUST\RRLogger\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TestController extends Controller
{
    public function handle(Request $request)
    {
        return response()->json(['status' => 'success']);
    }
}
