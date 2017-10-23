<?php

namespace tecnocen\rmdb\models;

use tecnocen\rmdb\Module as RmdbModule;

abstract class PersistentEntity extends Entity
{
    /**
     * @var string name of the attribute to store the user who deleted the
     * record. Set as `null` to omit the functionality.
     */
    protected $deletedByAttribute = 'deleted_by';

    /**
     * @var string name of the attribute to store the datetime when the record
     * was deleted. Set as `null` to omit the functionality.
     */
    protected $deletedAtAttribute = 'deleted_at';

    /**
     * @inheritdoc
     */
    protected function attributeTypecast()
    {
        return parent::attributeTypecast() + [
            $this->deletedByAttribute => 'integer',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $module = $this->getRmdbModule();
        return parent::behaviors() + [
            'softDelete' => [
                'class' => $module->softDeleteClass,
                'softDeleteAttributeValues' => [
                    $this->deletedByAttribute => $module->userId,
                    $this->deletedAtAttribute => $module->timestampValue,
                ],
                'restoreAttributeValues' => [
                    $this->deletedByAttribute => null,
                    $this->deletedAtAttribute => null,
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            $this->deletedByAttribute => RmdbModule::t('models', 'Deleted By'),
            $this->deletedAtAttribute => RmdbModule::t('models', 'Deleted At'),
        ]);
    }
}
