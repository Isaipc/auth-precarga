<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

define("SALT_STR", "florvic");
define("NOM_LEN", "100");
define("PSW_LEN", "9");
define("LOG_LEN", "9");

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function signup(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:' . NOM_LEN,
            'login' => 'required|string|max:' . LOG_LEN . '|unique:inscaptur',
            // 'login' => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'login' => $request->login,
            // 'password' => bcrypt($request->password)
            'password' => crypt(trim($request->password), SALT_STR)
        ]);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string|max:' . LOG_LEN,
            'password' => 'required|string',
            // 'remember_me' => 'boolean'
        ]);

        // $credentials = request(['login', 'password']);

        // if (!Auth::attempt($credentials)) {
        //     return response()->json([
        //         'message' => 'Unauthorized'
        //     ], 401);
        // }

        $pass = crypt(trim($request->password), SALT_STR);
        // dd($pass);

        $attempt = User::where('login', $request->login)
            ->where('password', '=', $pass)
            ->first();

        if ($attempt == null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // dd($attempt);
        // $user = $request->user();

        $tokenResult = $attempt->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json([
            'usuario' => $request->user()->details()
        ]);
    }
}
