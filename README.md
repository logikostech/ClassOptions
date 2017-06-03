[![Travis CI](https://img.shields.io/travis/logikostech/class-options/master.svg)](https://travis-ci.org/logikostech/class-options)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/logikostech/class-options/master/LICENSE)

# Logikos\ClassOptions

## Usage

Add it to any class like so:
```php
use  Logikos\ClassOptions\ConfigurableInterface;
use  Logikos\ClassOptions\ConfigurableTrait;

class Foo implements ConfigurableInterface
{
    use ConfigurableTrait;
    
    protected function initOptions() {
        $this->addOption('bar'); // allows user of class to set the option 'bar' to anything, or leave it unset
        
    }
}
```

