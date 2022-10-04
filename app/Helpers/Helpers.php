<?php

namespace App\Helpers;

use App\Models\Staff;
use App\Models\Task;
use GuzzleHttp\Client;

class Helpers
{
    public static function getToken()
    {
        $secret = config('secret.e-task');
        $api = 'https://apidoc.bukittinggikota.go.id/simpeg/public/api/get-token';

        $client = new Client();

        $body = [
            'secret' => $secret,
        ];

        $response = $client->request('POST', $api, [
            'form_params' => $body,
        ]);

        $status = $response->getStatusCode();
        if ($status == 200) {
            $token = json_decode($response->getBody()->getContents())->data->access_token;

            return $token;
        }

        return response()->json('user not authorized');
    }

    public static function checkAvailable($id)
    {
        $tasks = Task::where('status', 'doing')->get();

        if ($tasks) {
            foreach ($tasks as $t) {
                $staffs = json_decode($t->staff);
                foreach ($staffs as $s) {
                    if ($s->id == $id) {
                        $user = Staff::find($id);
                        $user->available = 0;
                        $user->save();
                    }
                }
            }
        } else {
            $user = Staff::find($id);
            $user->available = 1;
            $user->save();
        }
    }
}
