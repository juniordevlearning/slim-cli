# Slim3-CLI 
A command line code generator for Slim3

## Description
This tool is made to create Controllers(or any other php file) for the slim project, the default configuration is made for Slim-Skeleton.<br>

## Installation
It's recommended that you use Composer to install that package.<br>
`$ composer require jdl/slim-cli` <br>
This will install the package with all dependencies.

## Usage
To view all commands enter<br> `$ php ./Command/console.php`<br>
To create a controller enter<br> `$ php ./Command/console.php app:create-controller`<br>

## Command line sequence
```
Enter the ControllerName: UserController
Do you want to extend a class?y
Enter the ExtensionClass: ParentController
Controller: "UserController.php" was created.
```
First you get asket to enter the controllername, don't enter the .php extension as it is appended automaticly.<br>
After that you can decide if the class should extend another class, which should be answerd with y/n or yes/no<br>
If you answered with y in the previews question you will be asked to enter the parent classname, again don't enter the file extension.<br>
Then the controller will be created.<br>
(Note: If the path to the controller doesn't exist allready, it will be created automaticly)

## Example Output
`dir: ./scr/Controller/UserController.php`
```
<?php

namespace App\Controller;

class UserController extends ParentController
{
}
```

## Command line options
The `--path` option let's you change the Path to the controller. Ending slash required <br>
Example: `--path=./new/controller/path/` <br>
<br>
The `--namespace` option let's you change the Namespace of the controller. <br>
Example: `--namespace=New\Controller\Namespace`

## Configuration
In the configuration file you can change the default configuration: <br><br>
Namespace: Your controller namespace <br>
Controller Path: The path to your controllers<br>

## License