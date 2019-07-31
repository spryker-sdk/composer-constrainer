# ComposerConstrainer Module
[![Build Status](https://travis-ci.org/spryker-sdk/composer-constrainer.svg)](https://travis-ci.org/spryker-sdk/composer-constrainer)
[![Coverage Status](https://coveralls.io/repos/github/spryker-sdk/composer-constrainer/badge.svg)](https://coveralls.io/github/spryker-sdk/composer-constrainer)

Tool to detect extended core modules and to update composer.json constraints fom using ^ (caret) to use ~ (tilde) for those extended modules. 

## Installation

```
composer require --dev spryker-sdk/composer-constrainer
```

This is a development only "require-dev" module. Please make sure you include it as such.

Add the console command `\SprykerSdk\Zed\ComposerConstrainer\Communication\Console\ComposerConstraintConsole` to your `\Pyz\Zed\Console\ConsoleDependencyProvider::getConsoleCommands()`.

## Documentation

[Spryker Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)

Spryker OS is modular and following SemVer. Every BC-breaking change of module API (https://documentation.spryker.com/api/definition-api.htm) is considered as a major release. But what happens to non-API? Spryker leverages the ability to change non-API functionalities in minor and patch releases. This way Spryker provides new features for different business verticals and growths system in a healthy consistent way.

What does it mean for my customized modules?

When you customized Spryker modules (changed modules behavior on project level) even minor changes could potentially cause migration efforts. To avoid such cases and keep project updates safe and predictable a project should use ~ (tilde) composer constraint instead of ^ for modules with changed behavior. And to simplify the process Spryker provides a development tool "code:constraint:modules", which will suggest required changes in composer.json. 

## Usage

### Dry run the command 

`vendor/bin/console code:constraint:modules -d`

With this command no changes will be made in the composer.json. The return code of this command is either 0 (success) or 1 (error, some constraints need to be changed).

To see exactly what will be updated run the command in very verbose mode.

`vendor/bin/console code:constraint:modules -d -v` 


### Run the command

`vendor/bin/console code:constraint:modules`

This command will change the composer.json, please dry run the command before you apply the changes with this command.

