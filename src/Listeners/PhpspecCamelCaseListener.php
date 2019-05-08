<?php

namespace Langeland\PhpspecCamelCase\Listeners;

use PhpSpec\Event\SpecificationEvent;
use PhpSpec\Loader\Node\ExampleNode;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PhpspecCamelCaseListener implements EventSubscriberInterface
{

  /**
   * @inheritdoc
   */
  public static function getSubscribedEvents()
  {
    return [
      'beforeSpecification' => ['beforeSpecification', -100],
    ];
  }

  /**
   * @inheritdoc
   */
  public function beforeSpecification(SpecificationEvent $specificationEvent) {
    $spec = $specificationEvent->getSpecification();

    foreach ($spec->getClassReflection()->getMethods() as $method) {
      if (!preg_match('/^(it|its)[^a-zA-Z]/', $method->getName())) {
        if ($title = $this->getName($method->getName())) {
          $spec->addExample(new ExampleNode($title, $method));
        }
      }
    }

//    foreach ($spec->getExamples() as $example) {
//      if ($title = $this->getName($example->getFunctionReflection()->getDocComment())) {
//        $example->setTitle($title);
//      }
//    }
  }

  /**
   * Get the annotation.
   *
   * @param string $docComment
   *
   * @return string
   */
  protected function getName($methodName)
  {
      $re = '/(?<=[a-z])(?=[A-Z])/x';
      $a = preg_split($re, $methodName);
      return join($a, ' ');
  }

}

