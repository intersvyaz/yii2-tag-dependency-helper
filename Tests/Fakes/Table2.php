<?php
namespace devgroup\TagDependencyHelper\Tests\Fakes;

use devgroup\TagDependencyHelper\InvalidateTagBehavior;
use devgroup\TagDependencyHelper\Traits\CachedFind;
use yii\db\ActiveRecord;

class Table2 extends ActiveRecord
{
    use CachedFind;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'invalidateCache' => [
                'class' => InvalidateTagBehavior::class
            ]
        ];
    }

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'table2';
    }
}
