<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services;


class ServicesController extends Controller
{
    public function index()
    {
        return Services::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|unique:services,sku|max:10',
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        return Services::create($request->all());
    }

    public function show(Services $service)
    {
        return $service;
    }

    public function update(Request $request, Services $service)
    {
        $request->validate([
            'sku' => 'required|unique:services,sku,' . $service->id . '|max:10',
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        $service->update($request->all());

        return $service;
    }

    public function destroy(Services $service)
    {
        $service->delete();

        return response()->json(null, 204);
    }
}
