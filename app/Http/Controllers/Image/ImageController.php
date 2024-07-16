<?php

namespace App\Http\Controllers\Image;

use App\Http\Controllers\Controller;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(private ImageService $service)
    {
    }

    public function show(string $image)
    {
        return $this->service->getImage($image);
    }
}
