<?php
namespace Intersvyaz\TagDependencyHelper\Tests\Fakes;

use Intersvyaz\TagDependencyHelper\InvalidateTagBehavior;
use Intersvyaz\TagDependencyHelper\Traits\CachedFind;
use yii\db\ActiveRecord;

class Table extends ActiveRecord
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
        return 'table';
    }
}
