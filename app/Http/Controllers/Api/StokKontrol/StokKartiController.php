<?php

namespace App\Http\Controllers\Api\StokKontrol;

use App\Http\Controllers\Controller;
use App\Models\StokKontrol\StokKarti;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

use DB;
use Validator;
use Exception;
use App\Models\StokKontrol\Variant;

class StokKartiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        //$offset = $request->has('offset') ? $request->query('offset') : 0;
        //$limit = $request->has('limit') ? $request->query('limit') : 10;
        //$qb = StokKarti::query()/*->with('categories')*/;
        //if($request->has('q'))
        //    $qb->where('name', 'like', '%' . $request->query('q') . '%');
        //
        //if($request->has('sortBy'))
        //    $qb->orderBy($request->query('sortBy'), $request->query('sort', 'DESC'));
        //
        //$data = $qb->paginate($size);

        $data = StokKarti::where(function ($q) use ($request)
        {
            $q->where('Sirket_Kod', auth('api')->user()->Sirket_Kod);

            if ($request->Kod && strlen($request->Kod) > 0)
                $q->where('Kod', 'like', $request->Kod . '%');

            if ($request->ResimNo && strlen($request->ResimNo) > 0)
                $q->where('ResimNo', $request->ResimNo);

            if ($request->Isim && strlen($request->Isim) > 0)
                $q->where('Isim', 'like', '%' . $request->Isim . '%');

            if ($request->KisaTanim && strlen($request->KisaTanim) > 0)
                $q->where('KisaTanim', 'like', '%' . $request->KisaTanim . '%');

            if ($request->MalzemeGrubu1 && strlen($request->MalzemeGrubu1) > 0)
                $q->where('MalzemeGrubu1', 'like', '%' . $request->MalzemeGrubu1 . '%');

            if ($request->MalzemeGrubu2 && strlen($request->MalzemeGrubu2) > 0)
                $q->where('MalzemeGrubu2', 'like', '%' . $request->MalzemeGrubu2 . '%');

        })->orderBy('id', 'asc')->paginate($request->size ?? 10);


        // $data = $data->makeHidden('full_name');

        //$data->each(function($item){
        //    $item->setAppends(['full_name']);
        //});
        //$data->each->setAppends(['full_name']);

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
            'Kod' => 'required|string|min:1|max:25',
            'Isim' => 'required|string|min:1|max:60',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $StokKarti = StokKarti::create($input);

        $props = $StokKarti->column_defaults();
        foreach ($input as $key => $val)
        {
            if (is_null($input[$key]))
                $input[$key] = $props[$key]->default;
        }


        $variantInput = $this->stok_to_variant($request);

        $variant = Variant::create($variantInput);

        return response([
                            'status' => true,
                            'data' => $StokKarti,
                            'message' => 'Stok Kartı Oluşturuldu'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $StokKarti
     * @return JsonResponse
     */
    public function show($StokKarti)
    {
        $StokKarti = StokKarti::with(['ozel_birimler'])->with(['variantlar'])->where('Kod', $StokKarti)->where('Sirket_Kod', auth('api')->user()->Sirket_Kod)->first();
        if (!$StokKarti)
            return response()->json(['status' => false, 'errors' => ['Stok Kartı Bulunamadı! Kod: ' . $StokKarti]], 402);

        return response()->json(['status' => true, 'data' => $StokKarti], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param StokKarti $StokKarti
     * @return void
     */
    public function edit(StokKarti $StokKarti)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param StokKarti $StokKarti
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Response
     */
    public function update(Request $request, $StokKarti)
    {
        $StokKarti = StokKarti::where('Sirket_Kod', auth('api')->user()->Sirket_Kod)->where('Kod', $StokKarti)->first();
        if (!$StokKarti)
            return response()->json(['status' => false, 'errors' => ['Stok Kartı Bulunamadı! Kod: ' . $StokKarti]], 402);

        $input = $request->all();

        $props = $StokKarti->column_defaults();
        foreach ($input as $key => $val)
        {
            if (is_null($input[$key]))
                $input[$key] = $props[$key]->default;
        }

        $validator = Validator::make($input, [
            'Kod' => 'required|string|min:1|max:25',
            'Isim' => 'required|string|min:1|max:60',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        if(!is_null($input['SatisYap']) && $StokKarti->SatisYap == 'E' && $input['SatisYap'] == 'H')
        {
            $response = DB::select("EXEC Web_CariOzelFiyatKontrol @spSirketKod = ?, @spStokKod = ?", [auth('api')->user()->Sirket_Kod, $StokKarti->Kod])[0];

            if (!$response->status)
                return response(['status' => false,
                                    'title' => 'Hata',
                                    'message' => $response->message], 200);
        }

        $StokKarti->update($input);

        $variantInput = $this->stok_to_variant($request);
        $variant = Variant::where('VariantKod', $StokKarti->Kod)->where('Stokkod', $StokKarti->Kod)->first();
        $variant->update($variantInput);

        return response(['status' => true,
                            'data' => $StokKarti,
                            'title' => 'Stok Kartı',
                            'message' => $StokKarti->Kod . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StokKarti $StokKarti
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(StokKarti $StokKarti)
    {
        $response = DB::select("EXEC Web_StokKartiKontrol @spSirket_Kod = ?, @spStok_Kod = ?", [auth('api')->user()->Sirket_Kod, $StokKarti->Kod])[0];

        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $StokKarti->Kod . ' - ' . $response->message], 200);

        $response = $StokKarti->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $StokKarti->DepoAdi . ' Stoğu Silindi!'
                        ], 200);
    }

    public function search(Request $request)
    {
        $data = StokKarti::where(function ($q) use ($request)
        {
            $q->where('Sirket_Kod', auth('api')->user()->Sirket_Kod);

            if ($request->Kod && strlen($request->Kod) > 0)
                $q->where('Kod', 'like', $request->Kod . '%');

            if ($request->Isim && strlen($request->Isim) > 0)
                $q->where('Isim', 'like', '%' . $request->Isim . '%');

            if ($request->KisaTanim && strlen($request->KisaTanim) > 0)
                $q->where('KisaTanim', 'like', '%' . $request->KisaTanim . '%');

            if ($request->ResimNo && strlen($request->ResimNo) > 0)
                $q->where('ResimNo', 'like', $request->ResimNo . '%');

            if ($request->MalzemeGrubu && strlen($request->MalzemeGrubu) > 0)
                $q->where(function ($qa) use ($request)
                {
                    $qa->where('MalzemeGrubu1', 'like', '%' . $request->MalzemeGrubu . '%')
                        ->orWhere('MalzemeGrubu2', 'like', '%' . $request->MalzemeGrubu . '%')
                        ->orWhere('MalzemeGrubu3', 'like', '%' . $request->MalzemeGrubu . '%')
                        ->orWhere('MalzemeGrubu4', 'like', '%' . $request->MalzemeGrubu . '%')
                        ->orWhere('MalzemeGrubu5', 'like', '%' . $request->MalzemeGrubu . '%');;
                });

        })->orderBy('Kod', 'asc')->paginate($request->size ?? 10);

        return response()->json(['status' => true, 'data' => $data], 200);
    }

    private function stok_to_variant($request)
    {
        $variantInput = [];
        $variantInput['Sirket_Kod'] = $request->Sirket_Kod;
        $variantInput['VariantKod'] = $request->Kod;
        $variantInput['StokKod'] = $request->Kod;
        $variantInput['Tanim'] = $request->Isim;
        $variantInput['Aktif'] = $request->Aktif;
        if ($request->ResimNo != null)
            $variantInput['vResimNo'] = $request->ResimNo;
        if ($request->Genislik != null)
            $variantInput['Genislik'] = $request->Genislik;
        if ($request->Boy != null)
            $variantInput['Boy'] = $request->Boy;
        if ($request->Yukseklik != null)
            $variantInput['Yukseklik'] = $request->Yukseklik;
        $variantInput['FaturaStokIsim'] = $request->FaturaStokIsim;

        return $variantInput;
    }
}
