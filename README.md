Log Sentry (module for Omeka S)
===============================

> __New versions of this module and support for Omeka S version 3.0 and above
> are available on [GitLab], which seems to respect users and privacy better
> than the previous repository.__

[Log Sentry] is a module for [Omeka S] that allows to log errors and exceptions
via the third party monitoring service [Sentry]. It allows to log end user
errors and to profile and to trace exceptions, allowing to find issues hard to
reproduce quicker.

It can be used with or without module [Log].

**Warning**: the free Sentry subscription plan is limited to 5000 errors or
exceptions by month.


Installation
------------

The server should run php 8.0 or later. Php 7.4, the minimum version of Omeka S
v4, is not supported. Note that it can break some modules that require php 7.4.

See general end user documentation for [installing a module].

* From the zip

Download the last release [LogSentry.zip] from the list of releases (the master
does not contain the dependency), and uncompress it in the `modules` directory.

* From the source and for development

If the module was installed from the source, rename the name of the folder of
the module to `LogSentry`, go to the root module, and run:

```sh
composer install --no-dev
```

If an issue appears after upgrade of Omeka, don’t forget to update the packages
of Omeka: `rm -rf vendor && composer install --no-dev`.


Config
------

To use it, you need a server running php 8.0 or more and to add some keys in the
file `config/local.config.php` at the root of Omeka. Only the `dsn` is required:

- fill key `['sentry']['options']['dsn']` with dsn, that is a url provided by
  Sentry in your account and used for authentication and logging.
- optionally, to monitor the front-end with javascript, set the key `['sentry']['javascript']['inject_script']`
  as `true` and fill the key `['sentry']['javascript']['options']['dsn']` with
  the dsn or another one.
- set any other specific config for Sentry as you need for proxy, error
  handling, error rate, hooks, tracing, environment production/development,
  version, filters, etc: see the [documentation about the configuration].

By default, only uncaught exceptions and errors are logged.

- You may disable standard  error logging: set option `['logger']['writers']['sentry']`
  as `false`. If you set it `true` (default), the priority can be updated. The
  default priority is `\Laminas\Log\Logger::ERR` and fine in most of the cases.
- You may want to attach the default module listener via the key `['sentry']['attach_listener']`
  as `true`.

Note that it is is useless to monitor events that are not at least error or
eventually warning. The aim of Sentry is to deploy it to monitor end users
errors. For development, use other loggers.

In short, update the Omeka file `config/local.config.php` like this:

```php
    'sentry' => [
        'disable_module' => false,
        'options' => [
            // Sentry dsn.
            'dsn' => '',
            // other sentry options
            // https://docs.sentry.io/platforms/php
        ],
        'javascript' => [
            'inject_script' => false,
            'options' => [
                // Sentry Raven dsn.
                'dsn' => '',
                // other sentry options
                // https://docs.sentry.io/platforms/javascript
            ],
        ],
        // Attach listener during bootstrap. This is a specific option of this module,
        'attach_listener' => false,
    ],
```

TODO
----

- [ ] Add a release number that will include all modules versions or commits (or a hash?).


Warning
-------

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check
your archives regularly so you can roll back if needed.


Troubleshooting
---------------

See online issues on the [module issues] page on GitLab.


License
-------

This module is published under the [CeCILL v2.1] license, compatible with
[GNU/GPL] and approved by [FSF] and [OSI].

This software is governed by the CeCILL license under French law and abiding by
the rules of distribution of free software. You can use, modify and/ or
redistribute the software under the terms of the CeCILL license as circulated by
CEA, CNRS and INRIA at the following URL "http://www.cecill.info".

As a counterpart to the access to the source code and rights to copy, modify and
redistribute granted by the license, users are provided only with a limited
warranty and the software’s author, the holder of the economic rights, and the
successive licensors have only limited liability.

In this respect, the user’s attention is drawn to the risks associated with
loading, using, modifying and/or developing or reproducing the software by the
user in light of its specific status of free software, that may mean that it is
complicated to manipulate, and that also therefore means that it is reserved for
developers and experienced professionals having in-depth computer knowledge.
Users are therefore encouraged to load and test the software’s suitability as
regards their requirements in conditions enabling the security of their systems
and/or data to be ensured and, more generally, to use and operate it in the same
conditions as regards security.

The fact that you are presently reading this means that you have had knowledge
of the CeCILL license and that you accept its terms.

* The libraries [sentry/sentry] and [facile-it/sentry-module] are published
  under the license [MIT].


Copyright
---------

* Copyright Daniel Berthereau, 2017-2023 [Daniel-KM] on GitLab)

* Library [facile-it/sentry-module]: Copyright 2016 Thomas Mauro Vargiu


[Log Sentry]: https://gitlab.com/Daniel-KM/Omeka-S-module-LogSentry
[Omeka S]: https://omeka.org/s
[Log]: https://gitlab.com/Daniel-KM/Omeka-S-module-Log
[Installing a module]: https://omeka.org/s/docs/user-manual/modules/#installing-modules
[LogSentry.zip]: https://gitlab.com/Daniel-KM/Omeka-S-module-LogSentry/-/releases
[Laminas Framework Log]: https://docs.laminas.dev/laminas-log
[documentation about the configuration]: https://docs.sentry.io/platforms/php/configuration/
[Sentry]: https://sentry.io
[sentry/sentry]: https://github.com/sentry/sentry
[facile-it/sentry-module]: https://github.com/facile-it/sentry-module
[module issues]: https://gitlab.com/Daniel-KM/Omeka-S-module-LogSentry/-/issues
[CeCILL v2.1]: https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[MIT]: https://github.com/sandywalker/webui-popover/blob/master/LICENSE.txt
[GitLab]: https://gitlab.com/Daniel-KM
[Daniel-KM]: https://gitlab.com/Daniel-KM "Daniel Berthereau"
