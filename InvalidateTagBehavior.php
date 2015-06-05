<?php

namespace Intersvyaz\TagDependencyHelper;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;

/**
 * Behavior for automatically invalidate cache based on unified tag names
 */
class InvalidateTagBehavior extends Behavior
{
    /** @var Cache */
    public $cache = 'cache';

    /**
     * Get events list.
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'invalidateTags',
            ActiveRecord::EVENT_AFTER_INSERT => 'invalidateTags',
            ActiveRecord::EVENT_AFTER_UPDATE => 'invalidateTags',
        ];
    }

    /**
     * Invalidate model tags.
     * @return bool
     */
    public function invalidateTags()
    {
        TagDependency::invalidate(
            $this->getCacheComponent(),
            [
                ActiveRecordCacheTags::getCommonTag($this->owner),
                ActiveRecordCacheTags::getObjectTag($this->owner, $this->owner->getPrimaryKey()),
            ]
        );
        return true;
    }

    /**
     * @return Cache
     * @throws InvalidConfigException
     */
    private function getCacheComponent()
    {
        if (!($this->cache instanceof Cache)) {
            $this->cache = is_string($this->cache) ? Yii::$app->{$this->cache} : null;
            if (!$this->cache) {
                throw new InvalidConfigException('Invalid cache Id');
            }
        }

        return $this->cache;
    }
}
