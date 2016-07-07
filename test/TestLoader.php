<?php

/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/7/7
 * Time: 12:09
 */
class TestLoader extends PHPUnit_Framework_TestCase
{

    public function testClassLoader()
    {
        require_once '../vendor/autoload.php';
        require_once '../src/Loader.php';
        $register_path =[
            ['TestClass', 'TestClass', true],
        ];
        $loader = new \ClassLoader\Loader(__DIR__, $register_path);
        $loader->autoload();
        $test1 = new \TestClass\Test1();
        $this->assertEquals('test1', $test1->getName());
        $test2 = new \TestClass\Sub\Test2();
        $this->assertEquals('test2', $test2->getName());
    }
}
