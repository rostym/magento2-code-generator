<?php

declare(strict_types=1);

namespace Krifollk\CodeGenerator\Model\CodeTemplate;

use Throwable;

/**
 * Class NotEnoughVariablesPassedException
 *
 * @package Krifollk\CodeGenerator\Model\CodeTemplate
 */
class NotEnoughVariablesPassedException extends \RuntimeException
{
    /**
     * NotEnoughVariablesPassedException constructor.
     *
     * @param array          $missedVariables
     * @param string         $template
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(array $missedVariables, string $template, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Missed next variables: %s for %s template.', implode(', ', $missedVariables), $template);
        parent::__construct($message, $code, $previous);
    }
}
