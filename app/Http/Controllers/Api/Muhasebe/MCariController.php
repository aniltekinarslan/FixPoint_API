<?php

namespace App\Http\Controllers\Api\Muhasebe;

use App\Http\Controllers\Controller;
use App\Models\Muhasebe\MCari;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

use DB;
use Validator;
use Exception;
use App\Models\Muhasebe\MCariOzelFiyat;

class MCariController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = MCari::where(function ($q) use ($request)
        {
            $q->where('SirketKod', auth('api')->user()->Sirket_Kod);

            if ($request->CariKod && strlen($request->CariKod) > 0)
                $q->where('CariKod', 'like', $request->CariKod . '%');

        })->orderBy('CariKod', 'asc')->paginate($request->size ?? 10);

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

        $MCari = MCari::create($input);

        return response([
                            'status' => true,
                            'data' => $MCari,
                            'message' => 'Cari Oluşturuldu'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $MCari
     * @return JsonResponse
     */
    public function show($MCari)
    {
        $MCari = MCari::with(['ozel_fiyatlandirma_listesi'])->where('SirketKod', auth('api')->user()->Sirket_Kod)->where('CariKod', $MCari)->first();
        if (!$MCari)
            return response()->json(['status' => false, 'errors' => ['Cari Bulunamadı! Kod: ' . $MCari]], 402);

        return response()->json(['status' => true, 'data' => $MCari], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MCari $MCari
     * @return void
     */
    public function edit(MCari $MCari)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param MCari $MCari
     * @return ResponseFactory|Response
     */
    public function update(Request $request, $MCari)
    {
        $MCari = MCari::where('SirketKod', auth('api')->user()->Sirket_Kod)->where('CariKod', $MCari)->first();
        if (!$MCari)
            return response()->json(['status' => false, 'errors' => ['Cari Bulunamadı! CariKod: ' . $MCari]], 402);

        $input = $request->all();

        $props = $MCari->column_defaults();
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

        $MCari->update($input);

        return response(['status' => true,
                            'data' => $MCari,
                            'title' => $MCari->CariAdi,
                            'message' => $MCari->CariKod . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MCari $MCari
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(MCari $MCari)
    {
        $response = DB::select("EXEC Web_MCariKontrol @spSirketKod = ?, @spStok_Kod = ?", [auth('api')->user()->Sirket_Kod, $MCari->CariKod])[0];

        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $MCari->CariKod . ' - ' . $response->message], 200);

        $response = $MCari->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $MCari->CariAdi . ' Cari Silindi!'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param MCariOzelFiyat $MCariOzelFiyat
     * @return JsonResponse
     */
    public function getOzelBirim(MCariOzelFiyat $MCariOzelFiyat)
    {
        return response()->json(['status' => true, 'data' => $MCariOzelFiyat], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $data = MCari::where(function ($q) use ($request)
        {
            $q->where('SirketKod', auth('api')->user()->Sirket_Kod);

            if ($request->CariKod && strlen($request->CariKod) > 0)
                $q->where('CariKod', 'like', $request->CariKod . '%');

            if ($request->CariAdi && strlen($request->CariAdi) > 0)
                $q->where('CariAdi', 'like', '%' . $request->CariAdi . '%');

            if ($request->KisaCariAdi && strlen($request->KisaCariAdi) > 0)
                $q->where('KisaCariAdi', 'like', '%' . $request->KisaCariAdi . '%');

            if ($request->KaraListe && strlen($request->KaraListe) > 0)
                $q->where('KaraListe', $request->KaraListe);

            if ($request->AnaYetkiliAdi && strlen($request->AnaYetkiliAdi) > 0)
                $q->where('AnaYetkiliAdi', 'like', '%' . $request->AnaYetkiliAdi . '%');

            if ($request->Sehir && strlen($request->Sehir) > 0)
                $q->where('Sehir', 'like', '%' . $request->Sehir . '%');

            if ($request->Ilce && strlen($request->Ilce) > 0)
                $q->where('Ilce', 'like', '%' . $request->Ilce . '%');

            if ($request->Ulke && strlen($request->Ulke) > 0)
                $q->where('Ulke', 'like', '%' . $request->Ulke . '%');

            if ($request->OzelKod && strlen($request->OzelKod) > 0)
                $q->where('OzelKod1', 'like', '%' . $request->OzelKod . '%');

            if (strlen($request->Aktiflik) > 0)
            {
                if($request->Aktiflik < 2)
                    $q->where('Aktif', $request->Aktiflik);
            }

        })->orderBy('id', 'asc')->paginate($request->size ?? 10);

        return response()->json(['status' => true, 'data' => $data], 200);
    }
}
