<?php

namespace tecnocen\rmdb\migrations;

/**
 * Migration to create entity tables which contain columns to store the users
 * which created, updated and deleted the record and the datetimes it happened.
 *
 * A persistent entity remains in the database even after being 'deleted' by
 * using `softDelete` technique.
 */
abstract class CreatePersistentEntity extends CreateEntity
{
    /**
     * @var ?string the name of the column to store the user which deleted the
     * record. Set as `null` to prevent this column from being deleted.
     */
    public $deletedByColumn = 'deleted_by';

    /**
     * @var ?string the name of the column to store the datetime when the record
     * was deleted. Set as `null` to prevent this column from being deleted.
     */
    public $deletedAtColumn = 'deleted_at';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (isset($this->deletedByColumn)) {
            $this->defaultColumns[$this->deletedByColumn]
                = $this->deletedByDefinition();
            $this->defaultForeignKeys[$this->deletedByColumn]
                = $this->deletedByForeignKey($this->createdByColumn);
        }
        if (isset($this->deletedAtColumn)) {
            $this->defaultColumns[$this->deletedAtColumn]
                = $this->deletedAtDefinition();
        }
    }

    /**
     * @return \yii\db\ColumnSchemaBuilder definition to delete the column to
     * store which user deleted the record.
     */
    protected function deletedByDefinition()
    {
        return $this->normalKey()->defaultValue(null);
    }

    /**
     * @return \yii\db\ColumnSchemaBuilder definition to delete the column to
     * store the datetime when the record was deleted.
     */
    protected function deletedAtDefinition()
    {
        return $this->datetime()->defaultValue(null);
    }

    /**
     * Foreign key definition for the `deleted_by` column.
     *
     * @return array
     * @se defaultUserForeignKey()
     */
    protected function deletedByForeignKey($columnName)
    {
        return $this->defaultUserForeignKey($columnName);
    }
}
