<?php

namespace Logikos\ClassOptions\Tests;

use Logikos\ClassOptions\ConfigurableInterface;
use Logikos\ClassOptions\ConfigurableTrait;
use Logikos\ClassOptions\OptionDefinition;
use PHPUnit\Framework\TestCase;

/**
 * Class UseConfigurableTraitTest
 *
 * @package Logikos\ClassOptions\Tests
 * This tests from the perspective of the class that uses the trait
 * for tests from the perspective of code which uses the class which uses the trait
 * @see     ConfigurableTraitTest
 */
class UseConfigurableTraitTest extends TestCase implements ConfigurableInterface {
  use ConfigurableTrait;

  public function test_WhenOptionDefined_ThenItIsInAvailableOptions() {
    $this->assertSame([], $this->availableClassOptions());
    $this->addOption('foo');
    $this->assertSame(['foo'], $this->availableClassOptions());
  }

  public function test_canConfigureOptionsAsOptionDefinitionObjects() {
    $o = new OptionDefinition('foo');
    $this->defineOption($o);
  }

  public function test_canAddManyOptionsAtOnce() {
    $this->addOptions([
        'a',
        new OptionDefinition('b'),
        'c',
        new OptionDefinition('d')
    ]);
    $this->assertSame(['a', 'b', 'c', 'd'], $this->availableClassOptions());
  }

  public function test_validWhenNoOptionsDefined() {
    $this->assertValid();
  }

  public function test_validWhenNonRequiredOptionsAreNotSet() {
    $foo = new OptionDefinition('foo');
    $this->defineOption($foo);
    $this->assertValid();
  }

  public function test_notValidWhenRequiredItemNotSet() {
    $foo = new OptionDefinition('foo');
    $foo->makeRequired();
    $this->defineOption($foo);
    $this->assertNotValid();
  }

  public function test_validWhenRequiredItemIsSet() {
    $foo = new OptionDefinition('foo');
    $foo->makeRequired();
    $this->defineOption($foo);
    $this->setClassOption('foo', 1);
    $this->assertValid();
  }

  public function test_canNotAddValidationPatternAfterOptionIsSet() {
    $foo = new OptionDefinition('foo');
    $this->defineOption($foo);
    $this->setClassOption('foo', 1);
    $this->expectException(\Exception::class);
    $foo->setValuePattern('/.+/');
  }

  public function test_canNotAddValidationHookAfterOptionIsSet() {
    $foo = new OptionDefinition('foo');
    $this->defineOption($foo);
    $this->setClassOption('foo', 1);
    $this->expectException(\Exception::class);
    $foo->setValidationHook('is_int');
  }

  private function assertValid() {
    $this->assertTrue($this->validateOptions());
  }

  private function assertNotValid() {
    $this->assertFalse($this->validateOptions());
  }

}