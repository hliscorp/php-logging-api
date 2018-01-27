# php-logging-api

There are many logging solutions available for PHP applications, starting with those provided by the language itself. By default, PHP supports text-based logging to current web server file via error_log or to a multiple clustered servers via syslog procedural functions as well as further fine tunings in via php.ini file. Even though taken together these are enough to fit the needs of most applications, the way they work (calling functions with many non-intuitive arguments, altering php.ini file) is cumbersome and primitive: they aren't designed to be logging solutions, but rather logging conclusions. For these reasons, programmers have felt a need for an abstraction layer that hides latter complexity, adds more structure as well as options language doesn't support by default (eg: logging in an SQL table). There is a library that stands above all others in breadth and complexity, implementing all logging solutions a PHP application may possible need and that is monolog. This is actually a well thought out API recommended for any complex application where performance is to be sacrificed for the sake of completeness.

This is an API done for any application that does not need the advantages of monolog described above, but instead considers logging not important enough to become a major performance penalty. A very light weight logging system that remains simple and perfectly flexible is what I've had in mind when I've designed LoggingAPI. It is nothing but a thin abstraction layer on top of native logging functions adding an ability to format logging messages. Performance penalties of using it are negligible (it is infinitely faster than monolog), but on the other hand it pays by being a lot less rich in features.

The architecture of LoggingAPI is as simple as having a Logger class that abstracts logging solution and implements operations blueprints specific to logging. Because Logger class is abstract and does not concern with logging solution chosen, calling any of its methods will delegate to a single *log* abstract protected method children will have to implement it in order to become a logging solution. LoggingAPI offers users the ability to implement any solution on Logger's skeleton, but these ones (also by far more common in usage) are already included:

- FileLogger: writes log into files on current web server.
- SysLogger: writes log into SysLog for higher flexibility (useful to log on distributed web servers).

By implementing *log* method, developers can add any other logger they want.

More information here:<br/>
http://www.lucinda-framework.com/logging-api
