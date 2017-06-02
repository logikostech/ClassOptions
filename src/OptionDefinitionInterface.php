<?php
/**
 * Created by PhpStorm.
 * User: todd
 * Date: 6/2/17
 * Time: 7:26 AM
 */

namespace Logikos\ClassOptions;

interface OptionDefinitionInterface
{
    public function isValidName($name);

    public function getName();

    public function setValue($value);

    public function getValue();

    public function isValueSet();

    public function setDefault($default);

    public function getDefault();

    public function setValuePattern($pattern);

    public function getValuePattern();

    public function isValidValue($value);

    public function setValidationHook(callable $function);

    public function makeRequired($bool = true);

    public function isRequired();

    public function isValid();
}