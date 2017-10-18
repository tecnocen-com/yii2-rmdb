<?php

namespace tecnocen\rmdb;

use yii2tech\ar\softdelete\SoftDeleteBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression as DbExpression;
use yii\i18n\PhpMessageSource;
use yii\web\Application as WebApplication;

/**
 * Module which contains the configurations and utils for RMDB models.
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $blameableClass = BlameableBehavior::class;

    /**
     * @var string
     */
    public $timestampClass = TimestampBehavior::class;

    /**
     * @var string
     */
    public $softDeleteClass = SoftDeleteBehavior::class;

    /**
     * @var mixed value to be passed on timestamp behavior.
     */
    public $timestampValue;

    /**
     * @var mixed id of the active user.
     */
    public $userId;

    /**
     * @var mixed value to be used when the user id can't be determined.
     */
    public $defaultUserId;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->timestampValue)) {
            $this->timestampValue = new DbExpression('NOW()');
        }
        $this->userId = (Yii::$app instanceof WebApplication
                && !Yii::$app->user->isGuest
            )
            ? Yii::$app->user->id
            : $this->defaultUserId;

        $this->registerTranslations();
    }

    /**
     * Registers the translations used by the library.
     */
    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['tecnocen/rmdb/*'] = [
            'class' => PhpMessageSource::class,
            'sourceLanguage' => 'en',
            'basePath' => __DIR__ . '/messages',
        ];
    }

    public static function t(
        $category,
        $message,
        $params = [],
        $language = null
    ) {
        return Yii::t(
            'tecnocen/rmdb/' . $category,
            $message,
            $params,
            $language
        );
    }
}
