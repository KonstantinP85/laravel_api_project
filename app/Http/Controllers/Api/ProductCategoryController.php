<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategory\IndexRequest;
use App\Http\Requests\ProductCategory\StoreRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @queryParam page int Field to page (defaults show first). No-example
     * @queryParam limit int Field to limit one page (defaults show 10). No-example
     * @queryParam title string Field to search by (defaults show all). No-example
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

        $qb = DB::table('product_categories');

        if (array_key_exists('title', $parameters) && $parameters['title'] !== null) {
            $qb->where('title', 'like', '%' . $parameters['title'] . '%');
        }

        return response($qb->paginate($limit), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $productCategory = ProductCategory::query()->findOrFail($id);

        return response(new ProductCategoryResource($productCategory), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     * @bodyParam title string ProductCategory title. Example: auto
     * @bodyParam description string ProductCategory title. Example: Description about auto
     *
     * @param StoreRequest $request
     * @return Response
     */
    public function store(StoreRequest $request): Response
    {
        $productCategory = ProductCategory::query()->create($request->validated());

        return response(new ProductCategoryResource($productCategory), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     * @bodyParam title string ProductCategory title. Example: auto
     * @bodyParam description string ProductCategory description. Example: Description about auto
     *
     * @param StoreRequest $request
     * @param int $id
     * @return Response
     */
    public function update(StoreRequest $request, int $id): Response
    {
        $productCategory = ProductCategory::query()->findOrFail($id);
        $productCategory->update($request->validated());

        return response(new ProductCategoryResource($productCategory), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $productCategory = ProductCategory::query()->findOrFail($id);
        $productCategory->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
