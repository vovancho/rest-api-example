<?php
/**
 * Created by PhpStorm.
 * User: VOVANCHO
 * Date: 04.04.2018
 * Time: 20:38
 */

namespace domain\services;


use domain\models\Product;
use domain\forms\ProductForm;
use domain\repositories\ProductRepository;

class ProductService
{
    private $products;

    public function __construct(
        ProductRepository $products
    )
    {
        $this->products = $products;
    }

    public function getAll()
    {
        return $this->products->getAll();
    }

    public function find($id)
    {
        return $this->products->find($id);
    }

    public function create(ProductForm $form)
    {
        $product = Product::create($form);

        if (!$product->validate()) {
            throw new \DomainException('Validate error');
        }

        $this->products->add($product);
    }

    public function update($id, ProductForm $form)
    {
        $product = $this->products->find($id);
        $product->edit($form);

        if (!$product->validate()) {
            throw new \DomainException('Validate error');
        }

        $this->products->save($product);
    }

    public function delete($id)
    {
        $product = $this->find($id);
        $this->products->delete($product);
    }
}