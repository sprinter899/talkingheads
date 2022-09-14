<?php

namespace app\controllers;

use app\models\Author;
use app\models\BaseModel;
use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\ServerErrorHttpException;

class AuthorsController extends BaseController
{
    /**
     * @var Author
     */
    public $modelClass = 'app\models\Author';

    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['delete']);
        $actions['update']['scenario'] = BaseModel::SCENARIO_UPDATE;
        return $actions;
    }

    public function actionIndex(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Author::find()->select(['name', 'surname', 'born_date']),
            'pagination' => [
                'pageSize' => 15,
            ]
        ]);
    }

    public function actionView($id): array
    {
        return Author::findOne($id)->getWithBooks();
    }

    public function actionStatistic(): array
    {
        return Author::getAllWithBookCnt();
    }

    /**
     * @throws StaleObjectException
     * @throws ServerErrorHttpException
     */
    public function actionDelete($id)
    {

        $model = $this->modelClass::findOne($id);

        $books = $model->getBooks();
        if ($books->count() > 0) {
            Book::deleteAll(['author_id' => $id]);
        }

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }


}