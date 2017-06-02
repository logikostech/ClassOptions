<?php

namespace Logikos\ClassOptions;


trait ConfigurableTrait {

    /** @var OptionDefinitionInterface[] */
    private $_classOptions = [];

    public function availableClassOptions() {
        return array_keys($this->_classOptions);
    }

    public function setClassOption($index, $value) {
        if (!in_array($index, $this->availableClassOptions()))
            throw new UndefinedIndexException;
        $this->_classOptions[$index]->setValue($value);
    }

    public function getClassOption($index) {
        return $this->_classOptions[$index]->getValue();
    }

    public function setClassOptions(array $options) {
        foreach ($options as $index => $value)
            $this->setClassOption($index, $value);
    }

    public function getClassOptions() {
        $options = [];
        foreach ($this->_classOptions as $o)
            if ($o->isValueSet()) $options[$o->getName()] = $o->getValue();
        return $options;
    }

    protected function defineOption(OptionDefinitionInterface $o) {
        $this->_classOptions[$o->getName()] = $o;
    }

    protected function addOption($name) {
        $this->defineOption(new OptionDefinition($name));
    }

    /**
     * @param array|OptionDefinitionInterface[] $options - array of string indexes or OptionDefinitionInterface[]
     */
    protected function addOptions(array $options) {
        foreach($options as $o) {
            if ($o instanceof OptionDefinitionInterface) {
                $this->defineOption($o);
                continue;
            }
            $this->addOption($o);
        }
    }
}