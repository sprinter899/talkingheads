<?php

class m220911_212930_author extends \yii\mongodb\Migration
{
    private $collection = 'author';

    public function up()
    {

        $this->createCollection($this->collection);
    }

    public function down()
    {
        $this->dropCollection($this->collection);

        return false;
    }
}
