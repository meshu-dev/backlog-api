<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\JsonResponseService;
use App\Repository\CategoryRepository;
use App\Factory\CategoryFactory;
use App\Service\ValidationService;

class CategoryController extends ApiController
{
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ValidationService $validationService,
        JsonResponseService $jsonResponseService
    ) {
        parent::__construct($jsonResponseService);
    }

    #[Route('/categories', name: 'category_get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $categories = $this->categoryRepository->findBy([], ['name' => 'ASC']);

        return $this->getResponse($categories);
    }

    #[Route('/categories/{id}', name: 'category_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        
        if ($category == null) {
            return $this->getErrorResponse(['id' => "Category with specified id doesn't exist"]);
        }

        return $this->getResponse($category);
    }

    #[Route('/categories', name: 'category_add', methods: ['POST'])]
    public function add(Request $request, CategoryFactory $categoryFactory): JsonResponse
    {
        $params = $this->getRequestParams($request);
        $category = $categoryFactory->make($params['name']);

        $errors = $this->validationService->validateEntity($category);

        $this->categoryRepository->save($category, true);
        
        return $this->getResponse($category, 201);
    }

    #[Route('/categories/{id}', name: 'category_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if ($category == null) {
            return $this->getErrorResponse(['id' => "Category with specified id doesn't exist"]);
        }
        $params = $this->getRequestParams($request);
        $category->setName($params['name']);

        $errors = $this->validationService->validateEntity($category);

        $this->categoryRepository->save($category, true);
        
        return $this->getResponse($category);
    }

    #[Route('/categories/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if ($category == null) {
            return $this->getErrorResponse(['id' => "Category with specified id doesn't exist"]);
        }

        $this->categoryRepository->remove($category, true);
        
        return $this->getResponse([], 204);
    }
}
