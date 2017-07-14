<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Console\Command;

use Krifollk\CodeGenerator\Model\Command\Plugin;
use Krifollk\CodeGenerator\Model\Generator\PluginGenerator;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class GeneratePlugin
 *
 * @package Krifollk\CodeGenerator\Console\Command
 */
class GeneratePlugin extends AbstractCommand
{
    /** @var \Krifollk\CodeGenerator\Model\Command\Plugin */
    private $plugin;

    /** @var \Krifollk\CodeGenerator\Model\Command\Plugin\ClassValidator */
    private $classValidator;

    /** @var \Krifollk\CodeGenerator\Model\Command\Plugin\InputConverter */
    private $inputConverter;

    /**
     * GeneratePlugin constructor.
     *
     * @param Plugin                                                      $plugin
     * @param Plugin\ClassValidator                                       $classValidator
     * @param \Krifollk\CodeGenerator\Model\Command\Plugin\InputConverter $inputConverter
     *
     * @throws \LogicException
     */
    public function __construct(
        Plugin $plugin,
        Plugin\ClassValidator $classValidator,
        Plugin\InputConverter $inputConverter
    ) {
        parent::__construct('generate:plugin');
        $this->plugin = $plugin;
        $this->classValidator = $classValidator;
        $this->inputConverter = $inputConverter;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();
        $this->setDescription('Generate plugin.');
    }

    /**
     * @inheritdoc
     * @throws \ReflectionException
     * @throws \InvalidArgumentException
     * @throws \Zend\Validator\Exception\RuntimeException
     * @throws \RuntimeException
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleNameEntity = $this->createModuleNameEntity($input->getArgument(self::MODULE_NAME_ARGUMENT));
        $dir = $this->getDirOption($input);
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question('Enter the name of the class for which you want to create plugin: ');
        $className = $helper->ask($input, $output, $question);

        if (!$this->classValidator->isValid($className)) {
            foreach ($this->classValidator->getMessages() as $message) {
                $output->writeln(sprintf('<error>%s</error>', $message));
            }

            return;
        }

        $question = new Question(sprintf(
            'Enter the name of the plugin class (<info>\Module\Name\ part not required</info>) Default: <info>%s</info>:',
            PluginGenerator::generateDefaultPluginName($moduleNameEntity, $className)),
            ''
        );

        $destinationClassName = $helper->ask($input, $output, $question);

        $table = new Table($output);
        $table->setHeaders(['#id', 'Allowed methods']);

        $allowedMethods = $this->extractAllowedMethods($className);

        foreach ($allowedMethods as $index => $allowedMethod) {
            $table->addRow([$index, $allowedMethod]);
        }

        $table->render();

        if ($destinationClassName !== '') {
            $output->writeln(
                sprintf(
                    'Plugin Name is: <info>%s</info>',
                    sprintf('%s\%s', $moduleNameEntity->asPartOfNamespace(), $destinationClassName)
                )
            );
        }

        $methods = [];
        while (true) {
            $question = new Question(
                'Enter method ids and types of interception<comment>(a - after, b - before, ar - around)</comment>'
                . "\n"
                . 'for which you want to create plugin using next format: <info>id:b-ar-a</info>, <info>id:a-b</info> :'
            );

            $result = $helper->ask($input, $output, $question);
            $methods = $this->inputConverter->convert($result, $allowedMethods);

            $table = new Table($output);
            $table->setHeaders(['Method Name', 'Interception types']);

            foreach ($methods as $method) {
                $interceptionTypes = '';
                if ($method->isRequireBeforeInterceptor()) {
                    $interceptionTypes .= "Before\n";
                }
                if ($method->isRequireAroundInterceptor()) {
                    $interceptionTypes .= "Around\n";
                }
                if ($method->isRequireAfterInterceptor()) {
                    $interceptionTypes .= "After\n";
                }
                $table->addRow([$method->getMethodName(), $interceptionTypes]);
            }

            $table->render();

            $question = new ConfirmationQuestion('Is everything alright ? <info>(y\n - yes by default)</info>');

            if ($helper->ask($input, $output, $question)) {
                break;
            }
        }

        try {
            $generatedFiles = $this->plugin->generate(
                $moduleNameEntity,
                $methods,
                $className,
                $destinationClassName,
                $dir
            );
            foreach ($generatedFiles as $generatedFile) {
                $output->writeln(sprintf('<info>File %s has been generated.</info>', $generatedFile));
            }
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    /**
     * @param string $className
     *
     * @return string[]
     * @throws \ReflectionException
     */
    private function extractAllowedMethods(string $className): array
    {
        $class = new \ReflectionClass($className);
        $methods = $class->getMethods();
        $allowedMethods = [];
        foreach ($methods as $method) {
            if ($method->isPublic() && !$method->isConstructor()) {
                $allowedMethods[] = $method->getName();
            }
        }

        return $allowedMethods;
    }
}
