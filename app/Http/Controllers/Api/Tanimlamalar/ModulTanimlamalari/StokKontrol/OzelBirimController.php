<?php

namespace App\Http\Controllers\Api\Tanimlamalar\ModulTanimlamalari\StokKontrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

use DB;
use Validator;
use Exception;
use Illuminate\Validation\Rule;
use App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol\OzelBirim;

class OzelBirimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
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
            //'Sirket_Kod' => 'required|string|min:1|max:10',
            'StokKod' => 'required|string|min:1|max:25',
            'Birim' => Rule::unique('StokKartiOzelBirimleri')->where(function ($query) use ($request) {
                return $query->where('Sirket_Kod', $request->Sirket_Kod)->where('StokKod', $request->StokKod);
            }),
            'Esdegeri' => 'required|numeric|min:0',
            'Kaci' => 'required|numeric|min:0',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $OzelBirim = OzelBirim::create($input);

        return response(['status' => true,
                            'input' => $input,
                            'message' => 'Özel Birim Eklendi!'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param OzelBirim $OzelBirim
     * @return JsonResponse
     */
    public function show(OzelBirim $OzelBirim)
    {
        return response()->json(['status' => true, 'data' => $OzelBirim], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param OzelBirim $OzelBirim
     * @return void
     */
    public function edit(OzelBirim $OzelBirim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param OzelBirim $OzelBirim
     * @return ResponseFactory|Response
     */
    public function update(Request $request, OzelBirim $OzelBirim)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'Sirket_Kod' => 'required|string|min:1|max:10',
            'StokKod' => 'required|string|min:1|max:25',
            'Birim' => Rule::unique('StokKartiOzelBirimleri')->where(function ($query) use ($OzelBirim) {
                return $query->where('Sirket_Kod', $OzelBirim->Sirket_Kod)->where('StokKod', $OzelBirim->StokKod);
            }),
            'Esdegeri' => 'required|numeric|min:0',
            'Kaci' => 'required|numeric|min:0',
        ], []);

        $OzelBirim->update($input);

        return response(['status' => true,
                            'input' => $input,
                            'title' => 'Eşdeğer',
                            'message' => $OzelBirim->aciklama . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param OzelBirim $OzelBirim
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(OzelBirim $OzelBirim)
    {
        $response = DB::select("EXEC Web_OzelBirimSilKontrol @spSirketKod = ?, @spStokKod = ?, @spBirim = ?", [auth('api')->user()->Sirket_Kod, $OzelBirim->StokKod, $OzelBirim->Birim])[0];
        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $OzelBirim->Birim . ' - ' . $response->message], 200);

        $response = $OzelBirim->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $OzelBirim->Birim . ' Özel Birimi Silindi!'
                        ], 200);
    }
}
