<?php

namespace App\Http\Controllers;

use App\Info;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * InfoController constructor.
     */
    public function __construct()
    {
//        data pro výstup do API
        $this->pushData = [
            [
                'route'     => [
                    'PUT',
                    '/api/log',
                ],
                'what'      => [
                    'what',
                    0,
                ],
                'tags'      => [
                    'tags',
                    'string',
                ],
                'timestamp' => [
                    'datetime',
                    'fullDate',
                ],
            ],
            [
                'route'     => [
                    'POST',
                    '/api/events/create',
                ],
                'what'      => [
                    'id',
                    1,
                ],
                'tags'      => [
                    'type',
                    2,
                ],
                'timestamp' => [
                    'timestamp',
                    'timestamp',
                ],
            ],
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pushInfo(Request $request)
    {
//         zkontrolujeme, zda jsme přes Request obdrželi potřebná data
        $this->validate($request, [
            'what' => 'required',
            'tags' => 'required',
            'timestamp' => 'required'
        ]);

//         zapíšeme obdržená data do tabulky
        $pushInfo = Info::create($request->all());

//        načteme VEŠKERÁ data z tabulky
        $infoData = response()->json(Info::all());

        $newData = [];
//        projdeme obdržená data
        foreach ($infoData as $infoItem) {
//            všechna data připravíme a odešleme na jednotlivá API
            foreach ($this->pushData as $data) {
                $route = $data['route'][0];
                $url = $data['route'][1];
                if (0 == $data['what'][1]) {
                    $newData[$data['what'][0]] = $infoItem['what'];
                }
                else {
                    $temp = explode('-', $infoItem['what']);
                    $newData[$data['what'][0]] = $temp[1];
                }
                if ('string' == $data['tags'][1]) {
                    $newData[$data['tags'][0]] = $infoItem['tags'];
                }
                else {
                    $newData[$data['tags'][0]] = $data['tags'][1];
                }
                if ('datetime' == $data['timestamp'][1]) {
                    $newData[$data['timestamp'][0]] = date('Y-m-d H:i:s', $infoItem['timestamp']);
                }
                else {
                    $newData[$data['timestamp'][0]] = $infoItem['timestamp'];
                }
            }

//            ODesíláme do API
            $response = $this->call($route, $url, $newData);
//            Pokud je správně zapsáno, aktuální řádek smažeme
            if ($this->assertEquals(200, $response->status())) {
                Info::findOrFail($infoItem['id'])->delete();
            }
        }

        return response()->json($newData, 201);
    }
}