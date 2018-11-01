<?php

namespace WiltersonGarcia\LaravelUploadManager;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LaravelUploadManager
{
    private $events;

    private $disk;

    /**
     * UploadService constructor.
     */
    public function __construct()
    {
        $this->disk = Storage::disk(config('filesystems.default'));
    }

    /**
     * Create file in temp folder
     * @param UploadedFile $file
     * @return Upload
     * @throws Exception
     */
    public function createTemp(UploadedFile $file)
    {
        try {
            $dir = config('laraveluploadmanager.final-url');
            $name = str_random(8) . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $path = $dir . '/' . $name;
            $url = $this->disk->url($path);

            $file->storeAs($dir, $name, config('filesystems.cloud'));

            $upload = new Upload();
            $upload->path = $path;
            $upload->url = $url;
            $upload->name = $file->getClientOriginalName();
            $upload->save();

        } catch (Exception $e) {
            throw new Exception('Error to create a temp file.', 422, [$e->getMessage()]);
        }

        return $upload;
    }

    /**
     * @param Upload $document
     * @throws Exception
     */
    public function moveToFinal(Upload $document)
    {
        if ($document->persisted || !$this->disk->exists($document->path)) {
            throw new Exception('Temporary file not found.', 404);
        }

        $dir = config('laraveluploadmanager.final-url');
        $name = pathinfo($document->path, PATHINFO_BASENAME);
        $path = $dir . '/' . $name;
        $url = $this->disk->url($path);

        try {
            $this->move($document->path, $path);
            $document->path = $path;
            $document->url = $url;
            $document->persisted = true;
            $document->save();
        } catch (Exception $e) {
            throw new Exception('File not found.', 422, [$e->getMessage()]);
        }
    }

    public function find(int $id)
    {
        $document = Upload::findOrFail($id);

        /* @var Upload $document */
        return $document;
    }

    public function destroy(Upload $document)
    {
        $this->disk->delete($document->path);

        /* @var Upload $document */
        return $document;
    }

    private function move(string $from, string $to)
    {
        $this->events[] = [
            'from' => $from,
            'to' => $to
        ];

        $this->disk->move($from, $to);
    }

    public function rollBack()
    {
        while (count($this->events) > 0) {
            $event = array_pop($this->events);
            $this->disk->move($event['to'], $event['from']);
        }
    }
}