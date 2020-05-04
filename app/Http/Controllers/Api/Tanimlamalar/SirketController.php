<?php

namespace App\Http\Controllers\Api\Tanimlamalar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Validator;
use App\Models\Tanimlamalar\Sirket;

class SirketController extends Controller
{
    public function index(Request $request)
    {
        $data = Sirket::where(function ($q) use ($request)
        {
            $q->where('Aktif', '1');
        })->orderBy('Sirket_Kod', 'asc')->paginate($request->size ?? 20);

        return response()->json(['status' => true, 'data' => $data], 200);
    }
}
