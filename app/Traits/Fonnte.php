<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait Fonnte
{
    protected function send_message(String $target,$messages)
    {
        $headers = [
            'Authorization' => config('fonnte')
        ];

        $url = 'https://api.fonnte.com/send';

        $data = [
            'target' => $target,
            'message' => $messages,
        ];

        $request_data = Http::withHeaders($headers)->post($url,$data);
        return response()->json($request_data);
    }
}
