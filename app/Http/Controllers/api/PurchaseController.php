<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
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
            $product = Product::find($request['product_id']);

            //VERIFICA SE EXISTE PRODUTO
            if ($product) {
                //VERIFICA SE HA EM ESTOQUE
                if ($request['quantity_purchased'] <= $product['qty_stock']) { 
                    $content = array (  
                        'amount' => $product['amount'] * $request['quantity_purchased'],
                        'card'   => array (
                            'owner'           => isset($request->card['owner']) ? $request->card['owner'] : '',
                            'card_number'     => isset($request->card['card_number']) ? $request->card['card_number'] : '',
                            'date_expiration' => isset($request->card['date_expiration']) ? $request->card['date_expiration'] : '',
                            'brand'           => isset($request->card['brand']) ? $request->card['brand'] : '',
                            'cvv'             => isset($request->card['cvv']) ? $request->card['cvv'] : '',
                            'flag'            => isset($request->card['flag']) ? $request->card['flag'] : '',
                        )
                    );
            
                    $response = $this->shopping(json_encode($content), "POST");
                    //VERIFICA SE HOUVE RESPOSTA
                    if (json_decode($response)->status == 200) {
                        $purchase = Purchase::create([
                            'product_id'         => $request['product_id'],
                            'quantity_purchased' => $product['amount'] * $request['quantity_purchased'],
                            'owner'              => $request->card['owner'],
                            'card_number'        => $request->card['card_number'],
                            'date_expiration'    => $request->card['date_expiration'],
                            'brand'              => $request->card['brand'],
                            'cvv'                => $request->card['cvv'],
                            'flag'               => $request->card['flag'],
                        ]);

                        //VERIFICA SE FOI CRIADO NA TABELA ANTES DE DAR O UPDATE NO ESTOQUE
                        if ($purchase) {
                            Product::where('id', $request['product_id'])->update(['qty_stock' => $product['qty_stock'] - $request['quantity_purchased']]);
                            return $response;
                        } else {
                            return response()->json([
                                'status' => 400, 
                                'message' => 'Erro nos dados enviados'
                            ]);
                        }
                    } else {
                        return $response;
                    }
                } else {
                    return response()->json([
                        'status' => 400, 
                        'message' => 'Não há estoque suficiente'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400, 
                    'message' => 'Não há estoque suficiente'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500, 
                'message' => 'Erro interno no servidor'
            ]);
        }
        
    }

    public function shopping($content, $type)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.devhp.com.br/test/gateway/shopping",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $content,
            CURLOPT_HTTPHEADER => array(
                "x-auth-token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9bOjoxXVwvZHVnYV9hcGlcLyIsImF1ZCI6Imh0dHA6XC9cL1s6OjFdXC9kdWdhX2FwaVwvIiwiaWF0IjoxNTg3NzM2Njk2LCJzdWIiOiIzMTE0In0.z4mEO3Jeem9KH0_En2RwnJekUmIUOc3t0aIUzrV1uO0",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
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
