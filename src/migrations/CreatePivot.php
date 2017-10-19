<?php

namespace tecnocen\rmdb\migrations;

use tecnocen\migrate\CreateTableMigration as CreateTable;

/**
 * Migration to create pivot tables which contain columns to store the user
 * which created the record and the datetime it was created.
 */
abstract class CreatePivot extends CreateTable
{
    /**
     * @var ?string the name of the column to store the user which created the
     * record. If this property is set as `null` the column won't be created.
     */
    public $createdByColumn = 'created_by';

    /**
     * @var ?string the name of the column to store the datetime when the record
     * was created. If this property is set as `null` the column won't be created.
     */
    public $createdAtColumn = 'created_at';

    /**
     * @var string name of the table used for the foreign key in user columns.
     */
    public $userTable = 'user';

    /**
     * @var \yii\db\ColumnSchemaBuilder[]
     */
    protected $defaultColumns = [];

    /**
     * @var string[]
     */
    protected $defaultForeignKeys = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (isset($this->createdByColumn)) {
            $this->defaultColumns[$this->createdByColumn]
                = $this->createdByDefinition();
            $this->defaultForeignKeys[$this->createdByColumn]
                = $this->userTable;
        }
        if (isset($this->createdAtColumn)) {
            $this->defaultColumns[$this->createdAtColumn]
                = $this->createdAtDefinition();
        }
    }

    /**
     * @inheritdoc
     */
    public function defaultColumns()
    {
        return $this->defaultColumns;
    }

    /**
     * @inheritdoc
     */
    public function defaultForeignKeys()
    {
        return $this->defaultForeignKeys;
    }

    /**
     * @return \yii\db\ColumnSchemaBuilder definition to create the column to
     * store which user created the record.
     */
    protected function createdByDefinition()
    {
        return $this->normalKey()->notNull();
    }

    /**
     * @return \yii\db\ColumnSchemaBuilder definition to create the column to
     * store the datetime when the record was created.
     */
    protected function createdAtDefinition()
    {
        return $this->datetime()->notNull();
    }
}
