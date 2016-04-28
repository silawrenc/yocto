<?php

use silawrenc\Yocto\Yocto;

class YoctoTest extends \PHPUnit_Framework_TestCase
{
    protected $flag;

    public function setUp()
    {
        $serviceRevolver = function () {
            return implode(', ', func_get_args());
        };
        $this->yocto = new Yocto($serviceRevolver);
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(Yocto::class, $this->yocto);
    }

    public function testAdd()
    {
        $this->assertInstanceOf(Yocto::class, $this->yocto->add($this->flag()));
    }

    public function testGet()
    {
        $this->assertEquals('that thing', $this->yocto->get('that thing'));
    }

    public function testRun()
    {
        $this->yocto->add($this->flag())->run();

        $this->assertTrue($this->flag);
    }

    public function testRunHasAccessToServiceResolver()
    {
        $this->yocto->add(function ($app) {
            $this->flag = $app->get('something');
        });
        $this->yocto->run();

        $this->assertEquals('something', $this->flag);
    }

    public function testAddAppendsToExecutables()
    {
        $first = function () {
            $this->flag = 'first, ';
        };
        $second = function () {
            $this->flag .= 'second';
        };
        $this->yocto->add($first)->add($second)->run();

        $this->assertEquals('first, second', $this->flag);
    }

    public function testRunEndsOnStrictFalse()
    {
        $breaking = function () {
            $this->flag = 'just this';
            return false;
        };
        $notRun = function () {
            $this->flag = 'not this';
        };
        $this->yocto->add($breaking)->add($notRun)->run();

        $this->assertEquals('just this', $this->flag);
    }

    public function testGetPassesAllArguments()
    {
        $result = $this->yocto->get('one', 'two', 'three');
        $this->assertEquals('one, two, three', $result);
    }

    protected function flag($flag = null)
    {
        return function () use ($flag) {
            $this->flag = $flag ?: true;
        };
    }
}
