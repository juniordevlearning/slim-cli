<?php

namespace App\Command;

use Symfony\Component\Filesystem\Filesystem;

/*
 * This file is part of the Slim3-CLI  package
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class ControllerCreator
{
    private $filesystem;
    private $controllerName;
    private $fullControllerName;
    private $controllerPath;
    private $namespace;
    private $extends;

    public function __construct($controllerName, $controllerPath, $namespace, $extends)
    {
        $this->filesystem = new Filesystem();
        $this->controllerName = $controllerName;
        $this->controllerPath = $controllerPath;
        $this->namespace = $namespace;
        $this->extends = $extends;
        $this->fullControllerName = $controllerName.'.php';
    }

    public function create()
    {
        if (!$this->filesystem->exists($this->controllerPath)) {
            $this->filesystem->mkdir($this->controllerPath);
        }
        clearstatcache();
        if (file_exists($this->controllerPath.$this->fullControllerName)) {
            $msg = 'Controller "'.$this->fullControllerName.'" allready exists.';
            return $msg;
        }

        try {
            $this->filesystem->appendToFile(
                $this->controllerPath.$this->fullControllerName,
                $this->getContent()
            );
            $msg = 'Controller: "'.$this->fullControllerName.'" was created.';
        } catch (IOExceptionInterface $exception) {
            throw new \RuntimeException(
                'An error occurred while creating your file: '
                .$this->controllerName
            );
        }

        return $msg;
    }

    private function getContent()
    {
        $phptag = '<?php'.PHP_EOL.PHP_EOL;
        $namespace = 'namespace '.$this->namespace.';'.PHP_EOL.PHP_EOL;
        $classname = 'class '.$this->controllerName;
        $classname .= ($this->extends !== null) 
            ? ' extends '.$this->extends.PHP_EOL 
            : PHP_EOL
        ;
        $body = '{'.PHP_EOL.'}';

        $content = [
            $phptag,
            $namespace,
            $classname,
            $body,
        ];

        return $content;
    }
}