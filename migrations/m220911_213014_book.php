<?php

class m220911_213014_book extends \yii\mongodb\Migration
{
    private string $collection = 'book';

    public function up()
    {
        $this->createCollection($this->collection);
        $this->createIndex($this->collection, 'author_id');
    }

    public function down()
    {
        $this->dropIndex($this->collection, 'author_id');
        $this->dropCollection($this->collection);
    }
}
