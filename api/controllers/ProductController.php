<?php
/**
 * Created by PhpStorm.
 * User: VOVANCHO
 * Date: 04.04.2018
 * Time: 10:26
 */

namespace api\controllers;

use domain\forms\ProductForm;
use domain\services\ProductService;
use Yii;
use yii\base\Module;
use yii\web\BadRequestHttpException;

/**
 * @SWG\Swagger(
 *     basePath="/",
 *     host="api.restapi.local",
 *     schemes={"https"},
 *     produces={"application/json","application/xml"},
 *     consumes={"application/json","application/xml"},
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Rest Example API",
 *         description="HTTP JSON API",
 *     ),
 *     @SWG\SecurityScheme(
 *         securityDefinition="OAuth2",
 *         type="oauth2",
 *         flow="password",
 *         tokenUrl="https://api.restapi.local/oauth2/token"
 *     ),
 *     @SWG\SecurityScheme(
 *         securityDefinition="Bearer",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header"
 *     )
 * )
 */
class ProductController extends BaseController
{
    private $service;

    public function __construct($id, Module $module, ProductService $service, array $config = [])
    {
        $this->service = $service;

        parent::__construct($id, $module, $config);
    }

    /**
     * @SWG\Get(
     *     path="/products",
     *     description="Вывод всех продуктов",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Product")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function actionIndex()
    {
        return $this->service->getAll();
    }

    /**
     * @SWG\Post(
     *     path="/products",
     *     description="Добавление нового продукта",
     *     @SWG\Parameter(
     *         description="Наименование",
     *         in="query",
     *         name="name",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         description="Стоимость",
     *         in="query",
     *         name="price",
     *         required=true,
     *         type="number",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function actionCreate()
    {
        $form = new ProductForm();
        if ($form->load(Yii::$app->request->getQueryParams(), '')
            && $form->validate()
        ) {
            try {
                $this->service->create($form);
                Yii::$app->getResponse()->setStatusCode(201);
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage(), null, $e);
            }
        } else {
            return $form->errors;
        }
    }

    /**
     * @SWG\Put(
     *     path="/products/{productId}",
     *     description="Обновление продукта с определенным идентификатором",
     *     @SWG\Parameter(
     *         description="ИД продукта",
     *         in="path",
     *         name="productId",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="Наименование",
     *         in="query",
     *         name="name",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         description="Стоимость",
     *         in="query",
     *         name="price",
     *         required=true,
     *         type="number",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function actionUpdate($id)
    {
        $product = $this->service->find($id);
        $form = new ProductForm($product);

        if ($form->load(Yii::$app->request->getQueryParams(), '')
            && $form->validate()
        ) {
            try {
                $this->service->update($id, $form);
                Yii::$app->getResponse()->setStatusCode(200);
            } catch (\DomainException $e) {
                throw new BadRequestHttpException($e->getMessage(), null, $e);
            }
        } else {
            return $form->errors;
        }
    }

    /**
     * @SWG\Delete(
     *     path="/products/{productId}",
     *     description="Удаление продукта с определенным идентификатором",
     *     @SWG\Parameter(
     *         description="ИД продукта",
     *         in="path",
     *         name="productId",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function actionDelete($id)
    {
        try {
            $this->service->delete($id);
            Yii::$app->getResponse()->setStatusCode(204);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }
}
/**
 * @SWG\Definition(
 *     definition="Product",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="price", type="number"),
 * )
 */