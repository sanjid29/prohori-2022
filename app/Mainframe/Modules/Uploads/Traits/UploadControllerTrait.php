<?php

namespace App\Mainframe\Modules\Uploads\Traits;

use App\Mainframe\Modules\Uploads\UploadController;
use App\Upload;
use Illuminate\Http\Request;
use Response;
use ZipArchive;

/** @mixin UploadController $this */
trait UploadControllerTrait
{
    /** @var null|array|bool|\Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[] */
    public $file;

    /**
     * @param  Request  $request
     * @return \App\Mainframe\Features\Modular\ModularController\ModularController|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|void
     */
    public function store(Request $request)
    {
        if (!user()->can('create', $this->model)) {
            return $this->permissionDenied();
        }

        if (!$this->file = $this->getFile()) {
            return $this->fail('No file in http request')->send();
        }

        $this->element = $this->model; // Create an empty model to be stored.
        /** @var Upload $upload */
        $upload = $this->element;

        # type is too common and can interfere with an existing type field in form.
        # So, we shall take input from upload_type files instead.
        if ($type = $request->get('upload_type')) {
            $request->merge(['type' => $type]);
        }

        $this->fill();
        $upload->fillModuleAndElement('uploadable');
        $upload->name = $this->file->getClientOriginalName();
        $upload->bytes = $this->file->getSize();
        $upload->ext = $this->file->getClientOriginalExtension();

        if (!$path = $this->attemptUpload()) {
            return $this->fail('Can not move file to destination from tmp')->send();
        }

        $upload->path = $path;

        // if($dimensions = $this->getImageDimension($file)){
        //     $upload->width = $dimensions['width'];
        //     $upload->height = $dimensions['height'];
        // }

        $this->attemptStore();

        if (!$this->isValid()) {
            $upload = null;
        }

        return $this->load($upload)->send();
    }

    /**
     * Get the uploaded file from request
     *
     * @return null|array|bool|\Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]
     */
    public function getFile()
    {
        if ($this->file) {
            return $this->file;
        }

        $fileRequestField = request()->get('file_field', 'file');

        if (!request()->hasFile($fileRequestField)) {
            return false;
        }

        $this->file = request()->file($fileRequestField);

        return $this->file;
    }

    /**
     * Physically move the file to a location.
     *
     * @return bool|string
     */
    public function attemptUpload()
    {
        return $this->attemptStorageUpload();
        // return $this->attemptLocalUpload();
        // return $this->attemptAwsUpload();

    }

    /**
     * Upload in the same local server
     *
     * @return string
     */
    public function attemptLocalUpload()
    {
        $directory = $this->uploadDirectory();
        $fileRelativePath = $this->localRelativePath();

        if ($this->file->move($directory, $fileRelativePath)) {
            return $fileRelativePath;
        }
    }

    /**
     * Upload in the /storage/.. same local server
     *
     * @return string
     */
    public function attemptStorageUpload()
    {
        $path = $this->getFile()->storeAs($this->uploadDirectory(), $this->uniqueFileName());
        return $path;
    }

    /**
     * Upload in aws
     *
     * @return mixed
     */
    public function attemptAwsUpload()
    {
        if ($awsPath = \Storage::disk('s3')->putFile(env('APP_ENV'), $this->file, 'public')) {
            return \Storage::disk('s3')->url($awsPath);
        }
    }

    public function rootDirectory()
    {
        return \request('bucket') ?: trim(config('mainframe.config.upload_root'), "\\/ ");
    }

    /**
     * Relative path to local directory inside public
     * Upload location: public/{upload_root}/{tenant_id}/YYYY/mm/dd/HH/ii
     *                  public/files        /1          /2021/12/25/23/59
     * For uploads where there is no tenant the default tenant_id=0
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public function uploadDirectory()
    {
        // If the file is already uploaded the use the same director to upload the updated file
        if ($path = optional($this->element)->path) {
            return dirname($path);
        }

        // Get the root directory
        $dir = $this->rootDirectory();

        // ->files/{tenant_id}
        $tenantId = '0';
        if ($uploadable = $this->element->uploadable) {
            $tenantId = $uploadable->tenant_id ?? $tenantId;
        } elseif (isset($this->tenant_id)) {
            $tenantId = $this->tenant_id;
        }
        $dir .= '/'.$tenantId;

        // ->public/files/{tenant_id}/2021/12/25/23/59
        $dir .= "/".date('Y')
            .'/'.date('m')
            .'/'.date('d')
            .'/'.date('H')
            .'/'.date('i');

        return $dir;
    }

    /**
     * Relative file path
     *
     * @return string
     */
    public function localRelativePath()
    {
        return '/'.trim($this->uploadDirectory(), '/').'/'.$this->uniqueFileName();
    }

    /**
     * Generate unique file name by adding a random string in the beginning
     *
     * @return string
     */
    public function uniqueFileName()
    {
        // Old implementation that retained file name
        // ----------------------------------------------
        // $fileName = urlencode($this->getFile()->getClientOriginalName()); // original filename with extension
        // $fileName = substr($fileName, -30, 30); // Take last 30 characters
        // return \Str::random(4)."_".urlencode($fileName);

        // New implementation that forces a valid file name
        //-------------------------------------------------
        $namePart = \Str::random(4)."_".now()->format('YmdHis');
        $ext = $this->getFile()->getClientOriginalExtension();

        return $namePart.'.'.$ext;
    }

    /**
     * Get dimension of image
     *
     * @return array|bool
     */
    public function getImageDimension()
    {
        if (isImageExtension($this->file->getClientOriginalExtension())) {
            [$width, $height] = getimagesize($this->file->getPathname());

            return ['width' => $width, 'height' => $height];
        }

        return false;
    }

    /**
     * Downloads the file with HTTP response to hide the file url
     *
     * @param $uuid
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\StreamedResponse|void
     */
    public function download($uuid)
    {
        $upload = Upload::where('uuid', $uuid)
            ->remember(timer('long'))
            ->first();

        if (!$upload) {
            return $this->notFound();
        }

        // Download from /storage/app..
        if (\Storage::exists($upload->path)) {
            return \Storage::download($upload->path);
        }

        // Download from /public/...
        return \Response::download(public_path().$upload->path);

    }

    /**
     * Check if file is image
     *
     * @return bool
     */
    public function fileIsImage()
    {
        // $allowedMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];

        if (\Str::contains($this->file->getMimeType(), 'image/')) {
            return true;
        }

        return false;

    }

    /**
     * Reorder JobUnits/Paragraphs with in paragraphs.
     * IDs are sent as an array job_unit_ids
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        $ids = \request('ids');
        $i = 1;
        foreach ($ids as $id) {
            Upload::where('id', $id)->update(['order' => $i++]);
        }

        return $this->load(['ids' => $ids])->success('Order has been updated')->json();
    }

    /**
     * Show images from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function showImage($id)
    {
        $upload = Upload::findOrFail($id);

        $path = $upload->path;

        if (!\Storage::exists($path)) {
            abort(404);
        }
        $file = \Storage::get($path);
        $type = \Storage::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;

    }

    /**
     * Update
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|void
     */
    public function updateExistingUpload()
    {
        if (!user()->can('update', $this->model)) {
            return $this->permissionDenied();
        }

        if (!$this->file = $this->getFile()) {
            return $this->fail('No file in http request')->send();
        }

        if (!$id = \request('upload_id')) {
            return $this->fail('No upload id found')->send();
        }

        $this->element = Upload::findOrFail($id); // Create an empty model to be stored.
        /** @var Upload $upload */
        $upload = $this->element;
        $oldFilePath = $upload->path;

        $upload->name = $this->file->getClientOriginalName();
        $upload->bytes = $this->file->getSize();
        $upload->ext = $this->file->getClientOriginalExtension();

        if (!$path = $this->attemptUpload()) {
            return $this->fail('Can not move file to destination from tmp')->send();
        }

        $upload->path = $path;

        // if($dimensions = $this->getImageDimension($file)){
        //     $upload->width = $dimensions['width'];
        //     $upload->height = $dimensions['height'];
        // }

        $this->attemptStore();

        if (!$this->isValid()) {
            $upload = null;
        } else {
            Upload::deleteFilePath($oldFilePath); // Delete the physical file
        }

        return $this->load($upload)->send();
    }

    /**
     * Download files as zip
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|void
     */
    public function downloadZip()
    {
        $moduleId = \request('module_id');
        $elementId = \request('element_id');

        if (!$moduleId || !$elementId) {
            abort(400, 'Module and element id not valid');
        }

        $query = Upload::query();
        if ($moduleId) {
            $query->where('module_id', $moduleId);
        }
        if ($elementId) {
            $query->where('element_id', $elementId);
        }
        if ($type = \request('type')) {
            $query->where('type', $type);
        }
        // else {
        //     // Download all. No query param needs to be added
        // }

        $uploads = $query->where('is_active', 1)->get();

        $fileName = \Str::random(8)."-".time().'.zip';
        $tempPath = '/temp/'.$fileName; // The zip will be created in this location
        $zip = new ZipArchive;

        if (true === ($zip->open(public_path($tempPath), ZipArchive::CREATE | ZipArchive::OVERWRITE))) {
            $count = 0;
            foreach ($uploads as $upload) {
                if ($upload->absPath()) {
                    $zip->addFile($upload->absPath(), $upload->name);
                    $count++;
                }
            }
            $zip->close();
        } else {
            abort(400, 'Can not create zip.');
        }

        if (!$count) {
            abort(400, 'No files found');
        }

        return response()->download(public_path($tempPath));

    }
}