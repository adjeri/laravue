<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers;
use Illuminate\Http\Request;
use App\Models\GlobalSetting;
use App\Http\Requests\GlobalSettingsRequest;
use App\Http\Resources\GlobalSettingsResource;

class GlobalSettingController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GlobalSettingsResource::make(GlobalSetting::first());
    }


    public function save(GlobalSettingsRequest $request)
    {
        if ($exists = GlobalSetting::first()) {
            $this->deleteImage($exists->logo);
        }

        $imageName = $this->saveImage($request->logo);

        $data = GlobalSetting::updateOrCreate(
            ['id' => 1],
            [
                'email' => $request->email,
                'logo' => $imageName,
                'index_pages' => $request->index_pages,
                'google_analytics' => $request->google_analytics,
                'facebook_ads' => $request->facebook_ads,
            ]
        );
        return GlobalSettingsResource::make($data);
    }
}
