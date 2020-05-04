<?php

namespace App\Http\Controllers\Api\Muhasebe;

use App\Http\Controllers\Controller;
use App\Models\Muhasebe\HesapPlani;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

use DB;
use Validator;
use Exception;
use App\Models\Muhasebe\HesapPlaniOzelFiyat;

class HesapPlaniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = HesapPlani::where(function ($q) use ($request)
        {
            $q->where('SirketKod', auth('api')->user()->Sirket_Kod);

            if ($request->CariKod && strlen($request->CariKod) > 0)
                $q->where('CariKod', 'like', $request->CariKod . '%');

        })->orderBy('HesapKod', 'asc')->paginate($request->size ?? 10);

        return response()->json(['status' => true, 'data' => $data], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['SirketKod'] = auth('api')->user()->Sirket_Kod;

        $validator = Validator::make($input, [
            'CariKod' => 'required|string|min:1|max:25',
            'CariAdi' => 'required|string|min:1|max:65',
            'CariHesapTipi' => 'required|string|min:1|max:18',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $HesapPlani = HesapPlani::create($input);

        return response([
                            'status' => true,
                            'data' => $HesapPlani,
                            'message' => 'Cari Oluşturuldu'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $HesapPlani
     * @return JsonResponse
     */
    public function show($HesapPlani)
    {
        $HesapPlani = HesapPlani::where('SirketKod', auth('api')->user()->Sirket_Kod)->where('CariKod', $HesapPlani)->first();
        if (!$HesapPlani)
            return response()->json(['status' => false, 'errors' => ['Cari Bulunamadı! Kod: ' . $HesapPlani]], 402);

        return response()->json(['status' => true, 'data' => $HesapPlani], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HesapPlani $HesapPlani
     * @return void
     */
    public function edit(HesapPlani $HesapPlani)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param HesapPlani $HesapPlani
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Response
     */
    public function update(Request $request, $HesapPlani)
    {
        $HesapPlani = HesapPlani::where('SirketKod', auth('api')->user()->Sirket_Kod)->where('CariKod', $HesapPlani)->first();
        if (!$HesapPlani)
            return response()->json(['status' => false, 'errors' => ['Cari Bulunamadı! CariKod: ' . $HesapPlani]], 402);

        $input = $request->all();

        $props = $HesapPlani->column_defaults();
        foreach ($input as $key => $val)
        {
            if (is_null($input[$key]))
                $input[$key] = $props[$key]->default;
        }


        $validator = Validator::make($input, [
            'CariKod' => 'required|string|min:1|max:25',
            'CariAdi' => 'required|string|min:1|max:65',
            'CariHesapTipi' => 'required|string|min:1|max:18',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $HesapPlani->update($input);

        return response(['status' => true,
                            'data' => $HesapPlani,
                            'title' => $HesapPlani->CariAdi,
                            'message' => $HesapPlani->CariKod . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HesapPlani $HesapPlani
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(HesapPlani $HesapPlani)
    {
        $response = DB::select("EXEC Web_HesapPlaniKontrol @spSirketKod = ?, @spStok_Kod = ?", [auth('api')->user()->Sirket_Kod, $HesapPlani->CariKod])[0];

        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $HesapPlani->CariKod . ' - ' . $response->message], 200);

        $response = $HesapPlani->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $HesapPlani->HesapAdi1 . ' Hesap Plani Silindi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $data = HesapPlani::where(function ($q) use ($request)
        {
            $q->where('SirketKod', auth('api')->user()->Sirket_Kod);
            $q->where('Aktif', 1);

            if ($request->HesapKod && strlen($request->HesapKod) > 0)
                $q->where('HesapKod', 'like', $request->HesapKod . '%');

            if ($request->HesapAdi1 && strlen($request->HesapAdi1) > 0)
                $q->where('HesapAdi1', 'like', '%' . $request->HesapAdi1 . '%');

        })->orderBy('HesapKod', 'asc')->paginate($request->size ?? 10);

        return response()->json(['status' => true, 'data' => $data], 200);
    }
}
