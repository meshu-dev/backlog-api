<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\SerializeService;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use App\Factory\ItemFactory;

class ItemController extends BaseController
{
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ItemRepository $itemRepository,
        protected ItemFactory $itemFactory,
        SerializeService $serializeService
    ) {
        parent::__construct($serializeService);
    }

    #[Route('/items', name: 'item_get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $items = $this->itemRepository->findAll();

        return $this->getJson($items);
    }

    #[Route('/items/{id}', name: 'item_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $item = $this->itemRepository->find($id);
        
        return $this->getJson($item);
    }

    #[Route('/items', name: 'item_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $params = $this->getRequestJson($request);
        $category = $this->categoryRepository->find($params['category_id']);

        if (!$category) {
            return $this->json(["Category doesn't exist"], 404);
        }

        $item = $this->itemFactory->make($category, $params['name']);

        $this->itemRepository->save($item, true);
        
        return $this->getJson($item, 201);
    }

    #[Route('/items/{id}', name: 'item_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $item = $this->itemRepository->find($id);

        if (!$item) {
            return $this->json(["Item doesn't exist"], 404);
        }
        $params = $this->getRequestJson($request);
     
        $category = $this->categoryRepository->find($params['category_id']);

        if (!$category) {
            return $this->json(["Category doesn't exist"], 404);
        }
        $item->setCategory($category);
        $item->setName($params['name']);

        $this->itemRepository->save($item, true);
        
        return $this->getJson($item);
    }

    #[Route('/items/{id}', name: 'item_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $item = $this->itemRepository->find($id);

        if (!$item) {
            return $this->json(["Item doesn't exist"], 404);
        }

        $this->itemRepository->remove($item, true);
        
        return $this->getJson([], 204);
    }
}
