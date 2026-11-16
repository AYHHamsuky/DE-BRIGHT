<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Shared upload / reorder / hide / delete logic for the homepage slider
 * and the photo gallery. Each child only says which model and folder to use.
 */
abstract class ImageManagerController extends Controller
{
    /** @return class-string<\Illuminate\Database\Eloquent\Model> */
    abstract protected function model(): string;

    /** Folder inside public/uploads, e.g. "slides". */
    abstract protected function folder(): string;

    /** Heading shown on the admin page, e.g. "Homepage slider". */
    abstract protected function heading(): string;

    /** Route name prefix, e.g. "admin.slides". */
    abstract protected function routePrefix(): string;

    abstract protected function help(): string;

    public function index()
    {
        $model = $this->model();

        return view('admin.images', [
            'items' => $model::orderBy('sort_order')->orderBy('id')->get(),
            'heading' => $this->heading(),
            'routePrefix' => $this->routePrefix(),
            'help' => $this->help(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title' => ['nullable', 'string', 'max:150'],
        ]);

        $model = $this->model();
        $file = $request->file('image');
        $name = Str::random(24).'.'.$file->extension();
        $file->move(public_path('uploads/'.$this->folder()), $name);

        $model::create([
            'title' => $request->input('title'),
            'image' => 'uploads/'.$this->folder().'/'.$name,
            'sort_order' => ($model::max('sort_order') ?? 0) + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Image added.');
    }

    public function update(Request $request, int $id)
    {
        $model = $this->model();
        $item = $model::findOrFail($id);

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $item->update($data);

        return back()->with('success', 'Saved.');
    }

    public function destroy(int $id)
    {
        $model = $this->model();
        $item = $model::findOrFail($id);

        // Only remove files that were uploaded through the admin panel.
        if (Str::startsWith($item->image, 'uploads/')) {
            $path = public_path($item->image);
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $item->delete();

        return back()->with('success', 'Image deleted.');
    }
}
