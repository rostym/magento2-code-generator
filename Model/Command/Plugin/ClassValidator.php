<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Command\Plugin;

/**
 * Class ClassValidator
 *
 * @package Krifollk\CodeGenerator\Model\Command\Plugin
 */
class ClassValidator implements \Zend\Validator\ValidatorInterface
{
    /** @var array */
    private $messages = [];

    /**
     * @inheritdoc
     * @throws \ReflectionException
     */
    public function isValid($className): bool
    {
        $this->messages = [];
        if (!class_exists($className)) {
            $this->messages[] = sprintf('Provided class [%s] not exists.', $className);

            return false;
        }

        $class = new \ReflectionClass($className);

        if ($class->isFinal()) {
            $this->messages[] = 'You can not create a plugin for Final class.';
        }

        $methods = $class->getMethods();

        foreach ($methods as $method) {
            if ($method->isPublic() && $method->isStatic()) {
                $this->messages[] = sprintf(
                    'You can not create a plugin for class which contain at least one final public method.[%s]',
                    $method->getName()
                );
            }
        }

        return count($this->messages) === 0;
    }

    /**
     * @inheritdoc
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
