<?php

namespace App\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

trait HandlesS3Uploads
{
    protected function uploadToS3($file): string
    {
        dd("Hi", $file);
        $path = "/datahub/". $file_type ."/" . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
        Storage::disk('s3')->put($path, file_get_contents($file));
        return $path;
    }

}
