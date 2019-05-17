<?php

namespace tecnocen\rmdb\migrations;

/**
 * Migration to create entity tables which contain columns to store the users
 * which created and updated the record and the datetimes it happened.
 */
abstract class CreateEntity extends CreatePivot
{
    /**
     * @var ?string the name of the column to store the user which updated the
     * record. If this property is set as `null` the column won't be created.
     */
    public $updatedByColumn = 'updated_by';

    /**
     * @var ?string the name of the column to store the datetime when the record
     * was updated. If this property is set as `null` the column won't be created.
     */
    public $updatedAtColumn = 'updated_at';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (isset($this->updatedByColumn)) {
            $this->defaultColumns[$this->updatedByColumn]
                = $this->updatedByDefinition();
            $this->defaultForeignKeys[$this->updatedByColumn]
                = $this->updatedByForeignKey($this->createdByColumn);
        }
        if (isset($this->updatedAtColumn)) {
            $this->defaultColumns[$this->updatedAtColumn]
                = $this->updatedAtDefinition();
        }
    }

    /**
     * @return \yii\db\ColumnSchemaBuilder definition to update the column to
     * store which user updated the record.
     */
    protected function updatedByDefinition()
    {
        return $this->normalKey()->notNull();
    }

    /**
     * @return \yii\db\ColumnSchemaBuilder definition to update the column to
     * store the datetime when the record was updated.
     */
    protected function updatedAtDefinition()
    {
        return $this->datetime()->notNull();
    }

    /**
     * Foreign key definition for the `updated_by` column.
     *
     * @return array
     * @see defaultUserForeignKey()
     */
    protected function updatedByForeignKey($columnName)
    {
        return $this->defaultUserForeignKey($columnName);
    }
}
