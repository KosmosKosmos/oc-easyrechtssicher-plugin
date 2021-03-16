<?php namespace KosmosKosmos\EasyRechtssicher\Models;

use Model;

class Settings extends Model {

    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsCode = 'kosmoskosmos_easyrechtssicher_settings';
    public $settingsFields = 'fields.yaml';

}
