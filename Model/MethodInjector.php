<?php

declare(strict_types=1);

namespace Krifollk\CodeGenerator\Model;

/**
 * Class MethodInjector
 *
 * @package Krifollk\CodeGenerator\Model
 */
class MethodInjector
{
    /**
     * Inject method(s) content to class content
     *
     * Finds the position of latest curly bracket in class and paste content between
     *
     * @param string $classContent
     * @param string $methodContent
     *
     * @return string
     */
    public function inject(string $classContent, string $methodContent): string
    {
        $positionOfLastCurlyBracket = strrpos($classContent, '}');

        return substr($classContent, 0, $positionOfLastCurlyBracket - 1)
            . $methodContent
            . substr($classContent, $positionOfLastCurlyBracket, strlen($classContent));
    }
}
