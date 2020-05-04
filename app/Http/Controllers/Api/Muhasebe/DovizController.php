<?php

namespace App\Http\Controllers\Api\Muhasebe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use App\Models\Muhasebe\Doviz;

use DB;
use Validator;
use Exception;

class DovizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = Doviz::where(function ($q) use ($request)
        {
            $q->where('SirketKod', auth('api')->user()->Sirket_Kod);
        })->orderBy('DovizKod', 'asc')->paginate($request->size ?? 10);

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
            // 'DovizKod' => 'required|string|min:1|max:10|unique:TanimDovizler,DovizKod',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $Doviz = Doviz::create($input);

        return response(['status' => true,
                            'message' => 'Doviz Eklendi!'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Doviz $Doviz
     * @return JsonResponse
     */
    public function show(Doviz $Doviz)
    {
        return response()->json(['status' => true, 'data' => $Doviz], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Doviz $Doviz
     * @return void
     */
    public function edit(Doviz $Doviz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Doviz $Doviz
     * @return ResponseFactory|Response
     */
    public function update(Request $request, Doviz $Doviz)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            //'DovizAciklama' => 'required|string|min:1|max:10|unique:TanimDovizler,DovizAciklama',
        ], []);

        $Doviz->update($input);

        return response(['status' => true,
                            'input' => $input,
                            'title' => 'Doviz',
                            'message' => $Doviz->DovizAciklama . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Doviz $Doviz
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(Doviz $Doviz)
    {
        $response = DB::select("EXEC Web_DovizKontrol @spDoviz = ?", [$Doviz->DovizKod])[0];

        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $Doviz->DovizKod . ' - ' . $response->message], 200);

        $response = $Doviz->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $Doviz->DovizKod . ' Dovizi Silindi!'
                        ], 200);
    }
}
