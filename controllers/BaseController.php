<?php

namespace app\controllers;

class BaseController extends \yii\rest\ActiveController
{

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::class,
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public $enableCsrfValidation = false;

}