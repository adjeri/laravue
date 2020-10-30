<?php

namespace App\Http\Controllers;

use App\Models\HomePage;
use App\Http\Traits\Helpers;
use Illuminate\Http\Request;
use App\Http\Requests\HomePageRequest;
use App\Http\Resources\HomePageResource;

class HomePageController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return HomePageResource::make(HomePage::first());
    }

    public function save(HomePageRequest $request)
    {
        if ($exists = HomePage::first()) {
            $this->deleteImage($exists->image);
        }

        $imageName = $this->saveImage($request->image);


        $data = HomePage::updateOrCreate(
            ['id' => 1],
            [
                'content' => $request->content,
                'image' => $imageName,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
            ]
        );
        return HomePageResource::make($data);
    }
}
