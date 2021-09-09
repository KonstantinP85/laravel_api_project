<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\IndexRequest;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @queryParam page int Field to page (defaults show first). No-example
     * @queryParam limit int Field to limit one page (defaults show 10). No-example
     * @queryParam status string Field to search by (defaults show all). No-example
     * @queryParam client_id int Field to search by (defaults show all). No-example
     * @queryParam product_id int Field to search by (defaults show all). No-example
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

        $qb = DB::table('orders');

        if (array_key_exists('status', $parameters) && $parameters['status'] !== null) {
            $qb->where('status', 'like', '%' . $parameters['status'] . '%');
        }
        if (array_key_exists('client_id', $parameters) && $parameters['client_id'] !== null) {
            $qb->where('client_id', $parameters['client_id']);
        }
        if (array_key_exists('product_id', $parameters) && $parameters['product_id'] !== null) {
            $qb->where('product_id', $parameters['product_id']);
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
        $order = Order::query()->findOrFail($id);

        return response(new OrderResource($order), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     * @bodyParam product_id int Order product. Example: 2
     * @bodyParam client_id int Order client. Example: 2
     *
     * @param StoreRequest $request
     * @return Response
     */
    public function store(StoreRequest $request): Response
    {
        $inputData = $request->validated();
        $order = Order::query()->create([
            'status' => 'new',
            'client_id' => $inputData['client_id'],
            'product_id' => $inputData['product_id'],
        ]);

        return response(new OrderResource($order), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     * @bodyParam status string Order status. Example: cancelled
     *
     * @param UpdateRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateRequest $request, int $id): Response
    {
        $order = Order::query()->findOrFail($id);
        $order->update($request->validated());

        return response(new OrderResource($order), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $order = Order::query()->findOrFail($id);
        $order->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
