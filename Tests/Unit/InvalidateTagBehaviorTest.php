<?php
namespace devgroup\TagDependencyHelper\Tests\Unit;


use devgroup\TagDependencyHelper\ActiveRecordCacheTags;
use devgroup\TagDependencyHelper\Tests\Fakes\Table;
use devgroup\TagDependencyHelper\Tests\TestCase;
use Yii;
use yii\caching\TagDependency;

class InvalidateTagBehaviorTest extends TestCase
{
    public function testInvalidateTags()
    {
        $dependency = new TagDependency([
            'tags' => [ActiveRecordCacheTags::getCommonTag(Table::className())],
            'reusable' => true,
        ]);

        $model = Table::getDb()->cache(
            function ($db) {
                return Table::findOne(['title' => 'row1']);
            },
            86400,
            $dependency
        );

        $this->assertFalse($dependency->getHasChanged(Yii::$app->cache));

        $model->delete();

        $this->assertTrue($dependency->getHasChanged(Yii::$app->cache));
    }
}
