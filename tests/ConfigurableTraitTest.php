<?php

namespace Logikos\ClassOptions\Tests;

use Logikos\ClassOptions\ConfigurableInterface;
use Logikos\ClassOptions\Tests\Mock\Configurable;
use Logikos\ClassOptions\UndefinedIndexException;
use PHPUnit\Framework\TestCase;

class ConfigurableTraitTest extends TestCase {

  /** @var  ConfigurableInterface */
  private $configurable;

  public function setUp() {
    $this->configurable = new Configurable();
  }

  public function testWhenNoOptionsSet_ThenGetClassOptionsReturnsEmptyArray() {
    $this->assertSame([], $this->configurable->getClassOptions());
  }

  public function testTryingToSetUnavailableOptionThrowsException() {
    $this->expectException(UndefinedIndexException::class);
    $this->configurable->setClassOption('this-index-does-not-exist', 1);
  }

  public function testCanSetAndGetOption() {
    $value = rand(1, 100);
    $index = Configurable::OPTION_FOO;
    $this->configurable->setClassOption($index, $value);
    $this->assertSame($value, $this->configurable->getClassOption($index));
  }

  public function testCanSetManyOptionsAtOnce() {
    $foo = rand(100, 199);
    $bar = rand(200, 299);
    $this->configurable->setClassOptions([
        Configurable::OPTION_FOO => $foo,
        Configurable::OPTION_BAR => $bar
    ]);
    $this->assertOptionValueIs(Configurable::OPTION_FOO, $foo);
    $this->assertOptionValueIs(Configurable::OPTION_BAR, $bar);
  }


  # Test getClassOptions() empty
  public function test_WhenNoOptionSet_ThenGetClassOptionsIsEmptyArray() {
    $this->assertSame([], $this->configurable->getClassOptions());
  }

  # Test getClassOptions() with set options
  public function test_WhenOptionSet_ThenGetClassOptionsReturnsIt() {
    $foo = rand(100, 199);
    $bar = rand(200, 299);
    $this->configurable->setClassOption(Configurable::OPTION_FOO, $foo);
    $this->configurable->setClassOption(Configurable::OPTION_BAR, $bar);
    $this->assertSame(
        ['foo' => $foo, 'bar' => $bar],
        $this->configurable->getClassOptions()
    );
  }

  private function assertOptionValueIs($optionIndex, $value) {
    $this->assertSame($value, $this->configurable->getClassOption($optionIndex));
  }
}