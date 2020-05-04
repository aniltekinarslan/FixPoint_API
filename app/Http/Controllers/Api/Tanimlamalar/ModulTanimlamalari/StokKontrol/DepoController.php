<?php

namespace App\Http\Controllers\Api\Tanimlamalar\ModulTanimlamalari\StokKontrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol\Depo;
use App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol\DepoYeri;

use DB;
use Validator;
use Exception;
use App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol\GirisTuru;
use App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol\CikisTuru;

class DepoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = Depo::where(function($q) use($request)
        {
            $q->where('Sirket_Kod', auth('api')->user()->Sirket_Kod);
        })->orderBy('id', 'asc')->paginate($request->size ?? 10);

        return response()->json(['status' =>  true, 'data' => $data], 200);
    }

    public function listDepoYerleri()
    {
        $data = DepoYeri::all();
        return response()->json(['status' =>  true, 'data' => $data], 200);
    }

    public function listGirisTurleri(Request $request)
    {
        $data = GirisTuru::where(function($q) use($request)
        {
            if ($request->transfer && strlen($request->transfer) > 0)
                $q->where('transfer', $request->transfer);

            if ($request->hareket && strlen($request->hareket) > 0)
                $q->where('hareket', $request->hareket);
        })->orderBy('id', 'asc')->get();

        return response()->json(['status' =>  true, 'data' => $data], 200);
    }

    public function listCikisTurlerl()
    {
        $data = CikisTuru::all();
        return response()->json(['status' =>  true, 'data' => $data], 200);
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
            'DepoKod' => 'required|string|min:1|max:10|unique:m_Depolar,DepoKod',
            'DepoAdi' => 'required|string|min:1|max:50',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $Depo = Depo::create($input);

        return response(['status' => true,
                            'input' => $input,
                            'message' => 'Depo Eklendi!'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Depo $Depo
     * @return void
     */
    public function show(Depo $Depo)
    {
        $Depo->setAppends(['is_used']);
        return response()->json(['status' =>  true, 'data' => $Depo], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Depo $Depo
     * @return void
     */
    public function edit(Depo $Depo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Depo $Depo
     * @return ResponseFactory|Response
     */
    public function update(Request $request, $Depo)
    {
        $Depo = Depo::where('Sirket_Kod', auth('api')->user()->Sirket_Kod)->where('DepoKod', $Depo)->first();
        if (!$Depo)
            return response()->json(['status' => false, 'errors' => ['Kullanıcı Bulunamadı! Kod: ' . $Depo]], 402);

        $input = $request->all();

        $validator = Validator::make($input, [
            'DepoKod' => 'required|string|min:1|max:10|unique:m_Depolar,DepoKod',
            'DepoAdi' => 'required|string|min:1|max:50',
        ], []);

        $Depo->update($input);

        return response(['status' => true,
                            'input' => $input,
                            'title' => 'Depo',
                            'message' => $Depo->DepoAdi . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Depo $Depo
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(Depo $Depo)
    {
        $response = DB::select("EXEC Web_DepoKontrol @spSirket_Kod = ?, @spDepoKod = ?", [auth('api')->user()->Sirket_Kod, $Depo->DepoKod])[0];

        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $Depo->DepoAdi . ' - ' . $response->message], 200);

        $response = $Depo->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $Depo->DepoAdi . ' Deposu Silindi!'
                        ], 200);
    }
}
