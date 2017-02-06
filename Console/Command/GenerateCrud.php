<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Console\Command;

use Krifollk\CodeGenerator\Model\Command\Crud;

/**
 * Class GenerateCrud
 *
 * @package Krifollk\CodeGenerator\Console\Command
 */
class GenerateCrud extends TriadGenerateCommand
{
    const COMMAND_NAME = 'generate:crud';

    /**
     * @inheritdoc
     * @param Crud $crud
     */
    public function __construct(Crud $crud)
    {
        parent::__construct($crud);
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    protected function configure()
    {
        parent::configure();
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Generate CRUD by db table.');
    }
}
