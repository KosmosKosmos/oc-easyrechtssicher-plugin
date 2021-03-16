<?php namespace KosmosKosmos\EasyRechtssicher\Components;

use Cms\Classes\ComponentBase;
use RainLab\Translate\Classes\Translator;
use KosmosKosmos\EasyRechtssicher\Models\Settings;

class Privacy extends ComponentBase
{
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
        $lang = Translator::instance()->getLocale();
        $url = Settings::get('privacy');
        if ($url) {
            $cacheTimeInMinutes = 1440; // in Minuten 1440 => 24h
            if ($lang != 'de') {
                $parts = explode('/', $url);
                if (count($parts) == 7) {
                    // Sprache schon in URL
                    $url = str_replace(array('/de/', '/en/'), '/' . $lang . '/', $url);
                } else {
                    $dom = array_pop($parts);
                    $url = implode('/', $parts) . '/' . $lang . '/' . $dom;
                }
            }
            // Cache Handling
            $dir = sys_get_temp_dir();
            $file = 'easy_dse_' . $lang . '_' . $_SERVER['HTTP_HOST'] . '.html';
            if (isset($_REQUEST['cache']) && ($_REQUEST['cache'] == 0)) {
                @unlink($dir.$file);
                // Serveraufruf sicherstellen, selbst, wenn das L ^c  schen fehl schl ^c  gt
                $lastmodified = 0;
            } else {
                $lastmodified = (file_exists($dir.$file) ? @filemtime($dir.$file) : 0); // 0 oder unixtimestamp
            }
            $now = date('U');
            $doLiveUpdate = 1;
            $ret = '';
            // lade aus Cache, wenn vorhanden und cache zeit noch nicht rum
            if ($lastmodified && (($now - $lastmodified) / 60) < $cacheTimeInMinutes) {
                // lade gecachte DSE
                $ret = @file_get_contents($dir.$file);
                if ($ret) {
                    $doLiveUpdate = 0;
                    $ret .= "\n<!-- gecachte Version " . $dir.$file . ' vom ' . date('d.m.Y H:i:s', $lastmodified) . ' -->';
                }
            }
            if ($doLiveUpdate) {
                $ret = @file_get_contents($url, false, stream_context_create(array('http'=>array('timeout' => 20))));
                if (!$ret) {
                    // versuche doch gecachte Version Datenschutzerkl ^c  rung, weil Serverfehler oder cachetime rum
                    $ret = @file_get_contents($dir.$file);
                    if (!$ret) {
                        $ret = "Error DDI#0 Datenschutzerkl  rung fehlt";
                    } else {
                        $ret .= "\n<!-- gecachte Version " . $dir.$file . ' vom ' . date('d.m.Y H:i:s', $lastmodified) . ' -->';
                    }
                } else {
                    // wenn kein Fehler dann cachen
                    if (!preg_match('/error.{1,4}#/i', $ret)) {
                        @file_put_contents($dir.$file, $ret);
                    }
                }
            }
            $this->page['privacyContent'] = $ret;
        }
    }
}
