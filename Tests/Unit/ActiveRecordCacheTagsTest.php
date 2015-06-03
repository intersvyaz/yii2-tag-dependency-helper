<?php
namespace devgroup\TagDependencyHelper\Tests\Unit;

use devgroup\TagDependencyHelper\ActiveRecordCacheTags;
use devgroup\TagDependencyHelper\Tests\Fakes\Table;
use devgroup\TagDependencyHelper\Tests\TestCase;
use Yii;

class ActiveRecordCacheTagsTest extends TestCase
{
    public function testSerializePk()
    {
        $this->assertEquals('1', ActiveRecordCacheTags::serializePk(1));
        $this->assertEquals('1|2', ActiveRecordCacheTags::serializePk(['A' => 1, 'B' => 2]));
        $this->assertEquals('1|2', ActiveRecordCacheTags::serializePk(['B' => 2, 'A' => 1]));
    }

    public function testSerializeCondition()
    {
        $this->assertEquals('1', ActiveRecordCacheTags::serializeCondition(1));
        $this->assertEquals('{"A":1,"B":2}', ActiveRecordCacheTags::serializeCondition(['A' => 1, 'B' => 2]));
        $this->assertEquals('{"B":2,"A":1}', ActiveRecordCacheTags::serializeCondition(['B' => 2, 'A' => 1]));
    }

    public function testGetCommonTag()
    {
        $ar = new Table();
        $this->assertEquals($ar->className() . '[CommonTag]', ActiveRecordCacheTags::getCommonTag($ar));
    }

    public function testGetObjectTag()
    {
        $ar = new Table();
        $this->assertEquals(
            $ar->className() . '[ObjectTag:1]',
            ActiveRecordCacheTags::getObjectTag($ar, 1)
        );

        $this->assertEquals(
            $ar->className() . '[ObjectTag:1|2]',
            ActiveRecordCacheTags::getObjectTag($ar, ['id1' => 1, 'id2' => 2])
        );

        $this->assertEquals(
            $ar->className() . '[ObjectTag:1|2]',
            ActiveRecordCacheTags::getObjectTag($ar, ['id2' => 2, 'id1' => 1])
        );
    }

    /**
     * @expectedException \yii\base\InvalidParamException
     */
    public function testGetClassNameException()
    {
        ActiveRecordCacheTags::getCommonTag(['dddd']);
    }
}
