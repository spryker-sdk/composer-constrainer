# ComposerConstrainer Module
[![Build Status](https://github.com/spryker-sdk/composer-constrainer/workflows/CI/badge.svg?branch=master)](https://github.com/spryker-sdk/composer-constrainer/actions?query=workflow%3ACI+branch%3Amaster)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

Tool to detect extended core modules and to update composer.json constraints from using `^` (caret) to using `~` (tilde) for those extended modules. 

### What will be found by this tool

- Extended API and non-API classes.

### What is ignored by this tool

- Extended ModuleDependencyProvider.
- Extended ModuleConfig.


## Installation

```
composer require --dev spryker-sdk/composer-constrainer
```

This is a development only "require-dev" module. Please make sure you include it as such.

Add the console command `SprykerSdk\Zed\ComposerConstrainer\Communication\Console\ComposerConstraintConsole` to your `Pyz\Zed\Console\ConsoleDependencyProvider::getConsoleCommands()` stack.

## Documentation

[Spryker Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)

Spryker OS is modular and follows SemVer. Every BC-breaking change of module API (https://documentation.spryker.com/api/definition-api.htm) is considered as a major release. 
But what happens to non-API? Spryker leverages the ability to change non-API functionality in minor and patch releases. 
This way Spryker provides new features for different business verticals.

What does it mean for my customized modules?

When you customized Spryker modules (changed module behavior on project level) even minor changes could potentially cause migration efforts. 
To avoid such cases and keep project updates safe and predictable a project should use `~` (tilde) composer constraint instead of `^` for modules with changed behavior. 
And to simplify the process Spryker provides a development command `vendor/bin/console code:constraint:modules`, which will suggest required changes in composer.json.
It can also auto-adjust your composer.json. 


## Usage

### Dry-run the command 

```
vendor/bin/console code:constraint:modules -d
```

With this command no changes will be made to the `composer.json`.
Use `-v` to see version details.

The return code of this command is either `0` (success) or `1` (error, some constraints need to be changed).

This is the recommended hook for your CI system.

### Run the command

```
vendor/bin/console code:constraint:modules
```

This command will change the project's `composer.json`. 
Please dry-run the command before you apply any changes.
