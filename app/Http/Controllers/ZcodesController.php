<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zcode;
use Illuminate\Support\Facades\Redis;

class ZcodesController extends Controller
{

    /**
     * Format the response when the result comes from redis
     */
    private function formatResponse($zipCode, $id)
    {
        $settlements = $this->formatSettlements($zipCode['settlements']);

        return response()->json([
            'zip_code' => $id,
            'locality' => html_entity_decode($zipCode['locality'], ENT_QUOTES | ENT_HTML401, "utf-8"),
            'federal_entity' => [
                'key' => intVal($zipCode['state_code']),
                'name' => html_entity_decode($zipCode['state'], ENT_QUOTES | ENT_HTML401, "utf-8"),
                'code' => $zipCode['zip_code_key']
            ],
            'settlements' => $settlements,
            'municipality' => [
                'key' => intVal($zipCode['municipality_code']),
                'name' => html_entity_decode($zipCode['municipality'], ENT_QUOTES | ENT_HTML401, "utf-8"),
            ],
        ]);
    }

    /**
     * Format the settlements array
     * as specified on challenge example
     */
    private function formatSettlements($data)
    {
        $settlements = [];
        foreach ($data as $key => $value) {
            $settlements[$key] = [
                'key' => intVal($value['id_settlement']),
                'name' => $value['settlement'],
                'zone_type' => $value['zone_type'],
                'settlement_type' => [
                    'name' => ucfirst(strtolower($value['settlement_type'])),
                ]
            ];
        }

        return $settlements;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (!is_numeric($id) || $id < 1) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Invalid Zip Code',
            ]);
        }

        $cachedZipCode = Redis::get('code_' . $id);


        if (isset($cachedZipCode)) {
            $zipCode = json_decode($cachedZipCode, true);
            return $this->formatResponse($zipCode, $id);
        } else {
            $zipCode = Zcode::where('zip_code', $id)->with('settlements')->first();
            if ($zipCode === null) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Zip Code Not Found.',
                ]);
            }
            $zipCode = $zipCode->toArray();
            //store in cache
            Redis::set('code_' . $id, json_encode($zipCode));
            return $this->formatResponse($zipCode, $id);
        }
    }
}
