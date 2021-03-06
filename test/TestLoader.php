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
            ['Test\\TestClass2', 'TestClass2', true],
        ];
        $loader = new \Wwtg99\ClassLoader\Loader(__DIR__, $register_path);
        $loader->autoload();
        $test1 = new \TestClass\Test1();
        $this->assertEquals('test1', $test1->getName());
        $test2 = new \TestClass\Sub\Test2();
        $this->assertEquals('test2', $test2->getName());
        $test3 = new \Test\TestClass2\Sub\Test3();
        $this->assertEquals('test3', $test3->getName());
    }
}
