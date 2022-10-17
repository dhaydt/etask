<?php

namespace App\Helpers;

use App\Models\Staff;
use App\Models\Task;
use GuzzleHttp\Client;

class Helpers
{
    public static function crypt($string, $action = 'e')
    {
        $secret_key = 'mitraglobalkenca';

        // $secret_iv = 'buk1tt1ngg1m4ju4';
        $secret_iv = 'm1tr4Gl0b4lk3nc@';

        $output = false;

        $encrypt_method = 'AES-128-CBC';

        $iv = substr($secret_iv, 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $secret_key, 0, $iv));
        } elseif ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $secret_key, 0, $iv);
        }

        return $output;
    }

    public static function regexUserAgent($ua)
    {
        $ua = is_null($ua) ? $_SERVER['HTTP_USER_AGENT'] : $ua;
        // Enumerate all common platforms, this is usually placed in braces (order is important! First come first serve..)
        $platforms = 'Windows|iPad|iPhone|Macintosh|Android|BlackBerry';

        // All browsers except MSIE/Trident and..
        // NOT for browsers that use this syntax: Version/0.xx Browsername
        $browsers = 'Firefox|Chrome';

        // Specifically for browsers that use this syntax: Version/0.xx Browername
        $browsers_v = 'Safari|Mobile'; // Mobile is mentioned in Android and BlackBerry UA's

        // Fill in your most common engines..
        $engines = 'Gecko|Trident|Webkit|Presto';

        // Regex the crap out of the user agent, making multiple selections and..
        $regex_pat = "/((Mozilla)\/[\d\.]+|(Opera)\/[\d\.]+)\s\(.*?((MSIE)\s([\d\.]+).*?(Windows)|({$platforms})).*?\s.*?({$engines})[\/\s]+[\d\.]+(\;\srv\:([\d\.]+)|.*?).*?(Version[\/\s]([\d\.]+)(.*?({$browsers_v})|$)|(({$browsers})[\/\s]+([\d\.]+))|$).*/i";

        // .. placing them in this order, delimited by |
        $replace_pat = '$7$8|$2$3|$9|${17}${15}$5$3|${18}${13}$6${11}';

        // Run the preg_replace .. and explode on |
        $ua_array = explode('|', preg_replace($regex_pat, $replace_pat, $ua, PREG_PATTERN_ORDER));

        if (count($ua_array) > 1) {
            $return['platform'] = $ua_array[0];  // Windows / iPad / MacOS / BlackBerry
        $return['type'] = $ua_array[1];  // Mozilla / Opera etc.
        $return['renderer'] = $ua_array[2];  // WebKit / Presto / Trident / Gecko etc.
        $return['browser'] = $ua_array[3];  // Chrome / Safari / MSIE / Firefox

        /*
        Not necessary but this will filter out Chromes ridiculously long version
        numbers 31.0.1234.122 becomes 31.0, while a "normal" 3 digit version number
        like 10.2.1 would stay 10.2.1, 11.0 stays 11.0. Non-match stays what it is.
        */

            if (preg_match("/^[\d]+\.[\d]+(?:\.[\d]{0,2}$)?/", $ua_array[4], $matches)) {
                $return['version'] = $matches[0];
            } else {
                $return['version'] = $ua_array[4];
            }
        } else {
            /*
            Unknown browser..
            This could be a deal breaker for you but I use this to actually keep old
            browsers out of my application, users are told to download a compatible
            browser (99% of modern browsers are compatible. You can also ignore my error
            but then there is no guarantee that the application will work and thus
            no need to report debugging data.
             */

            return false;
        }

        // Replace some browsernames e.g. MSIE -> Internet Explorer
        switch (strtolower($return['browser'])) {
        case 'msie':
        case 'trident':
            $return['browser'] = 'Internet Explorer';
            break;
        case '': // IE 11 is a steamy turd (thanks Microsoft...)
            if (strtolower($return['renderer']) == 'trident') {
                $return['browser'] = 'Internet Explorer';
            }
        break;
    }

        switch (strtolower($return['platform'])) {
        case 'android':    // These browsers claim to be Safari but are BB Mobile
        case 'blackberry': // and Android Mobile
            if ($return['browser'] == 'Safari' || $return['browser'] == 'Mobile' || $return['browser'] == '') {
                $return['browser'] = "{$return['platform']} mobile";
            }
            break;
    }

        return $return;
    }

    public static function getUserDetail($id)
    {
        $user = Staff::with('detail', 'user')->find($id);

        return $user;
    }

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
        $tasks = Task::get();

        if ($tasks) {
            foreach ($tasks as $t) {
                $staffs = json_decode($t->staff);
                if ($t['status'] == 'doing') {
                    foreach ($staffs as $s) {
                        if ($s->id == $id) {
                            $user = Staff::where('id', $id)->first();
                            if ($user) {
                                $user->available = 0;
                                $user->save();
                            }
                        }
                    }
                } else {
                    foreach ($staffs as $s) {
                        if ($s->id == $id) {
                            $user = Staff::where('id', $id)->first();
                            if ($user) {
                                $user->available = 1;
                                $user->save();
                            }
                        }
                    }
                }
            }
        } else {
            $user = Staff::find($id);
            if ($user) {
                $user->available = 1;
                $user->save();
            }
        }
    }
}
