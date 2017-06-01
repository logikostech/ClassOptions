<?php

namespace Logikos\ClassOptions;


trait ConfigurableTrait {

    private $_classOptions = [];

    public function getClassOptions() {
        return $this->_classOptions;
    }

    public function setClassOption($index, $value) {
        if (!in_array($index, $this->availableClassOptions()))
            throw new UndefinedIndexException;
        $this->_classOptions[$index] = $value;
    }

    public function getClassOption($index) {
        return $this->_classOptions[$index];
    }

    public function setClassOptions(array $options) {
        foreach ($options as $index => $value) {
            $this->setClassOption($index, $value);
        }
    }
}