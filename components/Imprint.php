<?php namespace KosmosKosmos\EasyRechtssicher\Components;

use Cms\Classes\ComponentBase;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use KosmosKosmos\EasyRechtssicher\Traits\EsrComponentTrait;
use October\Rain\Support\Facades\Url;
use RainLab\Translate\Classes\Translator;
use KosmosKosmos\EasyRechtssicher\Models\Settings;

class Imprint extends ComponentBase
{
    use EsrComponentTrait;

    public function componentDetails()
    {
        return [
            'name'        => 'Impressum',
            'description' => 'Fügt das Impressum aus Easyrechtssicher ein.'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {

        $this->page['imprintContent'] = $this->getEsrData();

    }
}
