<?php

namespace App\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use App\Command\CreateControllerCommand;

/*
 * This file is part of the Slim3-CLI  package
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class CreateControllerCommandExceptionTest extends TestCase
{
    /**
     * @dataProvider Exceptions
     */
    public function testExecuteExceptions(
        $expected,
        $controllerName,
        $answer1,
        $answer2 = null,
        $answer3 = null
    ) {
        $this->setExpectedException(
            \RuntimeException::class,
            $expected
        );

        $application = new Application();
        $config = __DIR__.'/../slim3-cli-config.php';

        $application->add(new CreateControllerCommand($config));
        // Find command which should be testet
        $command = $application->find('app:create-controller');
        // Create commandline tester
        $commandTester = new CommandTester($command);
        // Dummy userinput
        $commandTester->setInputs([$controllerName, $answer1, $answer2,$answer3]);

        $commandTester->execute(['command' => $command->getName(),
            '--path' => 'mocks/Controller/'
        ]);
    }

    public function Exceptions()
    {
        return [
            ['Please enter "y" or "n" into the terminal.', 'Controller', ''],
            ['Please enter a valid classname.', 'Controller', 'y', '','']
        ];
    }
}