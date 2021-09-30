<?php namespace KosmosKosmos\EasyRechtssicher\Traits;


    use GuzzleHttp\Client;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\URL;
    use Illuminate\Support\Str;
    use KosmosKosmos\EasyRechtssicher\Models\Settings;
    use RainLab\Translate\Classes\Translator;

    trait EsrComponentTrait {
        protected function getEsrData($mode = 'imprint') {
            $lang = Translator::instance()->getLocale();
            $url = Settings::get($mode);

            if ($url) {
                if ($lang != 'de') {
                    $parts = explode('/', $url);
                    if (count($parts) == 7) {
                        // Sprache schon in URL
                        $url = str_replace(array('/de/', '/en/'), '/' . $lang . '/', $url);
                    }
                    else {
                        $dom = array_pop($parts);
                        $url = implode('/', $parts) . '/' . $lang . '/' . $dom;
                    }
                }
                // Cache Handling
//                $dir = sys_get_temp_dir();
                $baseUrl = Str::after(URL::to('/'), '://');

                $file = 'easy_'.trans('kosmoskosmos.easyrechtssicher::lang.mode.shortname.'.$mode).'_' . $lang . '_' . $baseUrl . '.html';

                $requestData = request()->all();
                if (array_key_exists('cache', $requestData) && $requestData['cache'] == 0 && File::exists(temp_path($file))) {
                    File::delete(temp_path($file));
                    // Serveraufruf sicherstellen, selbst, wenn das Löschen fehl schlägt
                    $lastmodified = 0;
                }
                else {
                    $lastmodified = (file_exists(temp_path($file)) ? @filemtime(temp_path($file)) : 0); // 0 oder unixtimestamp
                }

                $doLiveUpdate = true;
                $ret = '';
                // lade aus Cache, wenn vorhanden und cache zeit noch nicht rum
                if (Cache::has('OCER_NEEDS_RELOAD')) {
                    // lade gecachte DSE
                    if (File::exists(temp_path($file))) {
                        $ret = File::get(temp_path($file));
                    }

                    if (isset($ret) && $ret && Str::length($ret)) {
                        $doLiveUpdate = false;
                        $ret .= "\n<!-- gecachte Version " . temp_path($file) . ' vom ' . date('d.m.Y H:i:s', $lastmodified) . ' -->';
                    }
                }
                if ($doLiveUpdate) {
                    $client = new Client();
                    try {
                        $response = $client->get($url);
                        $ret = (string)$response->getBody();
                        // wenn kein Fehler dann cachen
                        if (!preg_match('/error.{1,4}#/i', $ret)) {
                            File::put(temp_path($file), $ret);
                            Cache::put('OCER_NEEDS_RELOAD', true, 900);
                        }
                    }
                    catch (Exception $e) {
                        // versuche doch gecachte Version Impressum, weil Serverfehler oder cachetime rum
                        if (File::exists(temp_path($file))) {
                            $ret = File::get(temp_path($file));
                        }

                        if (!isset($ret) || !$ret || !Str::length($ret)) {
                            $ret = "Error DII#0 ".__('kosmoskosmos.easyrechtssicher::lang.mode.fullname.'.$mode)." fehlt";
                        } else {
                            $ret .= "\n<!-- gecachte Version " . temp_path($file) . ' vom ' . date('d.m.Y H:i:s', $lastmodified) . ' -->';
                        }
                    }
                }

                return $ret;
            }

            return null;
        }
    }
