<?php

namespace App\Http\Controllers;

use App\Models\ContactPage;
use App\Http\Traits\Helpers;
use Illuminate\Http\Request;
use App\Http\Requests\NotifyRequest;
use App\Http\Requests\ContactPageRequest;
use App\Http\Resources\ContactPageResource;

class ContactPageController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ContactPageResource::make(ContactPage::first());
    }

    public function save(ContactPageRequest $request)
    {
        if ($exists = ContactPage::first()) {
            $this->deleteImage($exists->image);
        }

        $imageName = $this->saveImage($request->image);

        $data = ContactPage::updateOrCreate(
            ['id' => 1],
            [
                'email' => $request->email,
                'image' => $imageName,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
            ]
        );
        return ContactPageResource::make($data);
    }

    public function sendMail(NotifyRequest $request)
    {
        $contact = ContactPage::first();
        $contact->sendNotificationEmail($request->name, $request->email, $request->message);
        return response()->json(['message' =>'email sent']);
    }
}
