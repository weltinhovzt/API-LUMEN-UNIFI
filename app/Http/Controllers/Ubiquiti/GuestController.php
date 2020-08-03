<?php

namespace App\Http\Controllers\Ubiquiti;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Guest;
use UniFi_API\Client;
use Exception;

class GuestController extends Controller
{

    public function __invoke(Request $request)
    {
        try {
            $this->validate($request, [
                'ap'    => 'required|string',
                'mac'   => 'required|string',
                'ssid'  => 'required|string'
            ]);
        } catch (Exception $e) {
            return response()->json(
                [
                    'type'    => 'error',
                    'title'   => 'Parâmetros ausentes!',
                    'message' => 'Verifique se você esta no wifi do Sicoob Credivaz.',
                    'validation'  => $e->response->original
                ],
                $e->status
            );
        }

        try {
            $unifi = new Client(
                config('unifi.username'), 
                config('unifi.password'), 
                config('unifi.base_uri'), 
                config('unifi.site'), 
                config('unifi.version'), 
                config('unifi.verify')
            );
            $unifi->login();
            $unifi->authorize_guest($request->mac, 480);
            $unifi->logout();
        } catch (Exception $e) {

            return response()->json(
                [
                    'type'    => 'error',
                    'title'   => 'Erro ao liberar a internet!',
                    'message' => $e->getMessage()
                ],
                409
            );
        }

        try {

            Guest::create([
                'user_id'   => Auth::user()->id,
                'ap'        => $request->ap,
                'mac'       => $request->mac,
                'ssid'      => $request->ssid,
                'ip'        => $request->ip(),
                'minutes'   => 480
            ]);

            return response()->json(
                [
                    'type'    => 'success',
                    'title'   => 'Internet liberada!',
                    'message' => 'Agora você tem 8 horas de internet.'
                ],
                200
            );
        } catch (Exception $e) {

            return response()->json(
                [
                    'type'    => 'error',
                    'title'   => 'Acorreu um Erro!',
                    'message' => $e->getMessage()
                ],
                409
            );
        }
    }
}
