<?php

namespace Weble\Plugin\System\YOOinstantsearch\Extension;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use YOOtheme\Application;
use YOOtheme\Path;

use function YOOtheme\app;

class YOOinstantsearch extends CMSPlugin implements SubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'onAfterInitialise' => 'onAfterInitialise',
        ];
    }

    public function onAfterInitialise(): void
    {
        if (!class_exists(Application::class, false)) {
            return;
        }

        $root = Path::resolve(__DIR__."/../..");

        // set alias
        Path::setAlias('~yooinstantsearch', $root);
        Path::setAlias('~yooinstantsearch_url', str_replace(JPATH_ROOT."/", "", $root));

        // bootstrap modules
        app()->load('~yooinstantsearch/builder/bootstrap.php');
    }
}
