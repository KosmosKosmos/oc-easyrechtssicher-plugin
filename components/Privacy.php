<?php namespace KosmosKosmos\EasyRechtssicher\Components;

use Cms\Classes\ComponentBase;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use KosmosKosmos\EasyRechtssicher\Traits\EsrComponentTrait;
use October\Rain\Support\Facades\Url;
use RainLab\Translate\Classes\Translator;
use KosmosKosmos\EasyRechtssicher\Models\Settings;

class Privacy extends ComponentBase
{
    use EsrComponentTrait;

    public function componentDetails()
    {
        return [
            'name'        => 'Datenschutzerklärung',
            'description' => 'Fügt die Datenschutzerklärung aus Easyrechtssicher ein.'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->page['privacyContent'] = $this->getEsrData('privacy');
    }
}
