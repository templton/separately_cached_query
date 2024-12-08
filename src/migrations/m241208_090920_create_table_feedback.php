<?php

use yii\db\Migration;

/**
 * Class m241208_090920_create_table_feedback
 */
class m241208_090920_create_table_feedback extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('feedback', [
            'id' => $this->primaryKey(),
            'post' => $this->text(),
            'student_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk-feedback-student_id',
            'feedback',
            'student_id',
            'student',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-feedback-student_id', 'student');
        $this->dropTable('feedback');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241208_090920_create_table_feedback cannot be reverted.\n";

        return false;
    }
    */
}
