<?php

namespace App\Http\Controllers\Admin;

use App\Models\GalleryItem;

class GalleryController extends ImageManagerController
{
    protected function model(): string
    {
        return GalleryItem::class;
    }

    protected function folder(): string
    {
        return 'gallery';
    }

    protected function heading(): string
    {
        return 'Photo gallery';
    }

    protected function routePrefix(): string
    {
        return 'admin.gallery';
    }

    protected function help(): string
    {
        return 'Photos of classes, practicals, events and the campus. The title is shown as the caption.';
    }
}
