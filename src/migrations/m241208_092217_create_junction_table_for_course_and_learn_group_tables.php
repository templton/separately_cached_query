<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%course_learn_group}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%course}}`
 * - `{{%learn_group}}`
 */
class m241208_092217_create_junction_table_for_course_and_learn_group_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%course_learn_group}}', [
            'course_id' => $this->integer(),
            'learn_group_id' => $this->integer(),
            'PRIMARY KEY(course_id, learn_group_id)',
        ]);

        // creates index for column `course_id`
        $this->createIndex(
            '{{%idx-course_learn_group-course_id}}',
            '{{%course_learn_group}}',
            'course_id'
        );

        // add foreign key for table `{{%course}}`
        $this->addForeignKey(
            '{{%fk-course_learn_group-course_id}}',
            '{{%course_learn_group}}',
            'course_id',
            '{{%course}}',
            'id',
            'CASCADE'
        );

        // creates index for column `learn_group_id`
        $this->createIndex(
            '{{%idx-course_learn_group-learn_group_id}}',
            '{{%course_learn_group}}',
            'learn_group_id'
        );

        // add foreign key for table `{{%learn_group}}`
        $this->addForeignKey(
            '{{%fk-course_learn_group-learn_group_id}}',
            '{{%course_learn_group}}',
            'learn_group_id',
            '{{%learn_group}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%course}}`
        $this->dropForeignKey(
            '{{%fk-course_learn_group-course_id}}',
            '{{%course_learn_group}}'
        );

        // drops index for column `course_id`
        $this->dropIndex(
            '{{%idx-course_learn_group-course_id}}',
            '{{%course_learn_group}}'
        );

        // drops foreign key for table `{{%learn_group}}`
        $this->dropForeignKey(
            '{{%fk-course_learn_group-learn_group_id}}',
            '{{%course_learn_group}}'
        );

        // drops index for column `learn_group_id`
        $this->dropIndex(
            '{{%idx-course_learn_group-learn_group_id}}',
            '{{%course_learn_group}}'
        );

        $this->dropTable('{{%course_learn_group}}');
    }
}
