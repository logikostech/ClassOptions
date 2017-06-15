[![Travis CI](https://img.shields.io/travis/logikostech/class-options/master.svg)](https://travis-ci.org/logikostech/class-options)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/logikostech/class-options/master/LICENSE)

# Logikos\ClassOptions

## Usage
Add it to any class like so:
```php
<?php 

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
  const OPTION_DATE     = 'date';

  public function __construct() {
    $this->addOption(self::OPTION_FOO);
    $this->addOption(self::OPTION_BAR);
    $this->defineOption($this->requiredOption());
    $this->defineOption($this->noDashesOption());
    $this->defineOption($this->intOnlyOption());
    $this->defineOption($this->dateOption());
  }
  
  public function execute() {
    if (!$this->validateOptions())
      throw new \Exception('All required options must be set!');
    // code to execute
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

  private function dateOption() {
    $o = new OptionDefinition(self::OPTION_DATE);
    $o->setValidationHook(function($value) {
      $date = date_parse($value);
      return $date["error_count"] === 0;
    });
    return $o;
  }
}
```

### Defining Options
As you can see the user of the trait can define an option with `defineOption(OptionDefinitionInterface $option)` or with the `addOption(string 'name')` method.

#### [OptionDefinition](src/OptionDefinition.php)
Create a new named option definition: `$o = new OptionDefinition('foo');`

That is all you need to create an option.  However if you want validate what kind of values can be set to the option there are several helper methods.

* `$o->setValuePattern('/[0-1]+/');` - use regex to validate as values are set
* `$o->setValidationHook('is_int');` - pass any callable and the value will be passed to it as the first arg.  If the callable returns false an exception will be thrown.
* `$o->setValidationHook(function($value){return (is_int($value) && $value>5);});` - another callable example.

These validations will be checked anytime `setValue` is called and if they fail an exception is thrown.  

You can also require the option to be set using `makeRequired()` then at any time you can call `isValid()` and it does a full validation testing against the pattern or callable above and checking to see if it has been set if it is required.  Though `isValid()` does not throw an exception it just returns a bool.  Even though it does check the pattern and callable hooks it technically should only return false for required options that are not set beings the other validators are checked when trying to set the value and beings you can not add a validator after the value is set.  You can not really get an invalid value set so that isValid will return false.


### 
