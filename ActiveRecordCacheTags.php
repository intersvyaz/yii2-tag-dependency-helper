<?php
namespace Intersvyaz\TagDependencyHelper;

use yii\base\InvalidParamException;
use yii\db\ActiveRecord;

class ActiveRecordCacheTags
{
    /**
     * Get common tag name.
     * @param string|ActiveRecord $class
     * @return string
     * @throws InvalidParamException
     */
    public static function getCommonTag($class)
    {
        return self::getClassName($class) . '[CommonTag]';
    }

    /**
     * Get object tag name.
     * @param string|ActiveRecord $class
     * @param array|int $id
     * @return string
     * @throws InvalidParamException
     */
    public static function getObjectTag($class, $id)
    {
        return self::getClassName($class) . '[ObjectTag:' . self::serializePk($id) . ']';
    }

    /**
     * @param $class
     * @return string
     */
    private static function getClassName($class)
    {
        if (is_object($class) && $class instanceof ActiveRecord) {
            $class = $class->className();
        }
        if (!is_string($class)) {
            throw new InvalidParamException('Param $class must be a string or an object.');
        }
        return $class;
    }

    /**
     * Serialize primary key for cache-key
     * @param mixed $id
     * @return string
     */
    public static function serializePk($id)
    {
        if (is_array($id)) {
            ksort($id);
            return implode('|', $id);
        }

        return (string)$id;
    }

    /**
     * @param $condition
     * @return string
     */
    public static function serializeCondition($condition){
        return json_encode($condition);
    }
}
