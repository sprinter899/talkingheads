<?php

namespace app\controllers;

use app\models\Author;
use app\models\BaseModel;
use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class BooksController extends BaseController
{
    public $modelClass = 'app\models\Book';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        $actions['update']['scenario'] = BaseModel::SCENARIO_UPDATE;
        return $actions;
    }

    public function actionIndex(): ArrayDataProvider
    {
        $model = new Book();
        $items = $model->getWithAuthor();

        $data = [];
        $i=0;
        foreach ($items as $item) {
            $data[$i]['name'] = $item->name;
            $data[$i]['year_pub'] = $item->year_pub;
            $data[$i]['author'] = $item->author->fullname;
            $i++;
        }

        return new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 15,
            ]
        ]);

    }

    public function actionCreate()
    {
        $model = new $this->modelClass();
        $response = Yii::$app->getResponse();
        $params = Yii::$app->getRequest()->getBodyParams();

        if (!isset($params['author'])) {
            $response->setStatusCode(422);
        }

        $authorParams = $params['author'];
        $author = Author::find()
            ->where($authorParams)
            ->one();

        if (!$author) {
            $author = new Author();
            $author->load($authorParams);
            $author->save();
        }

        $params['author_id'] = (string)$author['_id'];

        unset($params['author']);

        $model->load($params, '');
        if ($model->save()) {
            $response->setStatusCode(201);
            $id = implode(',', $model->getPrimaryKey(true));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

    }



}