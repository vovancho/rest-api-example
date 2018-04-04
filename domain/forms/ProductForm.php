<?php
/**
 * Created by PhpStorm.
 * User: VOVANCHO
 * Date: 04.04.2018
 * Time: 20:48
 */

namespace domain\forms;

use domain\models\Product;
use yii\base\Model;

class ProductForm extends Model
{
    public $name;
    public $price;

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->name = $product->name;
            $this->price = $product->price;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'price'], 'required'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'price' => 'Стоимость',
        ];
    }
}