<?php

namespace Logikos\ClassOptions;

interface ConfigurableInterface {
  /**
   * @param string $index option name
   * @param mixed  $value value to assign to this option
   *
   * @return void
   */
  public function setClassOption($index, $value);

  /**
   * @param string $index option name
   *
   * @return mixed
   */
  public function getClassOption($index);

  /**
   * @param array $options array of option names and values
   *
   * @return void
   */
  public function setClassOptions(array $options);

  /**
   * @return array of options and values currently set
   */
  public function getClassOptions();

  /**
   * @return array of indexes (option names) that can be set
   */
  public function availableClassOptions();

}