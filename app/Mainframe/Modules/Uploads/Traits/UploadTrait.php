<?php

namespace App\Mainframe\Modules\Uploads\Traits;

use App\Mainframe\Features\Modular\BaseModule\BaseModule;
use App\Upload;
use File;
use Storage;
use Str;

/** @mixin Upload */
trait UploadTrait
{

    /**
     * get all uploads under a module
     *
     * @param  array  $entry_uuid
     * @param  string  $filter
     * @return mixed
     */
    public static function getList($entry_uuid, $filter = '')
    {
        $uploads = Upload::where('element_uuid', $entry_uuid);

        return $uploads->orderBy('created_at', 'DESC')->get();
    }

    /**
     * During creation of a module entry there is no id but
     * still files can be uploaded.
     *
     * @param $element BaseModule
     */
    public static function linkTemporaryUploads($element)
    {
        Upload::where('element_uuid', $element->uuid)->update([
            'element_id' => $element->id,
            'uploadable_id' => $element->id,
        ]);
    }

    /**
     * The file can be stored under public/* or storage/*. Previously we stored files in public
     * The function determines where the file actually is.
     *
     * @return string
     */
    public function absPath()
    {
        if (Storage::exists($this->path)) {
            return Storage::path($this->path);
        }

        if (File::exists(public_path($this->path))) {
            return public_path($this->path);
        }

        return null;
    }

    /**
     * Get the url for thumbnail of an upload.
     *
     * @return string
     */
    public function thumbnail()
    {
        if ($this->isImage()) {
            return $this->url;
        }
        return $this->extIconPath();

    }

    /**
     * Checks if an upload file is image
     *
     * @return mixed
     */
    public function isImage()
    {
        if (isImageExtension($this->ext)) {
            return true;
        }

        return false;
    }

    public function isPublic()
    {
        return Str::startsWith(trim($this->path, '/'), 'public');
    }

    /**
     * 'file_type_icons' contains number of file type icons.
     *
     * @return string
     */
    public function extIconPath()
    {
        $ext = strtolower($this->ext); // get full lower case extension
        $icon_path = 'mainframe/images/file_type_icons/'.$ext.'.png';

        if (!\File::exists($icon_path)) {
            $icon_path = 'mainframe/images/file_type_icons/noimage.png';
        }

        return asset($icon_path);
    }

    /**
     * Generate masked and plain url of the uploaded file.
     *
     * @param  bool  $auth  set false to generate plain url.
     * @return string
     */
    public function downloadUrl()
    {
        return route('download', $this->uuid);
    }

    /**
     * Fill extension
     *
     * @return $this
     */
    public function fillExtension()
    {
        $this->ext = extFrmPath($this->path); // Store file extension separately

        return $this;
    }

    public function fileName()
    {
        return basename($this->path);
    }

    public function fileNameWithoutExt()
    {
        return basename($this->path, '.'.$this->ext);
    }

    public function directory()
    {
        $path_parts = pathinfo($this->path);

        return $path_parts['dirname'] ?? null;
    }

    public function relativePath()
    {
        return trim($this->path, '/\\');
    }

    /**
     * Rename with full name and extension
     *
     * @param  string  $newNameWithExt  some-file.mp3
     * @return bool
     */
    public function rename($newNameWithExt)
    {
        $newPath = $this->directory().\Str::start($newNameWithExt, '/');
        \File::move(trim($this->path, '/\\'), trim($newPath, '/\\'));

        return $this->update(['path' => $newPath]);
    }

    /**
     * Rename only name part
     *
     * @param  string  $newName  some-file-name-without-ext
     * @return bool
     */
    public function renameName($newName)
    {
        $newNameWithExt = $newName.\Str::start($this->ext, '.'); // Add extension

        return $this->rename($newNameWithExt);

    }

    /**
     * Copy to
     *
     * @param $to
     * @return bool
     */
    public function copy($to)
    {
        $to = trim($to, '/\\');

        $path_parts = pathinfo($to);
        $toDirectory = $path_parts['dirname'];

        \File::makeDirectory(public_path().'/'.$toDirectory, 0777, true, true);

        return \File::copy(trim($this->path, '/\\'), $to);
    }
    /*
    |--------------------------------------------------------------------------
    | Section: Query scopes + Dynamic scopes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Section: Accessors
    |--------------------------------------------------------------------------
    */
    // public function getFirstNameAttribute($value) { return ucfirst($value); }

    /*
    |--------------------------------------------------------------------------
    | Section: Mutators
    |--------------------------------------------------------------------------
    */
    // public function setFirstNameAttribute($value) { $this->attributes['first_name'] = strtolower($value); }

    /*
    |--------------------------------------------------------------------------
    | Section: Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Creates a URL to a file
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        // First check if file exists in storage/app/files
        if (Storage::exists($this->path)) {

            // 1- Check if storage is public. If public share the public URL
            if ($this->isPublic()) {
                return url(Storage::url($this->path));
            }

            // No! no direct access
            if ($this->isImage()) {
                return route('show.image', $this->id);
            }

            return $this->downloadUrl();
        }

        // Check if file exists in public/files
        if (File::exists(public_path($this->path))) {
            return asset($this->path);
        }

        return asset('mainframe/images/noimage.png');
    }

    public function getDirAttribute()
    {
        return public_path().$this->path;
    }

    /*
    |--------------------------------------------------------------------------
    | Section: Relations
    |--------------------------------------------------------------------------
    */
    public function uploadable()
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Todo: Helper functions
    |--------------------------------------------------------------------------
    | Todo: Write Helper functions in the UploadHelper trait.
    */

    /**
     * Deletes the previously uploaded file of the same type.
     * This function is useful for uploading profile pic
     * where there latest pic will discard the last one.
     */
    public function deletePreviousOfSameType()
    {
        if (isset($this->uploadable)) {
            $this->uploadable->uploads()
                ->where('type', $this->type)
                ->where('id', '!=', $this->id)
                ->delete();

        }
    }

    /**
     * Delete a file in given path
     *
     * @param $path
     * @return bool
     */
    public static function deleteFilePath($path)
    {
        if (\Storage::exists($path)) {
            return \Storage::delete($path);
        }

        if (File::exists(public_path($path))) {
            return File::delete($path);
        }

        return false;
    }
}