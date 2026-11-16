<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slide;

class SlideController extends ImageManagerController
{
    protected function model(): string
    {
        return Slide::class;
    }

    protected function folder(): string
    {
        return 'slides';
    }

    protected function heading(): string
    {
        return 'Homepage slider';
    }

    protected function routePrefix(): string
    {
        return 'admin.slides';
    }

    protected function help(): string
    {
        return 'These images slide on the homepage. Wide images (about 1600 x 700 px) look best. Use the order number to arrange them, and untick "Show" to hide one without deleting it.';
    }
}
