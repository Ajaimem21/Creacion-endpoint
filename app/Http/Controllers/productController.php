<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{
    public function index()
    {
        $products = Product::all();

        if($products->isEmpty()){
            $data = ['message' => 'No se encontraron productos',
                     'status'=> 404];
            return response()->json($data, 404);
        }

        return response()->json($products, 200);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'category' => 'required',
        ]);

        if($validator-> fails()){
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        if (!$product) {
            $data = [
                'message' => 'Error al crear el producto',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'product' => $product,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'product' => $product,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        
        $product->delete();

        $data = [
            'message' => 'Producto eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function update(Request $request, $id)
    {
        $product = product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->category = $request->category;

        $product->save();

        $data = [
            'message' => 'Producto actualizado',
            'product' => $product,
            'status' => 200
        ];

        return response()->json($data, 200);

    }
}
