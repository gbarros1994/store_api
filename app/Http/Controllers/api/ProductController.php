<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $product = Product::all('name', 'amount', 'qty_stock');
            if ($product) {
                return response()->json([
                    'status' => 200, 
                    'message' => $product
                ]);
            } else {
                return response()->json([
                    'status' => 400, 
                    'message' => 'Erro nos dados enviados'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500, 
                'message' => 'Erro interno no servidor'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'amount' => 'required',
                'qty_stock' => 'required',
            ]);

            if (!$validator->fails()) {
                $product = Product::create($request->all());
                if ($product) {
                    return response()->json([
                        'status' => 200, 
                        'message' => $product
                    ]);
                } else {
                    return response()->json([
                        'status' => 400, 
                        'message' => 'Erro nos dados enviados'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400, 
                    'message' => 'Erro nos dados enviados'
                ]);
            }   
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500, 
                'message' => 'Erro interno no servidor'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
