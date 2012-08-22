Magento Exceptions to Codebase
=================

## Introduction ##
This Magento extension inserts all the Magento exceptions to your Codebase account (see http://blog.atechmedia.com/2012/08/exception-tracking-in-codebase/).

## Requirements ##
* Codebase account (www.codebasehq.com)
* Magento >= 1.5
* php-airbrake (https://github.com/nodrew/php-airbrake) (included)

## Notes ##
The excellent php-airbrake class is included because the file Configuration.php is slightly changed; line 33 has been changed to point to exceptions.codebasehq.com instead of api.airbrake.io.
Magento doesn't actually throw exceptions; it uses a custom exception handler. Therefore we cannot use the EventHandler that is bundled with php-airbrake but instead we dissect Magento's stack trace message and feed it to Codebase Exceptions.

## Installation ##
    cd /path/to/your/magento/root
    git clone git@github.com:elgentos/CodebaseExceptions.git CodebaseExceptions
    cp -R CodebaseExceptions/* .
    rm -rf CodebaseExceptions
    rm -rf var/cache/*
    rm -rf var/sessions/*
