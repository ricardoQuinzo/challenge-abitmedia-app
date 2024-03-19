<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;


class ServicesController extends Controller
{

       /**
     * @OA\Get(
     *  path="/api/system/services",
     *  summary="Obtener lista de Servicios",
     *  tags={"Servicios"},
     *  @OA\Response(response=200, description="Retorna lista de Servicios"),
     *  security={{ "apiAuth": {} }},
     *  @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $services = Services::all();

        return response()->json([
            'success' => true,
            'message' => 'Lista de Servicios',
            'data' => $services
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/system/services",
     *      operationId="createService",
     *      tags={"Servicios"},
     *      summary="Crear un nuevo servicio",
     *      description="Crea un nuevo servicio con la información proporcionada",
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
     *         description="Nombre del servicio",
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
     *      @OA\Response(
     *          response=201,
     *          description="Servicio creado con éxito",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Servicio creado con éxito"),
     *          ),
     *      ),
     *      @OA\Response(response=422, description="Error de validación"),
     *      security={{ "apiAuth": {} }}
     * )
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku' => 'required|unique:services,sku|digits:10',
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ];

            throw new HttpResponseException(response()->json($response, 422));
        }
        $services =  Services::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Servicio creado con éxito',
            'data' => $services
        ], 201);
    }

    public function show(Services $service)
    {
        return $service;
    }

      /**
     * @OA\Put(
     *      path="/api/system/services/{id}",
     *      operationId="updateService",
     *      tags={"Servicios"},
     *      summary="Actualizar información de un servicio",
     *      description="Actualiza la información de un servicio existente con la información proporcionada",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del servicio a actualizar",
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
     *         description="Nombre del servicio",
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
     *      @OA\Response(
     *          response=200,
     *          description="Servicio actualizado con éxito",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Servicio actualizado con éxito"),
     *          ),
     *      ),
     *      @OA\Response(response=404, description="Servicio no encontrado"),
     *      @OA\Response(response=422, description="Error de validación"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function update(Request $request, $id)
    {

        $service = Services::findOrFail($id);
        $service->update($request->all());

        return response()->json([
            'message' => 'Servicio actualizado correctamente',
            'service' => $service
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/api/system/services/{id}",
     *      operationId="deleteService",
     *      tags={"Servicios"},
     *      summary="Eliminar un servicio",
     *      description="Elimina un servicio existente",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del servicio a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Servicio eliminado con éxito",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Servicio eliminado con éxito"),
     *          ),
     *      ),
     *      @OA\Response(response=404, description="Servicio no encontrado"),
     *      security={{ "apiAuth": {} }}
     * )
     */
    public function destroy($id)
    {
        $service = Services::find($id);
    
        if (!$service) {
            return response()->json([
                'message' => 'El servicio no existe',
            ], 404);
        }
    
        $service->delete();
    
        return response()->json([
            'message' => 'Servicio eliminado correctamente',
        ], 200);
    }
}
