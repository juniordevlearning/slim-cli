<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/*
 * This file is part of the Slim3-CLI  package
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class CreateControllerCommand extends Command
{
    protected static $defaultName = 'app:create-controller';
    private $config;

    public function __construct($config)
    {
        $this->config = $config;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new Controller')
            ->setHelp('Creates a new Controller in the src/Controller/ directory')
            ->addOption(
                'path',
                'p',
                InputOption::VALUE_REQUIRED,
                'Change the default path to the controller direcotry'
            )
            ->addOption(
                'namespace',
                null,
                InputOption::VALUE_REQUIRED,
                'Change the default controller namespace to match your project'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $extends = null;

        $classNameQuestion = new Question(
            'Enter the ControllerName: ',
            'DummyController'
        );
        $extendClassNameQuestion = $this->getExtendQuestion();
        $confirmQuestion = $this->getConfirmQuestion();

        $controllerPath = $input->getOption('path') 
            ?? $this->config['ControllerPath'];
        $namespace = $input->getOption('namespace') 
            ?? $this->config['Namespace'];

        $controllerName = $helper->ask($input, $output, $classNameQuestion);
        $confirmation = $helper->ask($input, $output, $confirmQuestion);
        if ($confirmation === true) {
            $extends = $helper->ask($input, $output, $extendClassNameQuestion);
        }

        $controllerCreator = new ControllerCreator(
            $controllerName, 
            $controllerPath, 
            $namespace,
            $extends
        );
        $msg = $controllerCreator->create();

        $output->writeln([$msg]);
    }

    private function getConfirmQuestion()
    {
        $confirmQuestion = new Question(
            'Do you want to extend a class?',
            false
        );
        $confirmQuestion->setValidator(function($answer) {
            // If person had a type repeat the question
            if (!preg_match('/^y|^n/i', $answer)) {
                throw new \RuntimeException(
                    'Please enter "y" or "n" into the terminal.'
                );
            }
            // If no typo then check for "y"
            if (preg_match('/^y/i', $answer)) {
                return true;
            }

            return false;
        });
        $confirmQuestion->setMaxAttempts(2);

        return $confirmQuestion;
    }

    private function getExtendQuestion()
    {
        $extendClassNameQuestion = new Question(
            'Enter the ExtensionClass: '
        );
        $extendClassNameQuestion->setValidator(function ($answer) {
            if ($answer === null) {
                throw new \RuntimeException(
                    'Please enter a valid classname.'
                );
            }

            return $answer;
        });
        $extendClassNameQuestion->setMaxAttempts(2);

        return $extendClassNameQuestion;
    }
}