<?php

namespace Logikos\ClassOptions\Tests\Mock;

use Logikos\ClassOptions\ConfigurableInterface;
use Logikos\ClassOptions\ConfigurableTrait;
use Logikos\ClassOptions\OptionDefinition;

class Configurable implements ConfigurableInterface {
    use ConfigurableTrait;

    const OPTION_FOO      = 'foo';
    const OPTION_BAR      = 'bar';
    const OPTION_REQUIRED = 'required';
    const OPTION_NO_DASH  = 'noDashes';
    const OPTION_INT_ONLY = 'intOnly';

    public function __construct() {
        $this->addOption(self::OPTION_FOO);
        $this->addOption(self::OPTION_BAR);
        $this->defineOption($this->requiredOption());
        $this->defineOption($this->noDashesOption());
        $this->defineOption($this->intOnlyOption());
    }

    private function requiredOption() {
        $o = new OptionDefinition(self::OPTION_REQUIRED);
        $o->makeRequired();
        return $o;
    }

    private function noDashesOption() {
        $o = new OptionDefinition(self::OPTION_NO_DASH);
        $o->setValuePattern('/[^-]+/');
        return $o;
    }

    private function intOnlyOption() {
        $o = new OptionDefinition(self::OPTION_INT_ONLY);
        $o->setValidationHook('is_int');
        return $o;
    }
}