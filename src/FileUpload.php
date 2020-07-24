<?php

namespace mkn\LaravelFileUploader\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FileUpload extends Model
{
    protected $fillable = [
        'original_name',
        'file_name',
        'file_extension',
        'file_directory',
        'content_type',
        'is_encrypted',
    ];

    public static function single($file, $directory, $encrypt = 1)
    {
        $fileContent = $file->get();
        $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $file_extension = $file->extension();
        $content_type = $file->getMimeType();

        // Encrypt the Content
        if ($encrypt) {
            $fileContent = encrypt($fileContent);
        }

        $file_name = time() . '_' . $original_name;
        $full_file_name = $directory.'/'.$file_name.'.'.$file_extension;
        Storage::disk('local')->put($full_file_name, $fileContent);
        $File = new FileUpload();
        return $File->create([
            'original_name' => $original_name,
            'file_name' => $file_name,
            'file_extension' => $file_extension,
            'file_directory' => $directory,
            'content_type' => $content_type,
            'is_encrypted' => $encrypt,
        ])->id;
    }

    public static function multiple($files, $directory, $encrypt = 1)
    {
        $file_id_arr = array();
        foreach ((array)$files as $key => $file) {
            $file_id_arr[$key] = self::uploadSingleFile($file, $directory, $encrypt);
        }
        return $file_id_arr;
    }

    public function deleteFile()
    {
        $full_name = $this->getFullFileName();
        Storage::delete($full_name);
        return;
    }

    public function showFile()
    {
        $Content = $this->decryptFile();

        return Response::make($Content, 200, [
          'Content-Type'        => $this->content_type,
          'Content-Disposition' => 'inline;'
        ]);
    }

    public function downloadFile($fileName)
    {
        return response()->streamDownload(function () {
            echo $this->decryptFile();
        }, $fileName);
    }

    public function decryptFile()
    {
        $full_file_name = $this->getFullFileName();
        $Content = Storage::get($full_file_name);

        if ($this->is_encrypted) {
            return decrypt($Content);
        }
        return $Content;
    }

    private function getFullFileName()
    {
        return $this->file_directory.'/'.$this->file_name.'.'.$this->file_extension;
    }
}
