Airbrake Magento
================

Introduction
------------

Airbrake Magento is a plugin for Magento eCommerce platform, which provides
integration with [Airbrake][airbrake.io], the leading exception reporting
service.

Whenever your store throws an uncaught exception, the plugin will detect that in
real-time and send information about the exception to the Airbrake dashboard.

![][dashboard]

Installation
------------

### Modman

```shell
cd <your-magento-project>
modman init
git clone https://github.com/airbrake/Airbrake-Magento.git .modman/CodebaseExceptions
modman deploy CodebaseExceptions --force
```

Configuration
-------------

First of all, find your Project API key. To do that, navigate to your project's
_General Settings_ and copy the key from the right sidebar.

![][project-idkey]

Next, to configure Airbrake Magento, visit your admin panel
(http://\[YOURDOMAIN\]/admin) and navigate to _System_ → _Configuration_. On the
left sidebar, find the _ELGENTOS_ section and click on _CodebaseExceptions_.

Paste your Project API key into the relevant field and save the new
configuration.

![][configuration]

The configuration process is done. To make sure it works correctly, visit
http://\[YOURDOMAIN\]/exceptions/index/test. This will trigger a new test
exception. It can be found in your project's dashboard.

Requirements
------------

* Magento >= 1.5
* PHP >= 5.3

[airbrake.io]: https://airbrake.io
[dashboard]: https://img-fotki.yandex.ru/get/3104/98991937.1f/0_b7fb9_d4bfe99a_orig
[project-idkey]: https://img-fotki.yandex.ru/get/3907/98991937.1f/0_b558a_c9274e4d_orig
[configuration]: https://img-fotki.yandex.ru/get/4000/98991937.1f/0_b7fba_3016040c_orig
