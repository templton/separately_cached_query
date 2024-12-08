<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_learn_group}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%student}}`
 * - `{{%learn_group}}`
 */
class m241208_092259_create_junction_table_for_student_and_learn_group_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_learn_group}}', [
            'student_id' => $this->integer(),
            'learn_group_id' => $this->integer(),
            'PRIMARY KEY(student_id, learn_group_id)',
        ]);

        // creates index for column `student_id`
        $this->createIndex(
            '{{%idx-student_learn_group-student_id}}',
            '{{%student_learn_group}}',
            'student_id'
        );

        // add foreign key for table `{{%student}}`
        $this->addForeignKey(
            '{{%fk-student_learn_group-student_id}}',
            '{{%student_learn_group}}',
            'student_id',
            '{{%student}}',
            'id',
            'CASCADE'
        );

        // creates index for column `learn_group_id`
        $this->createIndex(
            '{{%idx-student_learn_group-learn_group_id}}',
            '{{%student_learn_group}}',
            'learn_group_id'
        );

        // add foreign key for table `{{%learn_group}}`
        $this->addForeignKey(
            '{{%fk-student_learn_group-learn_group_id}}',
            '{{%student_learn_group}}',
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
        // drops foreign key for table `{{%student}}`
        $this->dropForeignKey(
            '{{%fk-student_learn_group-student_id}}',
            '{{%student_learn_group}}'
        );

        // drops index for column `student_id`
        $this->dropIndex(
            '{{%idx-student_learn_group-student_id}}',
            '{{%student_learn_group}}'
        );

        // drops foreign key for table `{{%learn_group}}`
        $this->dropForeignKey(
            '{{%fk-student_learn_group-learn_group_id}}',
            '{{%student_learn_group}}'
        );

        // drops index for column `learn_group_id`
        $this->dropIndex(
            '{{%idx-student_learn_group-learn_group_id}}',
            '{{%student_learn_group}}'
        );

        $this->dropTable('{{%student_learn_group}}');
    }
}
