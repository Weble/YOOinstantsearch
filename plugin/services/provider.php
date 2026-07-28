<?php

defined('_JEXEC') or die();

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Weble\Plugin\System\YOOinstantsearch\Extension\YOOinstantsearch;

require_once __DIR__ . '/../vendor/autoload.php';

return new class() implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(PluginInterface::class, fn(Container $container) => new YOOinstantsearch(
            (array)PluginHelper::getPlugin('system', 'yooinstantsearch'),
        ));
    }
};
