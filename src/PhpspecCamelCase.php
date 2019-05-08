<?php

namespace Langeland\PhpspecCamelCase;

use Langeland\PhpspecCamelCase\Listeners\PhpspecCamelCaseListener;
use PhpSpec\ServiceContainer;

class PhpspecCamelCase implements \PhpSpec\Extension
{
  /**
   * @inheritdoc
   */
  public function load(ServiceContainer $container, array $params)
  {
    $container->define('event_dispatcher.listeners.annotation', function () {
        return new PhpspecCamelCaseListener();
      },
      ['event_dispatcher.listeners']
    );
  }
}
