yii2-tag-dependency-helper
==========================
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/intersvyaz/yii2-tag-dependency-helper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/intersvyaz/yii2-tag-dependency-helper/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/intersvyaz/yii2-tag-dependency-helper/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/intersvyaz/yii2-tag-dependency-helper/?branch=master)
[![Build Status](https://travis-ci.org/intersvyaz/yii2-tag-dependency-helper.svg)](https://travis-ci.org/intersvyaz/yii2-tag-dependency-helper)

Helper for unifying cache tag names with invalidation support for Yii2.

Usage
-----

In your model add behavior:


``` php
...

use Intersvyaz\TagDependencyHelper\Traits\CachedFind;
use Intersvyaz\TagDependencyHelper\InvalidateTagBehavior;

...

class Configurable extends ActiveRecord
{
    use CachedFind;
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => InvalidateTagBehavior::class,
                'cache' => 'cache', // optional option - application id of cache component
            ],
        ];
    }
}

```

This behavior automatically invalidates tags by model name and pair model-id.

If your cache entry should be flushed every time any row of model is edited - use `getCommonTag` helper function:

``` php
use Intersvyaz\TagDependencyHelper\ActiveRecordCacheTags;

...

$models = Configurable::getDb()->cache(
    function ($db) {
        return Configurable::find()->all($db);
    },
    86400,
    new TagDependency([
        'tags' => [ActiveRecordCacheTags::getCommonTag(Configurable::className())],
    ])
);
```

If your cache entry should be flushed only when exact row of model is edited - use `getObjectTag` helper function:

``` php
use Intersvyaz\TagDependencyHelper\ActiveRecordCacheTags;

...

$cacheKey = 'Product:' . $model_id;
if (false === $product = Yii::$app->cache->get($cacheKey)) {
    
    if (null === $product = Product::findOne($model_id)) {
        throw new NotFoundHttpException;
    }
    Yii::$app->cache->set(
        $cacheKey,
        $product,
        86400,
        new TagDependency(
            [
                'tags' => [
                    ActiveRecordCacheTags::getObjectTag(Product::class, $model_id),
                ]
            ]
        )
    );
}

```

In the CachedFind trait implemented shortcut method cachedFindOne() and cachedFindAll() for cached find query.
