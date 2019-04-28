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
class CreateControllerCommandTest extends TestCase
{
    /**
     * @dataProvider Cases
     */
    public function testExecute(
        $expected,
        $delete = 0,
        $controllerName,
        $answer1,
        $answer2 = null
    ) {
        $path = 'mocks/';
        // Create consoleapplication
        $application = new Application();
        $config = __DIR__.'/../slim3-cli-config.php';

        $application->add(new CreateControllerCommand($config));
        // Find command which should be testet
        $command = $application->find('app:create-controller');
        // Create commandline tester
        $commandTester = new CommandTester($command);
        // Dummy userinput
        $commandTester->setInputs([$controllerName, $answer1, $answer2]);

        $commandTester->execute(['command' => $command->getName(),
            '--path' => $path
        ]);
        $output = $commandTester->getDisplay();

        $this->assertContains($expected, $output);

        if ($delete === 1) {
            $this->delete($path, $controllerName);
        }
    }

    public function Cases()
    {
        return [
            ['Controller: "Controller.php" was created.', 0, 'Controller', 'n'],
            ['Controller "Controller.php" allready exists.', 1, 'Controller', 'n'],
            ['Controller: "Controller2.php" was created.', 0, 'Controller2', 'y', 'test'],
            ['Controller "Controller2.php" allready exists.', 1, 'Controller2', 'y', 'test'],
        ];
    }

    private function delete($path, $controllerName)
    {
        unlink($path.$controllerName.'.php');
        rmdir($path);
    }
}