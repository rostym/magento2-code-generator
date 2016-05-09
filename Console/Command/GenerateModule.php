<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Console\Command;

use Krifollk\CodeGenerator\Model\Command\Module;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateModule extends AbstractCommand
{
    const COMMAND_NAME = 'generate:module';

    /**#@+
     * Arguments
     */
    const MODULE_NAME_ARGUMENT = 'module_name';
    const MODULE_VERSION       = 'module_version';
    /**#@-*/

    /**
     * @var Module
     */
    private $moduleGenerator;

    /**
     * GenerateModule constructor.
     *
     * @param Module $moduleGenerator
     *
     * @throws \LogicException
     */
    public function __construct(Module $moduleGenerator)
    {
        $this->moduleGenerator = $moduleGenerator;
        parent::__construct(self::COMMAND_NAME);
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setDescription('Generate base module files.')
            ->addArgument(self::MODULE_NAME_ARGUMENT, InputArgument::REQUIRED, 'Module name')
            ->addArgument(self::MODULE_VERSION, InputArgument::OPTIONAL, 'Module version');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument(self::MODULE_NAME_ARGUMENT);
        $this->validateModuleName($moduleName);
        $moduleVersion = $input->getArgument(self::MODULE_VERSION);

        try {
            $generatedFiles = $this->moduleGenerator->generate($moduleName, $moduleVersion);

            foreach ($generatedFiles as $generatedFile) {
                $output->writeln(sprintf('<info>File %s was generated.</info>', $generatedFile));
            }
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

}
