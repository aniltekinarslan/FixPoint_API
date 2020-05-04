<?php

namespace App\Http\Controllers\Api\Muhasebe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

use DB;
use Validator;
use Exception;
use App\Models\Muhasebe\MCariOzelFiyat;

class MCariOzelFiyatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(['status' => true, 'data' => ''], 200);
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
        $input['Sirket_Kod'] = auth('api')->user()->Sirket_Kod;

        $validator = Validator::make($input, [
            'CariKod' => 'required|string|min:1|max:25',
            'StokKod' => 'required|string|min:1|max:25',
            'VariantKod' => 'required|string|min:1|max:25',
            //'StkIsk1' => 'required|numeric|min:0',
            //'StkIsk2' => 'required|numeric|min:0',
            //'StkIsk3' => 'required|numeric|min:0',
            //'BrFiyat' => 'required|numeric|min:0',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $MCariOzelFiyat = MCariOzelFiyat::create($input);

        return response(['status' => true,
                            'data' => $MCariOzelFiyat,
                            'message' => 'Özel Fiyatlandırma Eklendi!'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param MCariOzelFiyat $MCariOzelFiyat
     * @return JsonResponse
     */
    public function show(MCariOzelFiyat $MCariOzelFiyat)
    {
        //$MCariOzelFiyat = MCariOzelFiyat::where('Sirket_Kod', auth('api')->user()->Sirket_Kod)->where('CariKod', $MCariOzelFiyat)->first();
        //if (!$MCariOzelFiyat)
        //    return response()->json(['status' => false, 'errors' => ['Özel Fiyat Bulunamadı! Kod: ' . $MCariOzelFiyat]], 402);

        return response()->json(['status' => true, 'data' => $MCariOzelFiyat], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param MCariOzelFiyat $MCariOzelFiyat
     * @return void
     */
    public function edit(MCariOzelFiyat $MCariOzelFiyat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param MCariOzelFiyat $MCariOzelFiyat
     * @return ResponseFactory|Response
     */
    public function update(Request $request, MCariOzelFiyat $MCariOzelFiyat)
    {
        $input = $request->all();

        $input = array_filter($input, function($k) use ($input) {
            return !is_null($input[$k]);
        }, ARRAY_FILTER_USE_KEY);

        $MCariOzelFiyat->update($input);

        return response(['status' => true,
                            'data' => $request,
                            'title' => 'Özel Fiyat',
                            'message' => $request->VariantKod . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MCariOzelFiyat $MCariOzelFiyat
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(MCariOzelFiyat $MCariOzelFiyatlandirm)
    {
        $response = DB::select("EXEC Web_MCariOzelFiyatSilKontrol @spSirket_Kod = ?, @spCariKod = ?, @spStokKod = ?, @spBirim = ?",
                               [auth('api')->user()->Sirket_Kod, $MCariOzelFiyat->CariKod, $MCariOzelFiyat->StokKod, $MCariOzelFiyat->VariantKod])[0];
        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $MCariOzelFiyat->VariantKod . ' - ' . $response->message], 200);

        $response = $MCariOzelFiyat->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $MCariOzelFiyat->VariantKod . ' Özel Fiyatlandırmai Silindi!'
                        ], 200);
    }
}
