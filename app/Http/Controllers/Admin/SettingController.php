<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::where('hidden', 'No')->orderBy('id', 'desc')->get();

        return view('admin.setting_list')->with('settings', $settings);
    }

    public function edit(Setting $setting)
    {
        return view('admin.setting_edit')->with('setting', $setting);
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        if ($setting->key == 'global_date_format') {
            $search  = ['y', 'm', 'd', 'j'];
            $replace = ['YYYY', 'MM', 'DD', 'DD'];
            $subject = strtolower($request->value);
            $jsDate = str_replace($search, $replace, $subject);
            Setting::where('key', 'global_js_date_format')->update(['value' => $jsDate, 'hidden' => 'Yes']);
        }
        
        if (isset($request->product_pagination)) {
            $setting->update([
                'value' =>  $request->product_pagination
            ]);
        } elseif (isset($request->home_page_title)) {
            $setting->update([
                'value' =>  $request->home_page_title
            ]);
        } elseif (isset($request->footer_page_title)) {
            $setting->update([
                'value' =>  $request->footer_page_title
            ]);
        } else {
            $setting->update([
                'value' =>  empty($request->value) ? null : $request->value
            ]);
        }

        
        return redirect()->route('admin.settings')->with('success', __('setting.messages.settingUpdated'));
    }
}
