<?php

namespace app\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;

class Book extends BaseModel
{

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['name', 'year_pub', 'description',];
        $scenarios[self::SCENARIO_UPDATE] = ['name', 'year_pub', 'description',];
        return $scenarios;
    }


    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'book';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes(): array
    {
        return ['_id', 'name', 'year_pub', 'description', 'author_id', 'created', 'updated',];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'year_pub', 'author_id'], 'required'],
            [['created', 'updated',], 'safe'],
            [['author_id',], 'exist', 'targetClass' => Author::class, 'targetAttribute' => ['author_id', '_id']],
        ];
    }

    /**
     * @return ActiveQuery|ActiveQueryInterface
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['_id' => 'author_id']);
    }

    public function getWithAuthor()
    {
        return Book::find()
            ->with(['author'])
            ->orderBy('year_pub')->all();
    }

}