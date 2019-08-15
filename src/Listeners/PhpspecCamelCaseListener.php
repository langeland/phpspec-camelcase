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
    public function beforeSpecification(SpecificationEvent $specificationEvent)
    {
        $spec = $specificationEvent->getSpecification();

        foreach ($spec->getClassReflection()->getMethods() as $method) {
            if (preg_match('/^(it|its)[a-zA-Z]+$/', $method->getName()) === 0) {
                continue;
            }

            $spec->addExample(
                new ExampleNode(
                    $this->getName($method->getName()),
                    $method
                )
            );
        }
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

        $name = join($a, ' ');
        $name = strtolower($name);
        $name = ucfirst($name);

        return $name;
    }

}

