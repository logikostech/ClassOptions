<?php

namespace Logikos\ClassOptions;


class OptionDefinition
{
    private $name;
    private $value;
    private $isSet = false;
    private $defaultValue = null;
    private $valuePattern;
    private $validationHook;
    private $valueMustBeSet = false;

    public function __construct($name) {
        if (!$this->isValidName($name))
            throw new InvalidOptionNameException;
        $this->name = $name;
    }

    public function isValidName($name) {
        if (is_integer($name)) return true;
        if (is_string($name) && !empty($name)) return true;
        return false;
    }

    public function getName() {
        return $this->name;
    }

    public function setValue($value) {
        if (!$this->isValidValue($value))
            throw new InvalidOptionValueException;

        $this->value = $value;
        $this->isSet = true;
    }

    public function getValue() {
        return $this->isValueSet() ? $this->value : $this->defaultValue;
    }

    public function isValueSet() {
        return $this->isSet;
    }

    public function setDefault($default) {
        $this->defaultValue = $default;
    }

    public function getDefault() {
        return $this->defaultValue;
    }

    public function setValuePattern($pattern) {
        if ($this->isSet)
            throw new CanNotCallMethodAfterValueSetException;
        $this->valuePattern = $pattern;
    }

    public function getValuePattern() {
        return $this->valuePattern;
    }

    public function isValidValue($value) {
        if (!empty($this->valuePattern)) {
            return $this->checkPattern($value);
        }
        if (!empty($this->validationHook)) {
            return (bool) call_user_func($this->validationHook, $value);
        }
        return true;
    }

    public function setValidationHook(callable $function) {
        if ($this->isSet)
            throw new CanNotCallMethodAfterValueSetException;
        $this->validationHook = $function;
    }

    /**
     * @param $value
     * @return int
     */
    private function checkPattern($value)
    {
        $match = preg_match($this->valuePattern, $value);
        if ($match === false) throw new InvalidValuePatternException();
        return $match !== 0;
    }

    public function makeRequired($bool = true) {
        $this->valueMustBeSet = (bool) $bool;
    }

    public function isRequired() {
        return $this->valueMustBeSet;
    }

    public function isValid() {
        if ($this->isRequired() && !$this->isSet)
            return false;

        if ($this->isSet && !$this->isValidValue($this->value))
            return false;

        return true;
    }


}