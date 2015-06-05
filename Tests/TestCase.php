<?php
namespace Intersvyaz\TagDependencyHelper\Tests;

use Yii;
use yii\console\Application;
use yii\helpers\ArrayHelper;
use yii\caching\FileCache;
use yii\db\Connection;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        if (!extension_loaded('pdo') || !extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo and pdo_sqlite extension are required.');
        }

        $this->mockApplication([
            'components' => [
                'cache' => [
                    'class' => FileCache::class,
                    'cachePath' => '@runtime'
                ],
                'db' => [
                    'class' => Connection::class,
                    'dsn' => 'sqlite::memory:'
                ]
            ]
        ]);

        Yii::$app->db->open();
        Yii::$app->db->pdo->exec(file_get_contents(__DIR__.'/data/test.sql'));
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        Yii::$app->db->close();
        $this->destroyApplication();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = Application::class)
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'runtimePath' => __DIR__.'/runtime',
            'vendorPath' => $this->getVendorPath(),
        ], $config));
    }

    protected function getVendorPath()
    {
        $vendor = dirname(__DIR__) . '/vendor';
        if (!is_dir($vendor)) {
            $vendor = dirname(dirname(dirname(__DIR__)));
        }
        return $vendor;
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
    }
}
