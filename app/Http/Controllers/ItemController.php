<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;
use App\Http\Requests\ItemRequest;
use App\Repositories\ItemRepository;

class ItemController extends Controller
{
    protected $resource = SiteEnvResource::class;

    public function __construct(
        protected ItemRepository $itemRepository
    ) {
    }

    public function add(ItemRequest $request)
    {
        $user = auth()->user();

        $params = $request->all();
        $params['user_id'] = $user->id;

        $row = $this->itemRepository->add($params);

        return new ItemResource($row);
    }

    public function get(Request $request, int $id)
    {
        $row = $this->itemRepository->get($id);

        return new ItemResource($row);
    }

    public function getAll(Request $request)
    {
        $params = $request->all();
        $rows = $this->itemRepository->getAll($params);

        return ItemResource::collection($rows);
    }

    public function edit(ItemRequest $request, int $id)
    {
        $params = $request->all();

        $isUpdated = $this->itemRepository->edit($id, $params);
        $row = null;

        if ($isUpdated == true) {
            $row = $this->itemRepository->get($id);
        }

        return new ItemResource($row);
    }

    public function delete(Request $request, int $id)
    {
        $this->itemRepository->delete($id);

        return response()->json([], 204);
    }
}
