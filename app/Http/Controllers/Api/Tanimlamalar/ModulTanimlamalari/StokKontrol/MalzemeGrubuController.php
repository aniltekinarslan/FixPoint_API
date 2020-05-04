<?php

namespace App\Http\Controllers\Api\Tanimlamalar\ModulTanimlamalari\StokKontrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol\MalzemeGrubu;

use DB;
use Validator;
use Exception;

class MalzemeGrubuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = MalzemeGrubu::where(function ($q) use ($request)
        {
            $q->where('Sirket_Kod', auth('api')->user()->Sirket_Kod);
        })->orderBy('id', 'asc')->paginate($request->size ?? 10);

        return response()->json(['status' => true, 'data' => $data], 200);
    }

    public function listWithLevel($groupNo = 0)
    {
        if ($groupNo < 0 || $groupNo > 5)
            return response()->json(['status' => false, 'data' => 'groupNo min:1 max:5 olmalıdır'], 401);

        //$data = \Cache::remember('MalzemeGrubu.listWithLevel.' . $groupNo, 300, function () use ($groupNo)
        //{
        $m = MalzemeGrubu::where(function ($q) use ($groupNo)
        {
            if ($groupNo > 0)
                $q->where('Grup' . $groupNo, 1);

        })//->select('id', 'GrupKod', 'Aciklama')
        ->orderBy('Aciklama', 'asc')
            ->get();

        return response()->json(['status' => true, 'data' => $m], 200);
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
            'GrupKod' => 'required|string|min:1|max:15|unique:TanimMlzGruplari,GrupKod',
            'Aciklama' => 'required|string|min:1|max:100',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        if(!$request->has('Grup1') )
            $input['Grup1'] = 0;
        if(!$request->has('Grup2') )
            $input['Grup2'] = 0;
        if(!$request->has('Grup3') )
            $input['Grup3'] = 0;
        if(!$request->has('Grup4') )
            $input['Grup4'] = 0;
        if(!$request->has('Grup5') )
            $input['Grup5'] = 0;

        if ($input['Grup2'] == 0 || !isset($input['Grup2UstKod']))
            $input['Grup2UstKod'] = '';
        if ($input['Grup3'] == 0 || !isset($input['Grup3UstKod']))
            $input['Grup3UstKod'] = '';
        if ($input['Grup4'] == 0 || !isset($input['Grup4UstKod']))
            $input['Grup4UstKod'] = '';
        if ($input['Grup5'] == 0 || !isset($input['Grup5UstKod']))
            $input['Grup5UstKod'] = '';

        $input['Grup2UstKod'] = $input['Grup2UstKod'] ?? '';
        $input['Grup3UstKod'] = $input['Grup3UstKod'] ?? '';
        $input['Grup4UstKod'] = $input['Grup4UstKod'] ?? '';
        $input['Grup5UstKod'] = $input['Grup5UstKod'] ?? '';

        $MalzemeGrubu = MalzemeGrubu::create($input);

        return response(['status' => true,
                            'input' => $input,
                            'message' => 'Malzeme Grubu Eklendi!'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param MalzemeGrubu $MalzemeGrubu
     * @return JsonResponse
     */
    public function show(MalzemeGrubu $MalzemeGrubu)
    {
        $MalzemeGrubu->setAppends(['is_used']);
        return response()->json(['status' =>  true, 'data' => $MalzemeGrubu], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MalzemeGrubu $MalzemeGrubu
     * @return void
     */
    public function edit(MalzemeGrubu $MalzemeGrubu)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param MalzemeGrubu $MalzemeGrubu
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function update(Request $request, MalzemeGrubu $MalzemeGrubu)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'GrupKod' => 'required|string|min:1|max:15|unique:TanimMlzGruplari,GrupKod',
            'Aciklama' => 'required|string|min:1|max:100',
            'Sirket_Kod' => 'required|string|min:1|max:10',
        ], []);

        if ($input['Grup2'] == 0 || is_null($input['Grup2UstKod']))
            $input['Grup2UstKod'] = '';
        if ($input['Grup3'] == 0 || is_null($input['Grup3UstKod']))
            $input['Grup3UstKod'] = '';
        if ($input['Grup4'] == 0 || is_null($input['Grup4UstKod']))
            $input['Grup4UstKod'] = '';
        if ($input['Grup5'] == 0 || is_null($input['Grup5UstKod']))
            $input['Grup5UstKod'] = '';

        $input['Grup2UstKod'] = $input['Grup2UstKod'] ?? '';
        $input['Grup3UstKod'] = $input['Grup3UstKod'] ?? '';
        $input['Grup4UstKod'] = $input['Grup4UstKod'] ?? '';
        $input['Grup5UstKod'] = $input['Grup5UstKod'] ?? '';

        $MalzemeGrubu->update($input);

        return response(['status' => true,
                            'input' => $input,
                            'title' => 'Malzeme Grubu',
                            'message' => $MalzemeGrubu->Aciklama . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MalzemeGrubu $MalzemeGrubu
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(MalzemeGrubu $MalzemeGrubu)
    {
        $response = DB::select("EXEC Web_MalzemeGrubuKontrol @spSirket_Kod = ?, @spMalzemeGrb = ?", [auth('api')->user()->Sirket_Kod, $MalzemeGrubu->GrupKod])[0];

        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $MalzemeGrubu->Aciklama . ' - ' . $response->message], 200);

        $response = $MalzemeGrubu->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $MalzemeGrubu->Aciklama . ' Malzeme Grubu Silindi!'
                        ], 200);
    }
}
