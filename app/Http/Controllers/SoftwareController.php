<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Software;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SoftwareController extends Controller
{
    public function index()
    {
        $software = Software::with('so')->get();

        return response()->json([
            'success' => true,
            'message' => 'Lista de Software con SO',
            'data' => $software
        ]);
    }

    public function store(Request $request)
    {
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

    public function update(Request $request, $id)
    {
        // Validación de los datos de entrada
        $request->validate([
            'sku' => 'required|unique:software,sku,' . $id . '|digits:10',
            'name' => 'required',
            'price' => 'required|numeric',
            'id_so' => 'required|exists:so,id', // Asegúrate de que el id_so exista en la tabla so
        ]);

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
