<?php

namespace App\Http\Controllers\Api\Tanimlamalar\ModulTanimlamalari\StokKontrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol\ParcaBilgisi;

use DB;
use Validator;

class ParcaBilgisiController extends Controller
{
    public function index(Request $request)
    {
        $data = ParcaBilgisi::where(function ($q) use ($request)
        {
        })->orderBy('aciklama', 'asc')->paginate($request->size ?? 10);

        return response()->json(['status' => true, 'data' => $data], 200);
    }
}
