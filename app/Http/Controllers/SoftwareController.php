<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Software;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SoftwareController extends Controller
{

    /**
    
     * @OA\Get(
     *  path="/api/system/software",
     *  tags={"Software"},
     *  summary="Obtener lista de Software",   
     *  @OA\Response(response=200, description="Retorna lista de SOFTWARE"),
     *  security={{ "apiAuth": {} }},
     *  @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        //buscar el sistema operativo
        $software = Software::with('so')->get();

        // retornar la vista con los datos del software
        return response()->json([
            'success' => true,
            'message' => 'Lista de Software con SO',
            'data' => $software
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/system/software",
     *      operationId="createSoftware",
     *      tags={"Software"},
     *      summary="Crear un nuevo software",
     *      description="Crea un nuevo software con la información proporcionada",
     *       @OA\Parameter(
     *         name="sku",
     *         in="query",
     *         description="SKU",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nombre del Software",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     * 
     *      @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="Precio",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     
     *      @OA\Parameter(
     *         name="id_so",
     *         in="query",
     *         description="Sistema operativo (1 = Windows, 2 = MAC, 3 = Linux)",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="Software creado con éxito",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Software creado con éxito"),
     *          ),
     *      ),
     *      @OA\Response(response=422, description="Error de validación"),
     *      security={{ "apiAuth": {} }}
     * )
     */

    public function store(Request $request)
    {
        // Validar que vengan todos los campos requer
        $validator = Validator::make($request->all(), [
            'sku' => 'required|unique:software,sku|digits:10',
            'name' => 'required',
            'price' => 'required|numeric',
            'id_so' => 'required',
        ]);

        if ($validator->fails()) {
            // Si la validación falla, lanzar una excepción HttpResponseException
            $response = [
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ];

            throw new HttpResponseException(response()->json($response, 422));
        }

        // Si la validación es exitosa, proceder con la creación del registro
        $software = Software::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Software creado con éxito',
            'data' => $software
        ], 201);
    }

    public function show($id)
    {
        $software = Software::with('so')->find($id);
    
        if (!$software) {
            return response()->json([
                'success' => false,
                'message' => 'Software no encontrado',
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Detalle del software con información de SO',
            'data' => $software
        ]);
    }

    /**
     * @OA\Put(
     *      path="/api/system/software/{id}",
     *      operationId="updateSoftware",
     *      tags={"Software"},
     *      summary="Actualizar información de un software",
     *      description="Actualiza la información de un software existente con la información proporcionada",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del software a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         name="sku",
     *         in="query",
     *         description="SKU",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nombre del Software",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="Precio",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="id_so",
     *         in="query",
     *         description="Sistema operativo (1 = Windows, 2 = MAC, 3 = Linux)",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Software actualizado con éxito",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Software actualizado con éxito"),
     *          ),
     *      ),
     *      @OA\Response(response=404, description="Software no encontrado"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    
    public function update(Request $request, $id)
    {
        // Buscar el registro a actualizar
        $software = Software::findOrFail($id);
        // Actualizar el registro
        $software->update($request->all());

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'Software actualizado con éxito',
            'data' => $software
        ]);
    }


        /**
     * @OA\Delete(
     *      path="/api/system/software/{id}",
     *      operationId="deleteSoftware",
     *      tags={"Software"},
     *      summary="Eliminar un software",
     *      description="Elimina un software existente",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del software a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Software eliminado con éxito",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Software eliminado con éxito"),
     *          ),
     *      ),
     *      @OA\Response(response=404, description="Software no encontrado"),
     *      security={{ "apiAuth": {} }}
     * )
     */

    public function destroy($id)
    {
        // Buscar el registro a eliminar
        $software = Software::find($id);

        // Verificar si el registro existe
        if (!$software) {
            return response()->json([
                'success' => false,
                'message' => 'Software no encontrado',
            ], 404);
        }

        // Eliminar el registro
        $software->delete();

        // Retornar respuesta
        return response()->json([
            'success' => true,
            'message' => 'Software eliminado con éxito',
        ]);
    }
}
