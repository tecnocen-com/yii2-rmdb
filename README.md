Yii2 RMDB Classes
=================

Library with migrations and models to easily create RMDB tables and models.

Instalation
-----------

You can use composer to install the library `tecnocen/yii2-rmdb` by running the
command;

`composer require tecnocen/yii2-rmdb`

or edit the `composer.json` file

```json
require: {
    "tecnocen/yii2-rmdb": "*",
}
```

Usage
-----

### Create Migrations

There are 3 migration classes for each type of RMDB tables.

#### `tecnocen\rmdb\migrations\CreatePivot`

Uses the properties  `$createdByColumn`, `$createdAtColumn` and methods
`createdByDefinition()`, `createdAtDefinition()` to store the user and datetime
a record was created.

```php
class m170101_000001_product_sale extends \tecnocen\rmdb\migrations\CreatePivot
{
    public $createdByColumn = 'creation_user';
    public $createdAtColumn = 'creation_date';

    public function getTableName()
    {
        return 'product_sale';
    }

    public function columns()
    {
        return [
            'product_id' => ...,
            'sale_id' => ...,
        ];
    }

    public function compositePrimaryKeys()
    {
        return ['product_id', 'sale_id'];
    }
}
```

#### `tecnocen\rmdb\migrations\CreateEntity`

Extends the previous class adding the properties  `$updatedByColumn`,
`$updatedAtColumn` and methods `updatedByDefinition()`, `updatedAtDefinition()`
to store the user and datetime a record was updated.

```php
class m170101_000001_product extends \tecnocen\rmdb\migrations\CreateEntity
{
    public $createdByColumn = 'creation_user';
    public $createdAtColumn = 'creation_date';
    public $updatedByColumn = 'edition_user';
    public $updatedAtColumn = 'edition_date';

    public function getTableName()
    {
        return 'product';
    }

    public function columns()
    {
        return [
            'id' => $this->prymariKey()->...,
            'name' => ...,
        ];
    }
}
```

#### `tecnocen\rmdb\migrations\CreatePersistentEntity`

A persistent entity remains stored in the database after the user deletes it.

The library [yii2tech/ar-softdelete](https://github.com/yii2tech/ar-softdelete)
provides support for this functionality.

`CreateEntity` extends  the previous class adding the properties
`$deletedByColumn`, `$deletedAtColumn` and methods `deletedByDefinition()`,
`deletedAtDefinition()` to store the user and datetime a record was deleted.

```php
class m170101_000001_sale extends \tecnocen\rmdb\migrations\CreateEntity
{
    public $createdByColumn = 'creation_user';
    public $createdAtColumn = 'creation_date';
    public $updatedByColumn = 'edition_user';
    public $updatedAtColumn = 'edition_date';
    public $deletedByColumn = 'deletion_user';
    public $deletedAtColumn = 'deletion_date';

    public function getTableName()
    {
        return 'product';
    }

    public function columns()
    {
        return [
            'id' => $this->prymariKey()->...,
            'store_id' => ...,
        ];
    }
}
```

### RMDB Module

This library uses a custom module to help configure all the extended models
in an unified way.

configure it in your `common\config\main.php` in `yii-app-advanced` and
`common\config.php` in `yii-app-basic`.

```php
use tecnocen\rmdb\Module as RmdbModule;

return [
    // ...
    'modules' => [
        'rmdb' => [
            'class' => RmdbModule::class,
            'timestampClass' => ..., // optional
            'blameableClass' => ..., // optional
            'softDeleteClass' => ..., // optional
            'timestampValue' => time(), // optional by default uses `now()`
            'defaultUserId' => 5, // optional
        ],
    ],
];
```

### Models

Like the migrations there are 3 classes for models.

#### `tecnocen\rmdb\models\Pivot`

Adds protected properties `$createdByAttribute` and `$createdAtAttribute` to
configure the names of the attributes. The class will automatically load the
needed behaviors and configure them to use the attributes as provided by this
properties.

```php
class ProductSale extends \tecnocen\rmdb\models\Pivot
{
    protected $createdByAttribute = 'creation_user';
    protected $createdAtAttribute = 'creation_date';

    // rest of model methods and logic
}
```

#### `tecnocen\rmdb\models\Entity`

Extends the previos class and adds protected properties `$updatedByAttribute`
and `$updatedAtAttribute` to configure the names of the attributes. The class
will automatically load the needed behaviors and configure them to use the
attributes as provided by this properties.

```php
class Product extends \tecnocen\rmdb\models\Entity
{
    protected $createdByAttribute = 'creation_user';
    protected $createdAtAttribute = 'creation_date';
    protected $updatedByAttribute = 'edition_user';
    protected $updatedAtAttribute = 'edition_date';

    // rest of model methods and logic
}
```

#### `tecnocen\rmdb\models\PersistentEntity`

Extends the previos class and adds protected properties `$deletedByAttribute`
and `$deletedAtAttribute` to configure the names of the attributes. The class
will automatically load the needed behaviors and configure them to use the
attributes as provided by this properties.

```php
class Product extends \tecnocen\rmdb\models\PersistentEntity
{
    protected $createdByAttribute = 'creation_user';
    protected $createdAtAttribute = 'creation_date';
    protected $updatedByAttribute = 'edition_user';
    protected $updatedAtAttribute = 'edition_date';
    protected $deletedAtAttribute = 'deletion_user';
    protected $deletedAtAttribute = 'deletion_date';

    // rest of model methods and logic
}
```
