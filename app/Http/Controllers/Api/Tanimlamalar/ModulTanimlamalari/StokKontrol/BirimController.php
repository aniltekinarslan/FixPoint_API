<?php

namespace App\Http\Controllers\Api\Tanimlamalar\ModulTanimlamalari\StokKontrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol\Birim;

use DB;
use Validator;
use Exception;
use App\Models\StokKontrol\StokKartiOzelBirimleri;

class BirimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = Birim::where(function ($q) use ($request)
        {
        })->orderBy('id', 'asc')->paginate($request->size ?? 10);

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
        $validator = Validator::make($input, [
            'aciklama' => 'required|string|min:1|max:10|unique:TanimBirimler,aciklama',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $Birim = Birim::create($input);

        return response(['status' => true,
                            'input' => $input,
                            'message' => 'Birim Eklendi!'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Birim $Birim
     * @return JsonResponse
     */
    public function show(Birim $Birim)
    {
        $Birim->setAppends(['is_used']);
        return response()->json(['status' => true, 'data' => $Birim], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Birim $Birim
     * @return void
     */
    public function edit(Birim $Birim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Birim $Birim
     * @return ResponseFactory|Response
     */
    public function update(Request $request, Birim $Birim)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'aciklama' => 'required|string|min:1|max:10|unique:TanimBirimler,aciklama',
        ], []);

        $Birim->update($input);

        return response(['status' => true,
                            'input' => $input,
                            'title' => 'Birim',
                            'message' => $Birim->aciklama . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Birim $Birim
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(Birim $Birim)
    {
        $response = DB::select("EXEC Web_BirimKontrol @spBirim = ?", [$Birim->aciklama])[0];

        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $Birim->aciklama . ' - ' . $response->message], 200);

        $response = $Birim->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $Birim->aciklama . ' Birimi Silindi!'
                        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param StokKartiOzelBirimleri $StokKartiOzelBirimleri
     * @return JsonResponse
     */
    public function getOzelBirim(StokKartiOzelBirimleri $ozelBirim)
    {
        return response()->json(['status' => true, 'data' => $ozelBirim], 200);
    }
}
