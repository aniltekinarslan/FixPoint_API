<?php

namespace App\Http\Controllers\Api\Satis;

use App\Http\Controllers\Controller;
use App\Models\Satis\Teklif;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

use DB;
use Validator;
use Exception;
use Illuminate\Validation\Rule;
use App\Models\Satis\TeklifSatir;

class TeklifController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = Teklif::where(function ($q) use ($request)
        {
            $q->where('Sirket_Kod', auth('api')->user()->Sirket_Kod);

            if ($request->Nosu && strlen($request->Nosu) > 0)
                $q->where('Nosu', 'like', $request->Nosu . '%');

            if (!($request->Aciklar && $request->Kapalilar && $request->Aciklar == 'E' && $request->Kapalilar == 'E'))
            {
                if ($request->Aciklar && $request->Aciklar == 'E')
                    $q->where('Kapan', 'H');

                if ($request->Kapalilar && $request->Kapalilar == 'E')
                    $q->where('Kapan', 'E');
            }

            if ($request->Iptaller && strlen($request->Iptaller) > 0)
                $q->where('Iptal', 'E');
            else
                $q->where('Iptal', 'H');

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
        $input['Sirket_Kod'] = auth('api')->user()->Sirket_Kod;

        $validator = Validator::make($input, [
            'Nosu' => Rule::unique('m_CariKart')->where(function ($query) use ($request, $input)
            {
                return $query->where('Sirket_Kod', $input['Sirket_Kod'])->where('Nosu', $request->Nosu);
            }),
            'CariAdi' => 'required|string|min:1|max:65',
            'CariHesapTipi' => 'required|string|min:1|max:18',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $Teklif = Teklif::create($input);

        return response([
                            'status' => true,
                            'data' => $Teklif,
                            'message' => 'Teklif Oluşturuldu'
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $Teklif
     * @return JsonResponse
     */
    public function show($Teklif)
    {
        $Teklif = Teklif::with(['satirlar'])->where('Sirket_Kod', auth('api')->user()->Sirket_Kod)->where('Nosu', $Teklif)->first();
        if (!$Teklif)
            return response()->json(['status' => false, 'errors' => ['Teklif Bulunamadı! Kod: ' . $Teklif]], 402);

        return response()->json(['status' => true, 'data' => $Teklif], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Teklif $Teklif
     * @return void
     */
    public function edit(Teklif $Teklif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Teklif $Teklif
     * @return ResponseFactory|Response
     */
    public function update(Request $request, $Teklif)
    {
        $Teklif = Teklif::where('Sirket_Kod', auth('api')->user()->Sirket_Kod)->where('Nosu', $Teklif)->first();
        if (!$Teklif)
            return response()->json(['status' => false, 'errors' => ['Teklif Bulunamadı! Nosu: ' . $Teklif]], 402);

        $input = $request->all();

        $props = $Teklif->column_defaults();
        foreach ($input as $key => $val)
        {
            if (is_null($input[$key]))
                $input[$key] = $props[$key]->default;
        }


        $validator = Validator::make($input, [
            'Nosu' => 'required|string|min:1|max:25',
            'CariAdi' => 'required|string|min:1|max:65',
            'CariHesapTipi' => 'required|string|min:1|max:18',
        ], []);

        if ($validator->errors()->count() || $validator->fails())
            return response(['status' => false, 'errors' => $validator->errors()->toArray()], 402);

        $Teklif->update($input);

        return response(['status' => true,
                            'data' => $Teklif,
                            'title' => $Teklif->CariAdi,
                            'message' => $Teklif->Nosu . ' Güncellendi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Teklif $Teklif
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(Teklif $Teklif)
    {
        $response = DB::select("EXEC Web_TeklifKontrol @spSirket_Kod = ?, @spNosu = ?", [auth('api')->user()->Sirket_Kod, $Teklif->Nosu])[0];

        if (!$response->status)
            return response(['status' => false,
                                'title' => 'Hata',
                                'message' => $Teklif->Nosu . ' - ' . $response->message], 200);

        $response = $Teklif->delete();

        return response(['status' => $response,
                            'title' => 'Başarılı',
                            'message' => $Teklif->CariAdi . ' Teklif Silindi!'
                        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $data = Teklif::where(function ($q) use ($request)
        {
            $q->where('Sirket_Kod', auth('api')->user()->Sirket_Kod);

            if ($request->Nosu && strlen($request->Nosu) > 0)
                $q->where('Nosu', 'like', $request->Nosu . '%');

            if ($request->CariAdi && strlen($request->CariAdi) > 0)
                $q->where('CariAdi', 'like', '%' . $request->CariAdi . '%');

            if ($request->KisaCariAdi && strlen($request->KisaCariAdi) > 0)
                $q->where('KisaCariAdi', 'like', '%' . $request->KisaCariAdi . '%');

            if ($request->KaraListe && strlen($request->KaraListe) > 0)
                $q->where('KaraListe', $request->KaraListe);

            if ($request->AnaYetkiliAdi && strlen($request->AnaYetkiliAdi) > 0)
                $q->where('AnaYetkiliAdi', 'like', '%' . $request->AnaYetkiliAdi . '%');

            if ($request->Sehir && strlen($request->Sehir) > 0)
                $q->where('Sehir', 'like', '%' . $request->Sehir . '%');

            if ($request->Ilce && strlen($request->Ilce) > 0)
                $q->where('Ilce', 'like', '%' . $request->Ilce . '%');

            if ($request->Ulke && strlen($request->Ulke) > 0)
                $q->where('Ulke', 'like', '%' . $request->Ulke . '%');

            if ($request->OzelKod && strlen($request->OzelKod) > 0)
                $q->where('OzelKod1', 'like', '%' . $request->OzelKod . '%');

            if (strlen($request->Aktiflik) > 0)
            {
                if ($request->Aktiflik < 2)
                    $q->where('Aktif', $request->Aktiflik);
            }

        })->orderBy('id', 'asc')->paginate($request->size ?? 10);

        return response()->json(['status' => true, 'data' => $data], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function urun_bazli_liste(Request $request)
    {
        $data = TeklifSatir::whereHas('teklif', function($q) use ($request)
        {
            $q->where('Sirket_Kod', auth('api')->user()->Sirket_Kod);

            if ($request->Nosu && strlen($request->Nosu) > 0)
                $q->where('Nosu', 'like', $request->Nosu . '%');

            if (!($request->Aciklar && $request->Kapalilar && $request->Aciklar == 'E' && $request->Kapalilar == 'E'))
            {
                if ($request->Aciklar && $request->Aciklar == 'E')
                    $q->where('Kapan', 'H');

                if ($request->Kapalilar && $request->Kapalilar == 'E')
                    $q->where('Kapan', 'E');
            }

            if ($request->Iptaller && strlen($request->Iptaller) > 0)
                $q->where('Iptal', 'E');
            else
                $q->where('Iptal', 'H');

        })->paginate($request->size ?? 10);

        return response()->json(['status' => true, 'data' => $data], 200);
    }
}
