# foodora-test

## Installation instructions

 _php composer.phar install_ to be able to use autoloading and install phpunit to test
 
## DB configuration

Update your database configuration in the src/DBConfig.php file.

## Running scrits
* To migrate data from vendor_special_day to vendor_schedule table:

_php src/ConvertDataCommand.php_

* To restore original data back to vendor_schedule table:

_php src/RestoreDataCommand.php_
## Running tests
_php phpunit src/tests/<testClass>.php_
