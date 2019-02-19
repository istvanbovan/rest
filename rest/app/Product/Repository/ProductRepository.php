<?php

namespace App\Product\Repository;

use App\Product\Storage\Product;
use Exception;

class ProductRepository
{
    /** @var Product */
    private $model;

    /** @param Product $model */
    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $id
     * @return Product|null
     */
    public function find(string $id) : ?Product
    {
        return $this->model->find($id);
    }

    /**
     * @param string $id
     * @return void
     * @throws Exception
     */
    public function delete(string $id) : void
    {
        $model = $this->find($id);

        if (empty($model)) {
            return;
        }

        $model->delete();
    }
}
