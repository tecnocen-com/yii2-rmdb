<?php

namespace tecnocen\rmdb\models;

use tecnocen\rmdb\Module as RmdbModule;
use Yii;
use yii\base\InvalidConfigException;

abstract class Pivot extends \yii\db\ActiveRecord
{
    /**
     * @var string name of the attribute to store the user who created the
     * record. Set as `null` to omit the functionality.
     */
    protected $createdByAttribute = 'created_by';

    /**
     * @var string name of the attribute to store the datetime when the record
     * was created. Set as `null` to omit the functionality.
     */
    protected $createdAtAttribute = 'created_at';

    /**
     * @var string id to obtain the rmdb module from `Yii::$app`
     */
    protected $rmdbModuleId = 'rmdb';

    /**
     * @return RmdbModule
     * @throws InvalidConfigException
     */
    protected function getRmdbModule()
    {
        $module = Yii::$app->getModule($this->rmdbModuleId);
        if (!$module instanceof RmdbModule) {
            throw new InvalidConfigException(
                "Module '{$this->rmdbModuleId}' must be instance of "
                    . RmdbModule::class
            );
        }

        return $module;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $module = $this->getRmdbModule();
        return [
            'blameable' => [
                'class' => $module->blameableClass,
                'createdByAttribute' => $this->createdByAttribute,
                'updatedByAttribute' => null,
                'value' => $module->userId,
            ],
            'timestamp' => [
                'class' => $module->timestampClass,
                'createdAtAttribute' => $this->createdAtAttribute,
                'updatedAtAttribute' => null,
                'value' => $module->timestampValue,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            $this->createdByAttribute => RmdbModule::t('model', 'Created By'),
            $this->createdAtAttribute => RmdbModule::t('model', 'Created At'),
        ];
    }
}
