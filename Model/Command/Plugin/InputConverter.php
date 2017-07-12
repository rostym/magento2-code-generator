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
 * Class InputConverter
 *
 * @package Krifollk\CodeGenerator\Model\Command\Plugin
 */
class InputConverter
{
    const BEFORE_INTERCEPTOR = 'b';
    const AROUND_INTERCEPTOR = 'ar';
    const AFTER_INTERCEPTOR  = 'a';

    /**
     * Convert input string to object representation of it
     *
     * Input EX: id1:b-ar-a,id2:a-b, id3:a
     *
     * @param string $userInput
     * @param array  $allowedMethods
     *
     * @return array|\Krifollk\CodeGenerator\Model\Command\Plugin\Method[]
     * @throws \InvalidArgumentException
     */
    public function convert(string $userInput, array $allowedMethods): array
    {
        $explodedUserInput = explode(',', trim($userInput));

        $methodIds = array_keys($allowedMethods);
        $methods = [];

        foreach ($explodedUserInput as $method) {
            list($id, $plugins) = explode(':', $method, 2);
            $id = (int)$id;
            $plugins = explode('-', $plugins, 3);

            if (!in_array($id, $methodIds, true)) {
                throw new \InvalidArgumentException(sprintf('Provided method id not found [%s]', $id));
            }

            $methodName = $allowedMethods[$id];
            $beforePlugin = in_array(self::BEFORE_INTERCEPTOR, $plugins, true);
            $aroundPlugin = in_array(self::AROUND_INTERCEPTOR, $plugins, true);
            $afterPlugin = in_array(self::AFTER_INTERCEPTOR, $plugins, true);

            $methods[] = new Method($methodName, $beforePlugin, $aroundPlugin, $afterPlugin);
        }

        return $methods;
    }
}
