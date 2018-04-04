<?php
/**
 * Created by PhpStorm.
 * User: VOVANCHO
 * Date: 04.04.2018
 * Time: 20:33
 */

namespace domain\repositories;

use domain\models\Product;
use yii\db\ActiveRecord;

class ProductRepository
{
    /**
     * @param $id
     * @return ActiveRecord|Product
     */
    public function find($id)
    {
        if (!$product = Product::findOne($id)) {
            throw new \RuntimeException('Model not found.');
        }

        return $product;
    }

    public function getAll()
    {
        return Product::find()->all();
    }

    public function add(Product $product)
    {
        if (!$product->getIsNewRecord()) {
            throw new \DomainException('Adding existing model.');
        }
        if (!$product->insert(false)) {
            throw new \DomainException('Saving error.');
        }
    }

    public function save(Product $product)
    {
        if ($product->getIsNewRecord()) {
            throw new \DomainException('Adding existing model.');
        }
        if ($product->update(false) === false) {
            throw new \DomainException('Saving error.');
        }
    }

    public function delete(Product $product)
    {
        try {
            return $product->delete() !== false;
        } catch (\Exception $e) {
            throw new \DomainException('Deleting error.', 0, $e);
        }
    }
}