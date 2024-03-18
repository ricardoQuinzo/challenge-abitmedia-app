<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','refresh','logout']]);
    }

    /**
    * @OA\Post(
     *     path="/api/auth/register",
     *   tags={"Registro"},
     *     summary="Regitrar un nuevo usuario",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nombre del usuario",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Correo Electrónico",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Contraseña de Usuario",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *  @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="Verificar Contraseña de Usuario",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="Usuario registrado correctamente"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'Usuario creado correctamente',
            'user' => $user
        ], 201);
    }

    /**
    * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Iniciar Sesión"},
     *     summary="Iniciar sesion con usuario registrado",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Correo Electrónico",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Contraseña de Usuario",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="Token obtenido"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */

    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }


    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * @OA\Get(
     *  path="/api/auth/user-profile",
     *  summary="Perfil de Usuario Actual",
     *  tags={"Perfil de Usuario Registrado"},
     *  @OA\Response(response=200, description="Retorna el perfil de usuarios"),
     *  security={{ "apiAuth": {} }},
     *  @OA\Response(response=401, description="Unauthorized")
     * )
     */

    public function userProfile() {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }



}