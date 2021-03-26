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
            'description' => 'Ermöglicht das Einfügen von Impressum und Datenschutzerklärung',
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
                'description'   => 'Einstellungen rund um Bezahlung',
                'icon'          => 'icon-balance-scale',
                'class'         => Settings::class
            ]
        ];
    }

}
