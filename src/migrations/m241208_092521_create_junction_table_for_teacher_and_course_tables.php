<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%teacher_course}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%teacher}}`
 * - `{{%course}}`
 */
class m241208_092521_create_junction_table_for_teacher_and_course_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%teacher_course}}', [
            'teacher_id' => $this->integer(),
            'course_id' => $this->integer(),
            'PRIMARY KEY(teacher_id, course_id)',
        ]);

        // creates index for column `teacher_id`
        $this->createIndex(
            '{{%idx-teacher_course-teacher_id}}',
            '{{%teacher_course}}',
            'teacher_id'
        );

        // add foreign key for table `{{%teacher}}`
        $this->addForeignKey(
            '{{%fk-teacher_course-teacher_id}}',
            '{{%teacher_course}}',
            'teacher_id',
            '{{%teacher}}',
            'id',
            'CASCADE'
        );

        // creates index for column `course_id`
        $this->createIndex(
            '{{%idx-teacher_course-course_id}}',
            '{{%teacher_course}}',
            'course_id'
        );

        // add foreign key for table `{{%course}}`
        $this->addForeignKey(
            '{{%fk-teacher_course-course_id}}',
            '{{%teacher_course}}',
            'course_id',
            '{{%course}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%teacher}}`
        $this->dropForeignKey(
            '{{%fk-teacher_course-teacher_id}}',
            '{{%teacher_course}}'
        );

        // drops index for column `teacher_id`
        $this->dropIndex(
            '{{%idx-teacher_course-teacher_id}}',
            '{{%teacher_course}}'
        );

        // drops foreign key for table `{{%course}}`
        $this->dropForeignKey(
            '{{%fk-teacher_course-course_id}}',
            '{{%teacher_course}}'
        );

        // drops index for column `course_id`
        $this->dropIndex(
            '{{%idx-teacher_course-course_id}}',
            '{{%teacher_course}}'
        );

        $this->dropTable('{{%teacher_course}}');
    }
}
