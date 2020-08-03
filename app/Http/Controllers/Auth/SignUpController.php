<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class SignUpController extends Controller
{

    public function __invoke(Request $request)
    {

        try {
            $this->validate($request, [
                'name'      => 'required|string',
                'email'     => 'required|email|unique:users',
                'cpf'       => 'required|unique:users|formato_cpf|cpf',
                'phone'     => 'required',
                'terms'     => 'required',
                'password'  => 'required|confirmed|min:8',
                'password_confirmation'  => 'required'
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

            User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'cpf'       => $request->cpf,
                'phone'     => $request->phone,
                'terms'     => $request->terms,
                'password'  => Hash::make($request->password)
            ]);

            return response()->json(
                [
                    'type'    => 'success',
                    'title'   => 'Sucesso!',
                    'message' => 'Usuário Cadastrado.'
                ],
                201
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
