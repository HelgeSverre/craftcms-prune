<?php
namespace HelgeSverre\Prune;

use Craft;
use craft\base\Plugin;
use HelgeSverre\Prune\twigextensions\PruneTwigExtension;

class Prune extends Plugin
{
    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Prune::$plugin
     *
     * @var \HelgeSverre\Prune\Prune
     */
    public static $plugin;
    public $schemaVersion = '1.0.0';
    public $hasCpSettings = false;


    public function init()
    {
        parent::init();

        if (Craft::$app->request->getIsSiteRequest()) {
            // Add in our Twig extension
            $extension = new PruneTwigExtension();
            Craft::$app->view->registerTwigExtension($extension);
        }
    }
}
