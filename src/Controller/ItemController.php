<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\JsonResponseService;
use App\Service\ValidationService;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use App\Factory\ItemFactory;

class ItemController extends ApiController
{
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ItemRepository $itemRepository,
        protected ValidationService $validationService,
        JsonResponseService $jsonResponseService
    ) {
        parent::__construct($jsonResponseService);
    }

    #[Route('/items', name: 'item_get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $items = $this->itemRepository->findBy([], ['name' => 'ASC']);

        return $this->getResponse($items);
    }

    #[Route('/items/{id}', name: 'item_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $item = $this->itemRepository->find($id);

        if ($item == null) {
            return $this->getErrorResponse(['id' => "Item with specified id doesn't exist"]);
        }

        return $this->getResponse($item);
    }

    #[Route('/items', name: 'item_add', methods: ['POST'])]
    public function add(Request $request, ItemFactory $itemFactory): JsonResponse
    {
        $params = $this->getRequestParams($request);
        $category = $this->categoryRepository->find($params['category_id']);

        if ($category == null) {
            return $this->getErrorResponse(['id' => "Category with specified id doesn't exist"]);
        }

        $item = $itemFactory->make(
            $category,
            $params['name'],
            $params['image_url'] ?? ''
        );

        $errors = $this->validationService->validateEntity($item);

        $this->itemRepository->save($item, true);
        
        return $this->getResponse($item, 201);
    }

    #[Route('/items/{id}', name: 'item_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $item = $this->itemRepository->find($id);

        if ($item == null) {
            return $this->getErrorResponse(['id' => "Item with specified id doesn't exist"]);
        }
        $params = $this->getRequestParams($request);
     
        $category = $this->categoryRepository->find($params['category_id']);

        if ($category == null) {
            return $this->getErrorResponse(['id' => "Category with specified id doesn't exist"]);
        }
        $item->setCategory($category);
        $item->setName($params['name']);

        if (empty($params['image_url']) === false) {
            $item->setImageUrl($params['image_url']);
        }

        $errors = $this->validationService->validateEntity($item);

        $this->itemRepository->save($item, true);
        
        return $this->getResponse($item);
    }

    #[Route('/items/{id}', name: 'item_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $item = $this->itemRepository->find($id);

        if ($item == null) {
            return $this->getErrorResponse(['id' => "Item with specified id doesn't exist"]);
        }

        $this->itemRepository->remove($item, true);
        
        return $this->getResponse([], 204);
    }
}
