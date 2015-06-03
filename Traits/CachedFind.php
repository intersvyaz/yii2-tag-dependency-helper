<?php
namespace devgroup\TagDependencyHelper\Traits;

use devgroup\TagDependencyHelper\ActiveRecordCacheTags;
use Yii;
use yii\caching\TagDependency;

trait CachedFind
{
    /**
     * Cached analogue \yii\db\ActiveRecord::findOne()
     * @param mixed $condition
     * @param int $cacheTtl
     * @return null|\yii\db\ActiveRecord
     */
    public static function cachedFindOne($condition, $cacheTtl = 86400)
    {
        return self::cachedFind(
            $condition,
            static::class . ':one:' . ActiveRecordCacheTags::serializeCondition($condition),
            ActiveRecordCacheTags::getCommonTag(static::class),
            function ($condition) {
                return static::findByCondition($condition)->one();
            },
            $cacheTtl
        );
    }

    /**
     * Analogue cachedFindOne, but use only for search by primary key
     * @param mixed $condition
     * @param int $cacheTtl
     * @return null|\yii\db\ActiveRecord
     */
    public static function cachedFindOneByPk($condition, $cacheTtl = 86400)
    {
        return self::cachedFind(
            $condition,
            static::class . ':pk:' . ActiveRecordCacheTags::serializeCondition($condition),
            ActiveRecordCacheTags::getObjectTag(static::class, $condition),
            function ($condition) {
                return static::findByCondition($condition)->one();
            },
            $cacheTtl
        );
    }


    /**
     * Cached analogue \yii\db\ActiveRecord::findAll()
     * @param mixed $condition
     * @param int $cacheTtl
     * @return null|\yii\db\ActiveRecord[]
     */
    public static function cachedFindAll($condition, $cacheTtl = 86400)
    {
        return self::cachedFind(
            $condition,
            static::class . ':all:' . ActiveRecordCacheTags::serializeCondition($condition),
            ActiveRecordCacheTags::getCommonTag(static::class),
            function ($condition) {
                return static::findByCondition($condition)->all();
            },
            $cacheTtl
        );
    }

    /**
     * Returns a list of active record models that match the specified primary key value(s) or a set of column values.
     * @param mixed $condition primary key value or a set of column values
     * @param string $cacheKey
     * @param string $dependencyTag
     * @param callable $queryMethod
     * @param int $cacheTtl
     * @return mixed
     */
    protected static function cachedFind(
        $condition,
        $cacheKey,
        $dependencyTag,
        callable $queryMethod,
        $cacheTtl = 86400
    ) {
        $result = Yii::$app->cache->get($cacheKey);

        if ($result === false) {
            $result = call_user_func($queryMethod, $condition);
            if ($result) {
                Yii::$app->cache->set(
                    $cacheKey,
                    $result,
                    $cacheTtl,
                    new TagDependency([
                        'tags' => [$dependencyTag],
                    ])
                );
            }
        }
        return $result;
    }
}
