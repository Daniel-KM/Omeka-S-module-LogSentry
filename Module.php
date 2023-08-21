<?php declare(strict_types=1);

/*
 * Copyright Daniel Berthereau, 2017-2023
 *
 * This software is governed by the CeCILL license under French law and abiding
 * by the rules of distribution of free software.  You can use, modify and/ or
 * redistribute the software under the terms of the CeCILL license as circulated
 * by CEA, CNRS and INRIA at the following URL "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and rights to copy, modify
 * and redistribute granted by the license, users are provided only with a
 * limited warranty and the software's author, the holder of the economic
 * rights, and the successive licensors have only limited liability.
 *
 * In this respect, the user's attention is drawn to the risks associated with
 * loading, using, modifying and/or developing or reproducing the software by
 * the user in light of its specific status of free software, that may mean that
 * it is complicated to manipulate, and that also therefore means that it is
 * reserved for developers and experienced professionals having in-depth
 * computer knowledge. Users are therefore encouraged to load and test the
 * software's suitability as regards their requirements in conditions enabling
 * the security of their systems and/or data to be ensured and, more generally,
 * to use and operate it in the same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL license and that you accept its terms.
 */

namespace LogSentry;

use Laminas\ModuleManager\ModuleManager;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Omeka\Module\AbstractModule;

class Module extends AbstractModule
{
    public function init(ModuleManager $moduleManager): void
    {
        // Dependencies of Sentry require at least php 8.0.
        if (PHP_VERSION_ID < 80000) {
            error_log('To use module Log with Sentry, php should be version 8.0 or more.');
            return;
        }

        require_once __DIR__ . '/vendor/autoload.php';

        // To store the module name is useless to make Sentry working, but it
        // allows to list it with ModuleManager->getModules() later.
        $modules = $moduleManager->getModules();
        $modules[] = 'Facile\SentryModule';
        $moduleManager->setModules($modules);

        // Omeka has already loaded modules from application.config.php, so load
        // it here.
        $moduleManager->loadModule('Facile\SentryModule');
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event): void
    {
        parent::onBootstrap($event);

        // Attah listener only if needed.
        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        if (empty($config['sentry']['attach_listener'])) {
            return;
        }

        $application = $event->getApplication();
        $services = $application->getServiceManager();
        $eventManager = $application->getEventManager();

        /** @var \Facile\SentryModule\Listener\ErrorHandlerListener $errorHandlerListener */
        $errorHandlerListener = $services->get(\Facile\SentryModule\Listener\ErrorHandlerListener::class);
        $errorHandlerListener->attach($eventManager);
    }

    public function install(ServiceLocatorInterface $services)
    {
        $plugins = $services->get('ControllerPluginManager');
        $messenger = $plugins->get('messenger');
        $message = new \Omeka\Stdlib\Message(
            'Add your Sentry account url (dsn) in the file config/local.config.php at the root of Omeka. See %1$sreadme%2$s for more information.', // @translate
            '<a href="https://gitlab.com/Daniel-KM/Omeka-S-module-LogSentry" target="_blank" rel="noopener">',
            '</a>'
        );
        $message->setEscapeHtml(false);
        $messenger->addWarning($message);
    }
}
