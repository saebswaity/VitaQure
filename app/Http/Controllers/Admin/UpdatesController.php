<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class UpdatesController extends Controller
{
    public function update($version)
    {
        if($version==2.4)
        {
            \Artisan::call('db:seed --class=SettingSeeder');
        }

        if($version==2.6)
        {
            $setting=Setting::where('key','reports')->first();
            $setting_value=json_decode($setting['value'],true);
            $setting_value['show_header_image']=false;
            $setting_value['show_background_image']=false;
            $setting_value['show_footer_image']=false;

            $setting->update([
                'key'=>json_encode($setting_value)
            ]);
        }
    }
}
