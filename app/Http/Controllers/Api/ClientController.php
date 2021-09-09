<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\IndexRequest;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     * @queryParam page int Field to page (defaults show first). No-example
     * @queryParam limit int Field to limit one page (defaults show 10). No-example
     * @queryParam name string Field to search by (defaults show all). No-example
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

        $qb = DB::table('clients');

        if (array_key_exists('name', $parameters) && $parameters['name'] !== null) {
            $qb->where('name', 'like', '%' . $parameters['name'] . '%');
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
        $client = Client::query()->findOrFail($id);

        return response(new ClientResource($client), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     * @bodyParam name string Client name. Example: Nick
     *
     * @param StoreRequest $request
     * @return Response
     */
    public function store(StoreRequest $request): Response
    {
        $client = Client::query()->create($request->validated());

        return response(new ClientResource($client), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     * @bodyParam name string Client name. Example: Nick
     *
     * @param StoreRequest $request
     * @param int $id
     * @return Response
     */
    public function update(StoreRequest $request, int $id): Response
    {
        $client = Client::query()->findOrFail($id);
        $client->update($request->validated());

        return response(new ClientResource($client), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $product = Client::query()->findOrFail($id);
        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
