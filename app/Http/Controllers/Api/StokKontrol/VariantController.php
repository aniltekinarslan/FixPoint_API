<?php

namespace App\Http\Controllers\Api\StokKontrol;

use App\Http\Controllers\Controller;
use App\Models\StokKontrol\Variant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

class VariantController extends Controller
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
        //$qb = Variant::query()/*->with('categories')*/;
        //if($request->has('q'))
        //    $qb->where('name', 'like', '%' . $request->query('q') . '%');
        //
        //if($request->has('sortBy'))
        //    $qb->orderBy($request->query('sortBy'), $request->query('sort', 'DESC'));
        //
        //$data = $qb->paginate($size);


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

        $variant = Variant::create($input);
        return response([
                            'status' => true,
                            'data' => $variant,
                            'message' => 'Variant Oluşturuldu'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Variant $Variant
     * @return void
     */
    public function show(Variant $Variant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Variant $Variant
     * @return void
     */
    public function edit(Variant $Variant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Variant $Variant
     * @return void
     */
    public function update(Request $request, Variant $Variant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Variant $Variant
     * @return void
     */
    public function destroy(Variant $Variant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $data = Variant::whereHas('stok', function($q) use ($request)
        {
            if ($request->SatisYap && strlen($request->SatisYap) > 0)
                $q->where('SatisYap', $request->SatisYap);

        })->where(function ($q) use ($request)
        {
            $q->where('Sirket_Kod', auth('api')->user()->Sirket_Kod);

            if ($request->SabitStokKod && strlen($request->SabitStokKod) > 0)
                $q->where('StokKod', $request->SabitStokKod);

            if ($request->StokKod && strlen($request->StokKod) > 0)
                $q->where('StokKod', 'like', $request->StokKod . '%');

            if ($request->VariantKod && strlen($request->VariantKod) > 0)
                $q->where('VariantKod', 'like', $request->VariantKod.'%');

            if ($request->VariantTanim && strlen($request->VariantTanim) > 0)
                $q->where('Tanim', 'like', '%' . $request->VariantTanim . '%');

            if ($request->KisaTanim && strlen($request->KisaTanim) > 0)
                $q->where('KisaTanim', 'like', '%' . $request->KisaTanim . '%');

            if ($request->vResimNo && strlen($request->vResimNo) > 0)
                $q->where('vResimNo', 'like', $request->vResimNo . '%');

        })->orderBy('id', 'asc')->paginate($request->size ?? 10);

        return response()->json(['status' => true, 'data' => $data], 200);
    }
}
