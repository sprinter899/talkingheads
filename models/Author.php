<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Author extends BaseModel
{

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['name', 'surname', 'born_date', 'biography',];
        $scenarios[self::SCENARIO_UPDATE] = ['name', 'surname', 'born_date', 'biography',];
        return $scenarios;
    }

    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName(): string
    {
        return 'author';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes(): array
    {
        return ['_id', 'name', 'surname', 'born_date', 'biography', 'created', 'updated',];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'surname', 'born_date'], 'required',],
        ];
    }

    public function getBooks($author_id = null): \yii\mongodb\ActiveQuery
    {
        // @todo связь один ко многим с монгой не работает, разобраться не успел
        if(!$author_id) {
            $author_id = (string) $this->_id;
        }
        return Book::find()->where(['author_id' => $author_id]);
    }

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getWithBooks(): array
    {
        $model = $this->toArray();
        $id = (string)$this->_id;
        $i = 0;
        $books = $this->getBooks($id)->all() ?? [];
        foreach ($books as $book) {
            $model['books'][$i]['name'] = $book->name;
            $model['books'][$i]['year_pub'] = $book->year_pub;
            $i++;
        }
        return $model;
    }

    public static function getAllWithBookCnt(): array
    {
        $authors = Author::find()->orderBy(['name' => SORT_ASC, 'surname' => SORT_ASC])->all();
        $data = [];
        $i = 0;
        foreach ($authors as $author) {
            $id = (string)$author->_id;
            $data[$i] = $author->toArray();
            $data[$i]['booksCnt'] = $author->getBooks($id)->count();
            $i++;
        }

        return $data;
    }
}