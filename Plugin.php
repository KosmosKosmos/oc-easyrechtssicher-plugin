<?php namespace KosmosKosmos\EasyRechtssicher;

use Backend;
use KosmosKosmos\EasyRechtssicher\Components\Imprint;
use KosmosKosmos\EasyRechtssicher\Components\Privacy;
use System\Classes\PluginBase;
use KosmosKosmos\EasyRechtssicher\Models\Settings;

class Plugin extends PluginBase {

    public $require = [
        'RainLab.Translate'
    ];

    public function pluginDetails()
    {
        return [
            'name'        => 'EasyRechtssicher Plugin',
            'description' => 'kosmoskosmos.easyrechtssicher::lang.plugin.description',
            'author'      => 'KosmosKosmos',
            'icon'        => 'icon-balance-scale'
        ];
    }

    public function registerComponents()
    {
        return [
            Imprint::class => 'imprint',
            Privacy::class => 'privacy'
        ];
    }

    public function registerSettings()
    {
        return [
            'easyrechtssicher' => [
                'label'         => 'Easy Rechtssicher',
                'description'   => 'kosmoskosmos.easyrechtssicher::lang.settings.description',
                'icon'          => 'icon-balance-scale',
                'class'         => Settings::class
            ]
        ];
    }

}
