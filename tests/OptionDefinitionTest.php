<?php

namespace Logikos\ClassOptions\Tests;

use Logikos\ClassOptions\CanNotCallMethodAfterValueSetException;
use Logikos\ClassOptions\InvalidOptionValueException;
use Logikos\ClassOptions\OptionDefinition;
use Logikos\ClassOptions\OptionDefinitionInterface;
use PHPUnit\Framework\TestCase;

class OptionDefinitionTest extends TestCase {
  const EXPECT_FAIL = 'fail';
  const EXPECT_PASS = 'pass';

  /**
   * @dataProvider validNames
   *
   * @param string $name
   */
  public function testCanCreateObjectDefinitionWithValidNameAndGetName($name) {
    $o = new OptionDefinition($name);
    $this->assertSame($name, $o->getName());
  }

  public function validNames() { // non-empty strings and ints
    return [['string'], ['a'], [0], ['1.1']];
  }

  /**
   * @dataProvider invalidNames
   *
   * @param string $name
   */
  public function testCreatingObjectDefinitionWithInvalidNameThrowsException($name) {
    $this->expectException(\Exception::class);
    new OptionDefinition($name);
  }

  public function invalidNames() { // anything other than non-empty strings and ints
    return [[null], [false], [true], [''], [1.1], [array()], [array(1)], [new \stdClass], [function() { }]];
  }

  public function testCanSetAndGetOptionValue() {
    $value = rand(1, 10);
    $o = $this->newOptionDefinition();
    $o->setValue($value);
    $this->assertSame($value, $o->getValue());
  }

  public function testIsSet() {
    $o = $this->newOptionDefinition();
    $this->assertFalse($o->isValueSet());
    $o->setValue(null);
    $this->assertTrue($o->isValueSet());
  }

  public function testCanDefineDefaultValue() {
    $o = $this->newOptionDefinition();
    $default = rand(1, 9);
    $value = rand(10, 19);
    $o->setDefault($default);
    $this->assertSame($default, $o->getDefault());
    $this->assertSame($default, $o->getValue());
    $o->setValue($value);
    $this->assertSame($default, $o->getDefault());
    $this->assertSame($value, $o->getValue());
  }

  public function testCanSetCustomValuePattern() {
    $pattern = '/[a-zA-Z]{3}/';
    $o = $this->newOptionDefinition();
    $o->setValuePattern($pattern);
    $this->assertSame($pattern, $o->getValuePattern());
  }

  public function testValuePatternValidationFail_ThrowsException() {
    $this->expectException(InvalidOptionValueException::class);
    $pattern = '/[a-zA-Z]{3}/';
    $value = 1;
    $this->setOptionDefinitionWithPatternAndValue($pattern, $value, self::EXPECT_FAIL);
  }

  public function testValuePatternValidationPass() {
    $pattern = '/[a-zA-Z]{3}/';
    $value = 'aBc';
    $this->setOptionDefinitionWithPatternAndValue($pattern, $value, self::EXPECT_PASS);
  }

  public function testCustomValidationHookFail_ThrowsException() {
    $this->expectException(InvalidOptionValueException::class);
    $o = $this->newOptionDefinition();
    $o->setValidationHook(function($value) {
      return is_int($value) && $value > 10;
    });
    $o->setValue(1);
  }

  public function testCustomValidationHookPass() {
    $o = $this->newOptionDefinition();
    $o->setValidationHook(function($value) {
      return is_int($value) && $value > 10;
    });
    $o->setValue(20);
  }

  public function testValueIsNotRequiredByDefault() {
    $o = $this->newOptionDefinition();
    $this->assertFalse($o->isRequired());
    $o->makeRequired();
    $this->assertTrue($o->isRequired());
  }

  public function testRequiredValue() {
    $o = $this->newOptionDefinition();
    $this->assertTrue($o->isValid());
    $o->makeRequired(true);
    $this->assertFalse($o->isValid());
    $o->setValue(0);
    $this->assertTrue($o->isValid());
  }

  public function testWithPatternAndNoValueSetItIsStillValidUnlessRequired() {
    $o = $this->newOptionDefinition();
    $o->setValuePattern('/[a-z]+/');
    $this->assertTrue($o->isValid());
    $o->makeRequired();
    $this->assertFalse($o->isValid());
  }

  public function testSetPatternAfterValueThrowsException() {
    $this->expectException(CanNotCallMethodAfterValueSetException::class);
    $o = $this->newOptionDefinition();
    $o->setValue(1);
    $o->setValuePattern('/[a-z]+/');
  }

  public function testSetValidationHookAfterValueThrowsException() {
    $this->expectException(CanNotCallMethodAfterValueSetException::class);
    $o = $this->newOptionDefinition();
    $o->setValue(1);
    $o->setValuePattern('is_array');
  }

  public function testCanCastObjectAsString() {
    $v = 'abc';
    $o = $this->newOptionDefinition();
    $o->setValue($v);
    $this->assertSame($v, (string) $o);
    $this->assertSame($v, "{$o}");
  }

  /**
   * @param string $name
   *
   * @return OptionDefinitionInterface
   */
  private function newOptionDefinition($name = 'foo') {
    return new OptionDefinition($name);
  }

  /**
   * @param $pattern
   * @param $value
   * @param $passOrFail
   *
   * @return OptionDefinitionInterface
   */
  private function setOptionDefinitionWithPatternAndValue($pattern, $value, $passOrFail) {
    $o = $this->newOptionDefinition();
    $o->setValuePattern($pattern);
    $expect = $passOrFail == self::EXPECT_PASS ? true : false;
    $this->assertSame($expect, $o->isValidValue($value));
    $o->setValue($value);
    return $o;
  }

}