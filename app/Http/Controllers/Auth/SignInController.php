<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class SignInController extends Controller
{

    public function __invoke(Request $request)
    {
        try {
            $this->validate($request, [
                'cpf' => 'required|formato_cpf|cpf',
                'password' => 'required|string|min:8',
            ]);
        } catch (Exception $e) {
            return response()->json(
                [
                    'type'    => 'error',
                    'title'   => 'Erro de Validação!',
                    'message' => 'Verifique os campos e tente novamente!',
                    'validation'  => $e->response->original
                ],
                $e->status
            );
        }

        try {

            $credentials = $request->only(['cpf', 'password']);

            if ($token = Auth::attempt($credentials)) {

                return response()->json([
                    'token' => $token
                ], 200);
            }
        } catch (Exception $e) {

            return response()->json(
                [
                    'type'    => 'error',
                    'title'   => 'Acorreu um Erro!',
                    'message' => $e->getMessage()
                ],
                $e->status
            );
        }

        return response()->json(
            [
                'type'    => 'error',
                'title'   => 'Dados Invalidos!',
                'message' => 'Verifique os dados digitados e tente novamente!'
            ],
            401
        );
    }
}
