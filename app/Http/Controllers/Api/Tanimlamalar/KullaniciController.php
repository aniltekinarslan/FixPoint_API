<?php

namespace App\Http\Controllers\Api\Tanimlamalar;

use App\Http\Controllers\Controller;
use App\Models\Tanimlamalar\TanimPersonel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

use DB;
use Validator;
use Exception;
use App\Models\StokKontrol\StokKarti;

class KullaniciController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = TanimPersonel::where(function ($q) use ($request)
        {
            $q->where('Sirket_Kod', auth('api')->user()->Sirket_Kod);

            if ($request->SicilNo && strlen($request->SicilNo) > 0)
                $q->where('SicilNo', 'like', $request->SicilNo . '%');

            if ($request->Isim && strlen($request->Isim) > 0)
                $q->where('Isim', 'like', '%' . $request->Isim . '%');

            if ($request->Gorevi && strlen($request->Gorevi) > 0)
                $q->where('Gorevi', 'like', '%' . $request->Gorevi . '%');

            if ($request->CalistigiBolum && strlen($request->CalistigiBolum) > 0)
                $q->where('CalistigiBolum', 'like', '%' . $request->CalistigiBolum . '%');

        })->orderBy('SicilNo', 'asc')->paginate($request->size ?? 10);

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
        $input['Sirket_Kod'] = auth('api')->user()->Sirket_Kod;

        $validator = Validator::make($input, [
            'SicilNo' => 'required|string|min:1|max:10',
            'Isim' => 'required|string|min:1|max:30',
            'Sifre' => 'required|string|min:1|max:32',
            'Gorevi' => 'nullable|string|max:30',
            'EPosta' => 'nullable|string|max:60',
            'CalistigiBolum' => 'nullable|string|max:50',
            'TcKimlikNo' => 'nullable|string|max:11',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $TanimPersonel = TanimPersonel::create($input);

        return response([
                            'status' => true,
                            'data' => $TanimPersonel,
                            'message' => 'Kullanıcı Oluşturuldu'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $TanimPersonel
     * @return JsonResponse
     */
    public function show($TanimPersonel)
    {
        $TanimPersonel = TanimPersonel::where('SicilNo', $TanimPersonel)->where('Sirket_Kod', auth('api')->user()->Sirket_Kod)->first();
        if (!$TanimPersonel)
            return response()->json(['status' => false, 'errors' => ['Kullanıcı Bulunamadı! Kod: ' . $TanimPersonel]], 402);

        return response()->json(['status' => true, 'data' => $TanimPersonel], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TanimPersonel $TanimPersonel
     * @return void
     */
    public function edit(TanimPersonel $TanimPersonel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TanimPersonel $TanimPersonel
     * @return ResponseFactory|Response
     */
    public function update(Request $request, $TanimPersonel)
    {
        $TanimPersonel = TanimPersonel::where('Sirket_Kod', auth('api')->user()->Sirket_Kod)->where('SicilNo', $TanimPersonel)->first();
        if (!$TanimPersonel)
            return response()->json(['status' => false, 'errors' => ['Kullanıcı Bulunamadı! SicilNo: ' . $TanimPersonel]], 402);

        $input = $request->all();

        $props = $TanimPersonel->column_defaults();
        foreach ($input as $key => $val)
        {
            if (is_null($input[$key]))
                $input[$key] = $props[$key]->default;
        }

        $validator = Validator::make($input, [
            'SicilNo' => 'required|string|min:1|max:10',
            'Isim' => 'required|string|min:1|max:30',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $TanimPersonel->update($input);

        return response(['status' => true,
                            'data' => $TanimPersonel,
                            'title' => 'Kullanıcı',
                            'message' => $TanimPersonel->SicilNo . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TanimPersonel $TanimPersonel
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(TanimPersonel $TanimPersonel)
    {
        $response = DB::select("EXEC Web_TanimPersonelKontrol @spSirket_Kod = ?, @spStok_Kod = ?", [auth('api')->user()->Sirket_Kod, $TanimPersonel->SicilNo])[0];

        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $TanimPersonel->SicilNo . ' - ' . $response->message], 200);

        $response = $TanimPersonel->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $TanimPersonel->DepoAdi . ' Stoğu Silindi!'
                        ], 200);
    }
}
