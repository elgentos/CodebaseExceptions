(TMP. This repo is a work in progress)

# Magento Exceptions to Codebase

## Introduction

This Magento extension inserts all the Magento exceptions to your Codebase account (see http://blog.atechmedia.com/2012/08/exception-tracking-in-codebase/).

## Requirements

* Codebase account (www.codebasehq.com)
* Magento >= 1.5
* php-airbrake (https://github.com/nodrew/php-airbrake) (included)
* PHP >= 5.3

## Note

The excellent php-airbrake class is included because the file Configuration.php is slightly changed; line 33 has been changed to point to exceptions.codebasehq.com instead of api.airbrake.io.

Magento doesn't actually throw exceptions; it uses a custom exception handler. Therefore we cannot use the EventHandler that is bundled with php-airbrake but instead we dissect Magento's stack trace message and feed it to Codebase Exceptions.

The file errors/report.php needs to be overwritten for the helper function to be called.

You can test whether the extension works by throwing an exception by visiting http://www.yourdomain.com/exceptions/index/test

## Installation

	cd <your/magento/root>
	mkdir .modman
	cd .modman
    git clone git@github.com:airbrake/Airbrake-Magento.git CodebaseExceptions
    cd ..
    modman deploy CodebaseExceptions --force

Now go to your backend and fill out the Codebase Exceptions API key (you can find this under the tab Exceptions in your project page in Codebase).
