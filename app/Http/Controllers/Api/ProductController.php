<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    private $product;
    private $request;
    private $response;

    public function __construct(Product $product, Request $request, Response $response)
    {
        $this->product = $product;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Display a listing of the products.
     *
     */
    public function index(Request $request)
    {
        $products = $this->product;
        $productsRepository = new ProductRepository($products);

        if($request->has('conditions')) {
            $productsRepository->selectConditions($request->get('conditions'));
        }

        if($request->has('fields')){
            $productsRepository->selectFilter($request->get('fields'));
        }

        return $productsRepository->getResult()->paginate(10);
    }

    /**
     * Store a newly created products in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->all();
        $new_product = $this->product->create($data);

        return response()->json($new_product);
    }

    /**
     * Display the specified products.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);
//        return response()->json($product);
        return new ProductResource($product);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = $this->product->find($id);
        $product->update($request->all());
        return response()->json($product);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->product->find($id);
        $product->delete();
        return response()->json("Produto excluido");
    }
}
