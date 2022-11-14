<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class AuthHelpers
{
    public static function getStaff($nip)
    {
        $token = Helpers::getToken();
        $key = $token;

        $main_api = 'https://apidoc.bukittinggikota.go.id/simpeg/public/api';

        $api = $main_api.'/data_pegawai';

        $header = [
            'Authorization' => 'Bearer'.$key,
        ];

        try {
            $client = new Client();

            $response = $client->request('POST', $api, [
                'headers' => $header,
                'form_params' => ['nip' => $nip],
            ]);

            $status = $response->getStatusCode();

            session()->put('token', $key);

            if ($status == 200) {
                $resp = json_decode($response->getBody()->getContents())->api_status;

                if ($resp == 0) {
                    $data = [
                        'code' => 404,
                        'message' => 'Staff tidak ditemukan',
                    ];

                    return $data;
                } else {
                    $data = [
                        'code' => 200,
                        'data' => json_decode($response->getBody())->data,
                    ];

                    return $data;
                }
            }
        } catch (ClientException $e) {
            AuthHelpers::getStaff($nip);
        }
    }

    public static function getPresensi($nip)
    {
        $token = Helpers::getTokenPresensi();
        $key = $token;

        $main_api = 'https://apidoc.bukittinggikota.go.id/presensi/public/api';

        $api = $main_api.'/pengguna_presensi';

        $header = [
            'Authorization' => 'Bearer'.$key,
        ];

        try {
            $client = new Client();

            $response = $client->request('POST', $api, [
                'headers' => $header,
                'form_params' => ['userinfo_id' => $nip],
            ]);

            $status = $response->getStatusCode();

            session()->put('token', $key);

            if ($status == 200) {
                $resp = json_decode($response->getBody()->getContents())->api_status;

                if ($resp == 0) {
                    $data = [
                        'code' => 404,
                        'message' => 'Staff tidak ditemukan',
                    ];

                    return $data;
                } else {
                    $data = [
                        'code' => 200,
                        'data' => json_decode($response->getBody())->data,
                    ];

                    return $data;
                }
            }
        } catch (ClientException $e) {
            AuthHelpers::getStaff($nip);
        }
    }
}
