<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{

/**
 * @OA\Get(
 *  path="/api/system/users",
 *  summary="Obtener lista de Usuarios",
 *  tags={"Usuarios"},
 *  @OA\Response(response=200, description="Retorna lista de usuarios"),
 *  security={{ "apiAuth": {} }},
 *  @OA\Response(response=401, description="Unauthorized")
 * )
 */
    public function index()
    {
        $users = DB::table('users')->get();
        return response()-> json([
                        'msg' => 'Consulta de usuarios exitosa',
                        'users' => $users
                ]);
    }
}
