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
        try {
            $product = DB::select('SELECT
                                        pr.id AS id,
                                        pr.name AS name,
                                        pr.amount AS amount,
                                        pr.qty_stock AS stock,
                                        pu.created_at AS date,
                                        pu.quantity_purchased AS purchased
                                    FROM
                                        products AS pr
                                        LEFT JOIN purchases AS pu ON pr.id = pu.product_id 
                                    WHERE
                                        pr.id = "'.$id.'"
                                    ORDER BY
                                        pu.id DESC
                                    LIMIT 1');

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if($product->delete()) {
                return response()->json(
                    ['message' => 'Produto excluído com sucesso'],
                    200
                );
            } else {
                return response()->json(
                    ['message' => 'Erro nos dados enviados'],
                    400
                );
            }
        } catch (\Throwable $th) {
            return response()->json(
                    ['message' => 'Erro interno no servidor'],
                    500
                );
        }
    }
}
