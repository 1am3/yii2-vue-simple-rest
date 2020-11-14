<?php

namespace app\controllers;

use app\models\Product;
use app\models\Purchase;
use app\models\PurchaseProduct;
use Yii;
use yii\rest\ActiveController;
use yii\base\Request;
use GuzzleHttp\Client;

class PurchaseController extends ActiveController
{
    public $modelClass = 'app\models\Purchase';

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions);
        return [];
    }

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function actionCreate()
    {
        $total = 0;
        $out = [];
        $idProducts = Yii::$app->request->post('id_product');
        if(!empty($idProducts))
        {
            foreach ($idProducts as $id)
            {
                $product = Product::findOne(['id' => $id]);
                if($product)
                {
                    $total += $product['price'];
                } else {
                    $out['error'] = true;
                    $out['reason'] = "Product $id not found";
                }
            }
        } else {
            $out['error'] = true;
            $out['reason'] = 'Products not selected';
        }
        if(empty($out['error']))
        {
            $purchase = new Purchase();
            $purchase->total = $total;
            if($purchase->save())
            {
                foreach($idProducts as $id)
                {
                    $purchaseProduct = new PurchaseProduct();
                    $purchaseProduct->id_product = $id;
                    $purchaseProduct->id_purchase = $purchase->id;
                    $purchaseProduct->save();
                }
                $out['id_purchase'] = $purchase->id;
                $out['sum'] = round($total,2);
            }
        }
        return $out;
    }

    public function actionPay()
    {
        $out = [];
        $idPurchase = Yii::$app->request->post('id_purchase');
        $sum = Yii::$app->request->post('sum');
        if($idPurchase && $sum)
        {
            $purchase = Purchase::findOne(['id' => $idPurchase, 'status' => 'Новый']);
            if($purchase)
            {
                if( round( $sum,2) === round( $purchase->total,2) )
                {
                    if($this->checkPayment())
                    {
                        $purchase->status = 'Оплачено';
                        $purchase->save();
                        $out['status'] = 'Ok';
                    }
                }
            } else {
                $out['error'] = true;
                $out['reason'] = 'Purchase not found or already payment';
            }
        } else {
            $out['error'] = true;
            $out['reason'] = 'Id purchase or sum is wrong';
        }
        return $out;
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function checkPayment()
    {
        $result = false;
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://ya.ru');
            $status = $response->getStatusCode();
            if($status == 200)
            {
                $result = true;
            }
        } catch (GuzzleException $e)
        {

        }
        return $result;
    }
}
