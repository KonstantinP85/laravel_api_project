<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\IndexRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @queryParam page int Field to page (defaults show first). No-example
     * @queryParam limit int Field to limit one page (defaults show 10). No-example
     * @queryParam title string Field to search by (defaults show all). No-example
     * @queryParam product_category_id int Field to limit one page (defaults show all). No-example
     *
     * @param IndexRequest $request
     * @return Response
     */
    public function index(IndexRequest $request): Response
    {
        $parameters = $request->validated();

        if (array_key_exists('limit', $parameters) && $parameters['limit'] !== null) {
            $limit = $parameters['limit'] > 10 ? 10 : $parameters['limit'];
        } else {
            $limit = 10;
        }

        $qb = DB::table('products');

        if (array_key_exists('title', $parameters) && $parameters['title'] !== null) {
            $qb->where('title', 'like', '%' . $parameters['title'] . '%');
        }
        if (array_key_exists('productCategoryId', $parameters) && $parameters['productCategoryId'] !== null) {
            $qb->where('product_category_id', $parameters['productCategoryId']);
        }

        return response($qb->paginate($limit), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        $product = Product::query()->findOrFail($id);

        return response(new ProductResource($product), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     * @bodyParam title string Product title. Example: milk
     * @bodyParam price int Product price. Example: 4
     * @bodyParam product_category_id int Product category. Example: 2
     *
     * @param StoreRequest $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {
        $product = Product::query()->create($request->validated());

        return response(new ProductResource($product), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     * @bodyParam title string Product title. Example: milk
     * @bodyParam price int Product price. Example: 4
     *
     * @param StoreRequest $request
     * @param int $id
     * @return Response
     */
    public function update(StoreRequest $request, int $id): Response
    {
        $product = Product::query()->findOrFail($id);
        $product->update($request->validated());

        return response(new ProductResource($product), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $product = Product::query()->findOrFail($id);
        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
