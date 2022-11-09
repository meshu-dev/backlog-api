<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\SerializeService;
use App\Repository\CategoryRepository;
use App\Factory\CategoryFactory;

class CategoryController extends BaseController
{
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected CategoryFactory $categoryFactory,
        SerializeService $serializeService
    ) {
        parent::__construct($serializeService);
    }

    #[Route('/categories', name: 'category_get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $categories = $this->categoryRepository->findAll();

        return $this->getJson($categories);
    }

    #[Route('/categories/{id}', name: 'category_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        
        return $this->getJson($category);
    }

    #[Route('/categories', name: 'category_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $params = $this->getRequestJson($request);
        $category = $this->categoryFactory->make($params['name']);

        $this->categoryRepository->save($category, true);
        
        return $this->getJson($category, 201);
    }

    #[Route('/categories/{id}', name: 'category_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->json(["Category doesn't exist"], 404);
        }
        $params = $this->getRequestJson($request);
        $category->setName($params['name']);

        $this->categoryRepository->save($category, true);
        
        return $this->getJson($category);
    }

    #[Route('/categories/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->json(["Category doesn't exist"], 404);
        }

        $this->categoryRepository->remove($category, true);
        
        return $this->getJson([], 204);
    }
}
