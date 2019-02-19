<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Product\Storage\Product;
use App\Product\Repository\ProductRepository;
use Exception;

class ProductController extends Controller
{
    /** @var ProductRepository */
    private $productRepo;

    /**
     * @param ProductRepository $productRepo
     * @return void
     */
    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /** @return JsonResponse */
    public function getProducts() : JsonResponse
    {
        return new JsonResponse(Product::all(), Response::HTTP_OK);
    }

    /**
     * @param int $productId
     * @return JsonResponse
     */
    public function getProduct(int $productId) : JsonResponse
    {
        $model = $this->productRepo->find($productId);

        if (empty($model)) {
            return new JsonResponse(sprintf('No product was found with the ID %d', $productId), Response::HTTP_OK);
        }

        return new JsonResponse($model, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createProduct(Request $request) : JsonResponse
    {
        try {
            //Assuming that the payload has a valid structure
            $payload = $request->json()->all();

            $model = new Product();
            $model->name = $payload['name'];
            $model->sku = $payload['sku'];
            $model->description = $payload['description'];
            $model->image_path = $payload['image_path'];
            $model->save();

            return new JsonResponse('Product was created successfully!', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse(
                sprintf('Unable to create product! Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param Request $request
     * @param int $productId
     * @return JsonResponse
     */
    public function updateProduct(Request $request, int $productId) : JsonResponse
    {
        try {
            $model = $this->productRepo->find($productId);

            if (empty($model)) {
                return new JsonResponse(sprintf('No product was found with the ID %d', $productId), Response::HTTP_OK);
            }

            $payload = $request->json()->all();

            if (isset($payload['name'])) {
                $model->name = $payload['name'];
            }

            if (isset($payload['sku'])) {
                $model->sku = $payload['sku'];
            }

            if (isset($payload['description'])) {
                $model->description = $payload['description'];
            }

            if (isset($payload['image_path'])) {
                $model->name = $payload['image_path'];
            }

            $model->save();

            return new JsonResponse('Product was updated successfully!', Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(
                sprintf('Unable to update product! Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param int $productId
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteProduct(int $productId) : JsonResponse
    {
        $model = $this->productRepo->find($productId);

        if (empty($model)) {
            return new JsonResponse(sprintf('No product was found with the ID %d', $productId), Response::HTTP_OK);
        }

        $model->delete();

        return new JsonResponse('Product was deleted successfully!', Response::HTTP_OK);
    }
}
