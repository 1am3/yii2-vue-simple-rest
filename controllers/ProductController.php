<?php

namespace app\controllers;

use app\models\Product;
use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

class ProductController extends ActiveController
{
    public $modelClass = 'app\models\Product';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['update']);
        unset($actions['options']);
        return $actions;
    }

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Product::find(),
            'pagination' => [
                'pageSize' => -1,
            ],
        ]);
    }

    public function actionGenerate()
    {
        $limit = 20;
        $i = 0;
        while($i < $limit)
        {
            $i++;
            $product = new Product();
            $product->name = "Товар $i";
            $product->price = $i * 100;
            $product->save(false);
        }
        $out = ['error' => false];
        die(json_encode($out));
    }
}
