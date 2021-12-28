# Logging API

Table of contents:

- [About](#about)
- [Configuration](#configuration)
- [Binding Points](#binding-points)
- [Logging](#logging)
- [Installation](#installation)
- [Unit Tests](#unit-tests)
- [Reference Guide](#reference-guide)
- [Specifications](#specifications)
    - [How Are Log Lines Formatted](#how-are-log-lines-formatted)
    - [How to Bind a Custom Logger](#how-to-bind-a-custom-logger)

## About

This API is a **skeleton** (requires [binding](#binding-points) by developers) logging system built on principles of simplicity and flexibility. Unlike Monolog, the industry standard in our days, it brings no tangible performance penalties and has near-zero learning curve just by keeping complexity to a minimum while offering you the ability to extend functionalities. 

![diagram](https://www.lucinda-framework.com/logging-api.svg)

The whole idea of logging is reduced to just three steps:

- **[configuration](#configuration)**: setting up an XML file where one or more loggers are set for each development environment
- **[binding points](#binding-points)**: binding user-defined components defined in XML/code to API prototypes in order to gain necessary abilities
- **[logging](#logging)**: creating a [Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/src/Wrapper.php) instance based on above XML and using it to log

API is fully PSR-4 compliant, only requiring PHP7.1+ interpreter and SimpleXML extension. To quickly see how it works, check:

- **[installation](#installation)**: describes how to install API on your computer, in light of steps above
- **[unit tests](#unit-tests)**: API has 100% Unit Test coverage, using [UnitTest API](https://github.com/aherne/unit-testing) instead of PHPUnit for greater flexibility
- **[example](https://github.com/aherne/php-logging-api/blob/v4.0/tests/WrapperTest.php)**: shows a deep example of API functionality based on unit test for [Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/src/Wrapper.php)

All classes inside belong to **Lucinda\Logging** namespace!

## Configuration

To configure this API you must have a XML with a **loggers** tags inside:

```xml
<loggers path="...">
	<{ENVIRONMENT}>
		<logger class="..." {OPTIONS}/>
		...
	</{ENVIRONMENT}>
	...
</loggers>
```

Where:

- **loggers**: (mandatory) holds global logging policies.
    - {ENVIRONMENT}: name of development environment (to be replaced with "local", "dev", "live", etc)
        - **logger**: stores configuration settings for a single logger (eg: file logger)
            - *class*: (mandatory) full class name of [AbstractLoggerWrapper](https://github.com/aherne/php-logging-api/blob/v4.0/src/AbstractLoggerWrapper.php) implementation, encapsulating respective logger configuration. Available values:
                - [Lucinda\Logging\Driver\File\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/File/Wrapper.php): use this if you want to log to files
                - [Lucinda\Logging\Driver\SysLog\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/SysLog/Wrapper.php): use this if you want to log to syslog
                - any user-defined PSR-4 compliant PHP class (incl. namespace) instance for your own custom logger (see: [How to bind a new logger](#how-to-bind-a-new-logger))
            - {OPTIONS}: a list of extra attributes necessary to configure respective logger identified by *class* above:
                - *application*: (mandatory if [Driver\SysLog\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/SysLog/Wrapper.php)) value that identifies your site against other syslog lines. Eg: "mySite"
                - *format*: (mandatory if [Driver\SysLog\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/SysLog/Wrapper.php) or [Driver\File\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/File/Wrapper.php)) controls what will be displayed in log line (see: [How log lines are formatted](#how-log-lines-are-formatted)). Eg: "%d %v %e %f %l %m %u %i %a"
                - *path*: (mandatory if [Driver\File\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/File/Wrapper.php)) base name of file in which log is saved. Eg: "messages"
                - *rotation*: (optional if [Driver\File\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/File/Wrapper.php)) date algorithm to rotate log above. Eg: "Y-m-d"
                - any other: if a custom logger is used. Their values are available from argument of **setLogger** method CLASS will need to implement. (see: [How to bind a new logger](#how-to-bind-a-new-logger))

Example:

```xml
<loggers>
    <local>
        <logger class="Lucinda\Logging\Driver\File\Wrapper" path="messages" format="%d %v %e %f %l %m %u %i %a" rotation="Y-m-d"/>
    </local>
    <live>
        <logger class="Lucinda\Logging\Driver\File\Wrapper" path="messages" format="%d %v %e %f %l %m %u %i %a" rotation="Y-m-d"/>
        <logger class="Lucinda\Logging\Driver\SysLog\Wrapper" application="unittest" format="%v %e %f %l %m %u %i %a"/>
    </live>
</loggers>
```

## Binding Points

In order to remain flexible and achieve highest performance, API takes no more assumptions than those absolutely required! It offers developers instead an ability to bind to its prototypes via XML:

| XML Attribute @ Tag | Class Prototype | Ability Gained |
| --- | --- | --- |
| [class @ logger](#configuration) | [AbstractLoggerWrapper](https://github.com/aherne/php-logging-api/blob/v4.0/src/AbstractLoggerWrapper.php) | Registers a logger |

API already has following [AbstractLoggerWrapper](https://github.com/aherne/php-logging-api/blob/v4.0/src/AbstractLoggerWrapper.php) implementation embedded:

- [Lucinda\Logging\Driver\File\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/File/Wrapper.php): for logging to a rotating file on disk
- [Lucinda\Logging\Driver\SysLog\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/SysLog/Wrapper.php): for logging on syslog

But developers can bind their own (check: [How to Bind a Custom Logger](#how-to-bind-a-custom-logger)) 

## Logging

Now that XML is configured, you can get a logger to save and use later on whenever needed by querying [Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/src/Wrapper.php):

```php
$object = new Lucinda\Logging\Wrapper(simplexml_load_file(XML_FILE_NAME), DEVELOPMENT_ENVIRONMENT);
$logger = $object->getLogger();
```

Logger returned is a [Logger](#interface-logger) that hides complexity of logger(s) underneath through a common interface centered on logging operations. Each [Logger](#interface-logger) must have a [AbstractLoggerWrapper](https://github.com/aherne/php-logging-api/blob/v4.0/src/AbstractLoggerWrapper.php) whose job is to generate it based on info in XML. 

**NOTE**: because XML parsing is somewhat costly, it is recommended to save $logger somewhere and reuse it throughout application lifecycle.

## Installation

First choose a folder where API will be installed then write this command there using console:

```console
composer require lucinda/logging
```

Then create a *configuration.xml* file holding configuration settings (see [configuration](#configuration) above) and a *index.php* file (see [logging](#logging) above) in project root with following code:

```php
require(__DIR__."/vendor/autoload.php");
$object = new Lucinda\Logging\Wrapper(simplexml_load_file("configuration.xml"), "local");
$logger = $object->getLogger();
$logger->info("test");
```

Above has logged a "test" message with LOG_INFO priority in messages__YYYY-MM-DD.log file if same **loggers** tag as in example above is used.

## Unit Tests

For tests and examples, check following files/folders in API sources:

- [test.php](https://github.com/aherne/php-logging-api/blob/v4.0/test.php): runs unit tests in console
- [unit-tests.xml](https://github.com/aherne/php-logging-api/blob/v4.0/unit-tests.xml): sets up unit tests and mocks "loggers" tag
- [tests](https://github.com/aherne/php-logging-api/tree/v3.0.0/tests): unit tests for classes from [src](https://github.com/aherne/php-logging-api/tree/v3.0.0/src) folder
- [tests_drivers](https://github.com/aherne/php-logging-api/tree/v3.0.0/tests_drivers): unit tests for classes from [drivers](https://github.com/aherne/php-logging-api/tree/v3.0.0/drivers) folder

**NOTE**: on first run only, test.php will fail on syslog tests but from that moment on it will consistently pass

## Reference Guide

### Interface Logger

[Logger](https://github.com/aherne/php-logging-api/blob/v4.0/src/Logger.php) interface provides blueprints for level-oriented logging using following methods: 

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| emergency | [\Throwable](https://www.php.net/manual/en/class.throwable.php) $exception | void | logs a [\Throwable](https://www.php.net/manual/en/class.throwable.php) using LOG_ALERT priority |
| alert | [\Throwable](https://www.php.net/manual/en/class.throwable.php) $exception | void | logs a [\Throwable](https://www.php.net/manual/en/class.throwable.php) using LOG_CRIT priority |
| critical | [\Throwable](https://www.php.net/manual/en/class.throwable.php) $exception | void | logs a [\Throwable](https://www.php.net/manual/en/class.throwable.php) using LOG_ERR priority |
| error | [\Throwable](https://www.php.net/manual/en/class.throwable.php) $exception | void | logs a [\Throwable](https://www.php.net/manual/en/class.throwable.php) using LOG_WARNING priority |
| warning | string $message | void | logs a string using LOG_WARNING priority |
| notice | string $message | void | logs a string using LOG_NOTICE priority |
| debug | string $message | void | logs a string using LOG_DEBUG priority |
| info | string $message | void | logs a string using LOG_INFO priority |

Usage example:

https://github.com/aherne/php-logging-api/blob/v4.0/drivers/File/Logger.php

### Abstract Class LoggerWrapper

[AbstractLoggerWrapper](https://github.com/aherne/php-logging-api/blob/v4.0/src/AbstractLoggerWrapper.php) abstract class implements conversion of data in XML to a [Logger](#interface-logger) instance via following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | SimpleXMLElement $xml | void | Reads XML and delegates to setLogger method |
| getLogger | void | [Logger](#interface-logger) | Gets [Logger](#interface-logger) generated based on XML |

and following prototype method that needs to be implemented by developers:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| setLogger | SimpleXMLElement $xml | void | Reads XML and generates a [Logger](#interface-logger) object |

Usage example:

https://github.com/aherne/php-logging-api/blob/v4.0/drivers/File/Wrapper.php

## Specifications

Some guides helping developers to get the best of this API:

- [How Are Log Lines Formatted](#how-are-log-lines-formatted)
- [How to Bind a Custom Logger](#how-to-bind-a-custom-logger)

### How are log lines formatted

As one can see above, [logger](#configuration) tags whose *class* is [Driver\File\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/File/Wrapper.php) and [Driver\SysLog\Wrapper](https://github.com/aherne/php-logging-api/blob/v4.0/drivers/SysLog/Wrapper.php) support a *format* attribute whose value can be a concatenation of:

- **%d**: current date using Y-m-d H:i:s format.
- **%v**: syslog priority level constant value matching to Logger method called.
- **%e**: name of thrown exception class ()
- **%f**: absolute location of file that logged message or threw a Throwable
- **%l**: line in file above where message was logged or Throwable/Exception was thrown
- **%m**: value of logged message or Throwable message
- **%e**: class name of Throwable, if log origin was a Throwable
- **%u**: value of URL when logging occurred, if available (value of $_SERVER["REQUEST_URI"])
- **%a**: value of USER AGENT header when logging occurred, if available (value of $_SERVER["HTTP_USER_AGENT"])
- **%i**: value of IP  when logging occurred, if available (value of $_SERVER["REMOTE_ADDR"])

Example:

```xml
<logger format="%d %f %l" .../>
```

### How to bind a custom logger

Let us assume you want to bind a new SQL logger to this API. First you need to implement the logger itself, which must extend [Logger](#interface-logger) and implement its required **log** method:

```php
class SQLLogger extends Lucinda\Logging\Logger
{
    private $schema;
    private $table;

    public function __construct(string $schema, string $table)
    {
        $this->schema = $schema;
        $this->table = $table;
    }

    protected function log($info, int $level): void
    {
        // log in sql database based on schema, table, info and level
    }
}
```

Now you need to bind logger above to XML configuration. To do so you must create another class extending [AbstractLoggerWrapper](https://github.com/aherne/php-logging-api/blob/v4.0/src/AbstractLoggerWrapper.php) and implement its required **setLogger** method:

```php
class SQLLoggerWrapper extends Lucinda\Logging\AbstractLoggerWrapper
{
    protected function setLogger(\SimpleXMLElement $xml): Lucinda\Logging\Logger
    {
        $schema = (string) $xml["schema"];
        $table = (string) $xml["table;
        return new SQLLogger($schema, $table);
    }
}
```

In that case if "psr-4" attribute in composer.json associates "Lucinda\\Project\\" with "src/" folder then SQLLoggerWrapper must be placed in *src/Loggers* folder then you finally need to bind it to XML:

```xml
<loggers>
    <local>
        <logger class="Lucinda\Project\Loggers\SQLLoggerWrapper" table="logs" schema="logging_local"/>
    </local>
    <live>
        <logger class="Lucinda\Project\Loggers\SQLLoggerWrapper" table="logs" schema="logging_production"/>
    </live>
</loggers>
```
