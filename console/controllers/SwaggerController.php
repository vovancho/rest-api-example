<?php
/**
 * Created by PhpStorm.
 * User: VOVANCHO
 * Date: 04.04.2018
 * Time: 14:01
 */

namespace console\controllers;


use Yii;
use yii\console\Controller;

class SwaggerController extends Controller
{
    public function actionIndex()
    {
        $swagger = DIRECTORY_SEPARATOR === '/' ? Yii::getAlias('"' . PHP_BINARY . '"' . '@vendor/bin/swagger') : Yii::getAlias('@vendor/bin/swagger.bat');
        $source = Yii::getAlias('@api/controllers');
        $target = Yii::getAlias('@api/web/docs/swagger.json');

        echo '"' . PHP_BINARY . '"' . " \"{$swagger}\" \"{$source}\" --output \"{$target}\"";
        passthru(" \"{$swagger}\" \"{$source}\" --output \"{$target}\"");
    }
}