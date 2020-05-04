<?php

namespace App\Http\Controllers\Api\StokKontrol;

use App\Http\Controllers\Controller;
use App\Models\StokKontrol\StokHareketleri;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

class StokHareketleriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = StokHareketleri::where(function($q) use($request)
        {
            $q->where('Sirket_Kod', auth('api')->user()->Sirket_Kod);

            if ($request->Tarih1 && strlen($request->Tarih1) > 0 && $request->Tarih2 && strlen($request->Tarih2) > 0)
                $q->whereBetween('Tarih', [$request->Tarih1, $request->Tarih2]);

            if ($request->StokKod1 && !$request->StokKod2)
                $request->StokKod2 = $request->StokKod1;

            if ($request->StokKod1 && $request->StokKod2)
                $q->whereBetween('StokKod', [$request->StokKod1, $request->StokKod2]);

            if ($request->VariantKod1 && strlen($request->VariantKod1) > 0)
                $q->where('VariantKod', 'like', $request->VariantKod1.'%')->where('VariantKod', '>=', $request->VariantKod1);

            if ($request->VariantKod2 && strlen($request->VariantKod2) > 0)
                $q->where('VariantKod', '<=', $request->VariantKod2);

            if ($request->GirisTuru && strlen($request->GirisTuru) > 0)
                $q->where('GirisTuru', $request->GirisTuru);

            if ($request->GirisYeri && strlen($request->GirisYeri) > 0)
                $q->where('GirisYeri', $request->GirisYeri);

            if ($request->CikisTuru && strlen($request->CikisTuru) > 0)
                $q->where('CikisTuru', $request->CikisTuru);

            if ($request->CikisYeri && strlen($request->CikisYeri) > 0)
                $q->where('CikisYeri', $request->CikisYeri);

        })->orderBy('id', 'asc')->paginate($request->size ?? 10);

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

        $stokhareket = StokHareketleri::create($input);
        return response([
                            'status' => true,
                            'data' => $stokhareket,
                            'message' => 'Stok Hareketi Oluşturuldu'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param StokHareketleri $StokHareketleri
     * @return JsonResponse
     */
    public function show(StokHareketleri $StokHareketleri)
    {
        return response()->json(['status' =>  true, 'data' => $StokHareketleri], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param StokHareketleri $StokHareketleri
     * @return void
     */
    public function edit(StokHareketleri $StokHareketleri)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param StokHareketleri $StokHareketleri
     * @return void
     */
    public function update(Request $request, StokHareketleri $StokHareketleri)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StokHareketleri $StokHareketleri
     * @return void
     */
    public function destroy(StokHareketleri $StokHareketleri)
    {
        //
    }
}
