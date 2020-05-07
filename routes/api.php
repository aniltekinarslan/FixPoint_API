<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'auth', 'as' => '.auth'], function () {
    Route::post('login', ['as' => '.login',               'uses' => 'Auth\AuthController@login'])->middleware(['guest', 'throttle:5,1,login']);
    Route::get('user', ['as' => '.user',                  'uses' => 'Auth\AuthController@user'])->middleware('jwt.auth', 'throttle:60,1,user');
    Route::post('refresh', ['as' => '.refresh',           'uses' => 'Auth\AuthController@refresh'])->middleware('jwt.refresh', 'throttle:10,1,refresh');
});

Route::group(['as' => '.notlogin'], function () {
    Route::apiResource('Tanimlamalar/Sirket', 'Api\Tanimlamalar\SirketController');
    Route::apiResource('Tanimlamalar/MSirket', 'Api\Tanimlamalar\MSirketController');
});

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::middleware('api-token')->group(function ()
    {
        Route::get('StokKontrol/Variant/search', 'Api\StokKontrol\VariantController@search');
        Route::apiResource('StokKontrol/Variant', 'Api\StokKontrol\VariantController');

        Route::get('StokKontrol/StokKarti/search', 'Api\StokKontrol\StokKartiController@search');
        Route::apiResource('StokKontrol/StokKarti', 'Api\StokKontrol\StokKartiController');
        Route::apiResource('StokKontrol/StokHareketleri', 'Api\StokKontrol\StokHareketleriController');

        Route::get('Muhasebe/MCari/getnew', 'Api\Muhasebe\MCariController@getnew');
        Route::get('Muhasebe/MCari/search', 'Api\Muhasebe\MCariController@search');
        Route::apiResource('Muhasebe/MCari', 'Api\Muhasebe\MCariController');
        Route::apiResource('Muhasebe/MCariOzelFiyat', 'Api\Muhasebe\MCariOzelFiyatController');
        Route::get('Muhasebe/HesapPlani/search', 'Api\Muhasebe\HesapPlaniController@search');
        Route::apiResource('Muhasebe/HesapPlani', 'Api\Muhasebe\HesapPlaniController');
        Route::apiResource('Muhasebe/Doviz', 'Api\Muhasebe\DovizController');


        Route::get('Satis/Teklif/urunbazliliste', 'Api\Satis\TeklifController@urun_bazli_liste');
        Route::apiResource('Satis/Teklif', 'Api\Satis\TeklifController');

        Route::apiResource('Tanimlamalar/Kullanici', 'Api\Tanimlamalar\KullaniciController');
        Route::get('Tanimlamalar/ModulTanimlamalari/StokKontrol/MalzemeGrubu/listWithLevel/{groupNo}', 'Api\Tanimlamalar\ModulTanimlamalari\StokKontrol\MalzemeGrubuController@listWithLevel');
        Route::apiResource('Tanimlamalar/ModulTanimlamalari/StokKontrol/MalzemeGrubu', 'Api\Tanimlamalar\ModulTanimlamalari\StokKontrol\MalzemeGrubuController');

        Route::get('Tanimlamalar/ModulTanimlamalari/StokKontrol/Depo/listDepoYerleri', 'Api\Tanimlamalar\ModulTanimlamalari\StokKontrol\DepoController@listDepoYerleri');
        Route::get('Tanimlamalar/ModulTanimlamalari/StokKontrol/Depo/listGirisTurleri', 'Api\Tanimlamalar\ModulTanimlamalari\StokKontrol\DepoController@listGirisTurleri');
        Route::get('Tanimlamalar/ModulTanimlamalari/StokKontrol/Depo/listCikisTurleri', 'Api\Tanimlamalar\ModulTanimlamalari\StokKontrol\DepoController@listCikisTurlerl');
        Route::apiResource('Tanimlamalar/ModulTanimlamalari/StokKontrol/Depo', 'Api\Tanimlamalar\ModulTanimlamalari\StokKontrol\DepoController');

        Route::apiResource('Tanimlamalar/ModulTanimlamalari/StokKontrol/Birim', 'Api\Tanimlamalar\ModulTanimlamalari\StokKontrol\BirimController');
        Route::apiResource('Tanimlamalar/ModulTanimlamalari/StokKontrol/OzelBirim', 'Api\Tanimlamalar\ModulTanimlamalari\StokKontrol\OzelBirimController');
        Route::apiResource('Tanimlamalar/ModulTanimlamalari/StokKontrol/TeminTuru', 'Api\Tanimlamalar\ModulTanimlamalari\StokKontrol\TeminTuruController');
        Route::apiResource('Tanimlamalar/ModulTanimlamalari/StokKontrol/ParcaBilgisi', 'Api\Tanimlamalar\ModulTanimlamalari\StokKontrol\ParcaBilgisiController');
    });
});
