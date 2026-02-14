<?php

declare(strict_types=1);

namespace CFXP\Core\Events;

use CFXP\Core\Container\ContainerInterface;
use CFXP\Core\ServiceProviderInterface;
use CFXP\Core\Exceptions\NotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws NotFoundException
     */
    public function register(ContainerInterface $container): void
    {
        // Only register if not already registered by Kernel
        // This supports both HTTP (Kernel pre-registers) and CLI/testing scenarios
        if (!$container->has(Dispatcher::class)) {
            $container->singleton(Dispatcher::class, fn() => new Dispatcher(new ListenerProvider()));
        }

        // Only register interface binding if not already present
        if (!$container->has(EventDispatcherInterface::class)) {
            $container->singleton(
                EventDispatcherInterface::class,
                fn(ContainerInterface $container) => $container->get(Dispatcher::class)
            );
        }

        // Alias is safe to set regardless (will just overwrite)
        $container->alias('events', EventDispatcherInterface::class);
    }

    public function boot(ContainerInterface $container, ?EventDispatcherInterface $dispatcher = null): void
    {
        if ($dispatcher !== null) {
            $this->registerCoreListeners($dispatcher);
        }
    }

    /**
     * Register framework-specific event listeners.
     */
    private function registerCoreListeners(EventDispatcherInterface $dispatcher): void
    {
        // Register core framework listeners here
        // Example:
        // $dispatcher->listen(KernelBooted::class, [new ScreamBooting(), 'handle']);
    }
}
