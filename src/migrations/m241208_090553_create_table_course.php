<?php

use yii\db\Migration;

/**
 * Class m241208_090553_create_table_course
 */
class m241208_090553_create_table_course extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('course', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'type_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk-course-type_id',
            'course',
            'type_id',
            'course_type',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-course-type_id', 'course');
        $this->dropTable('course');
    }
}
