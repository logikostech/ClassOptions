<?php

namespace Logikos\ClassOptions\Tests;

use Logikos\ClassOptions\ConfigurableInterface;
use Logikos\ClassOptions\ConfigurableTrait;
use Logikos\ClassOptions\Option;
use Logikos\ClassOptions\UndefinedIndexException;
use PHPUnit\Framework\TestCase;

class ConfigurableTraitTest extends TestCase implements ConfigurableInterface {

    # Implementation of ConfigurableInterface so we can just test $this
    use ConfigurableTrait;

    const OPTION_FOO = 'foo';
    const OPTION_BAR = 'bar';

    public function availableClassOptions() {
        return [
            self::OPTION_FOO,
            self::OPTION_BAR
        ];
    }

    # Tests

    public function testWhenNoOptionsSet_ThenGetClassOptionsReturnsEmptyArray() {
        $this->assertSame([], $this->getClassOptions());
    }

    public function testTryingToSetUnavailableOptionThrowsException() {
        $this->expectException(UndefinedIndexException::class);
        $this->setClassOption('this-index-does-not-exist', 1);
    }

    public function testCanSetAndGetOption() {
        $value = rand(1, 100);
        $this->setClassOption(self::OPTION_FOO, $value);
        $this->assertSame($value, $this->getClassOption(self::OPTION_FOO));
    }

    public function testCanSetManyOptionsAtOnce() {
        $foo = rand(100, 199);
        $bar = rand(200, 299);
        $this->setClassOptions([
            self::OPTION_FOO => $foo,
            self::OPTION_BAR => $bar
        ]);
        $this->assertSame($foo, $this->getClassOption(self::OPTION_FOO));
        $this->assertSame($bar, $this->getClassOption(self::OPTION_BAR));
    }

    public function test_WhenNoOptionSet_ThenGetClassOptionsIsEmptyArray() {
        $this->assertSame([], $this->getClassOptions());
    }

    public function test_WhenOptionSet_ThenGetClassOptionsReturnsIt() {
        $foo = rand(100, 199);
        $bar = rand(200, 299);
        $this->setClassOption(self::OPTION_FOO, $foo);
        $this->setClassOption(self::OPTION_BAR, $bar);
        $this->assertSame(
            [
                self::OPTION_FOO => $foo,
                self::OPTION_BAR => $bar
            ],
            $this->getClassOptions()
        );
    }

    public function test_canDefineOptionAndGetIndex() {
        $option = new Option('foobar');
    }
}