<?php

namespace domain\models;

use domain\forms\ProductForm;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property string $name
 * @property string $price
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price'], 'required'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'price' => 'Стоимость',
        ];
    }

    public static function create(ProductForm $form)
    {
        return new static([
            'name' => $form->name,
            'price' => $form->price,
        ]);
    }

    public function edit(ProductForm $form)
    {
        $this->name = $form->name;
        $this->price = $form->price;
    }
}
