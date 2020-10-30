<?php

namespace App\Http\Traits;

use Error;
use Throwable;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait Helpers
{
    public function SaveData(Request $request, $data)
    {

        $fields = $request->all();

        if ($fields) {

            // try {

                DB::BeginTransaction();

                foreach ($fields as $key => $value) {
                    $data[$key] = $value;
                }

                $data->save();

                DB::commit();
            // } catch (Throwable $th) {
            //     Log::info(
            //         json_encode(["message" => $th->getMessage()])
            //     );
            //     throw new Error("Unknown column.");
            // }
        }

        return $data;
    }

    private function saveImage($imageFile, $path='Images')
    {
        if (empty($imageFile)) return;

        if (!file_exists($path)) {
            $this->storage()->makeDirectory($path, $mode = 0777, true, true);
        }

        $filename = $this->storage()->putFile($path, new File($imageFile));

        return $filename;
    }

    private function deleteImage($filename)
    {
        $this->storage()->delete($filename);
    }

    private function getSize($filename)
    {
        return $this->storage()->size($filename);
    }

    private function getOriginalName($filename)
    {
        return $filename->getClientOriginalName();
    }

    private function storage()
    {
        return Storage::disk('public');
    }
}
