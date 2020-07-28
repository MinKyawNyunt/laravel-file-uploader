# Laravel File Uploader
Ready Made File model and table including encryption and 100% editable.

## Installation

```
composer require mkn/laravel-file-uploader
php artisan migrate  --path=vendor/mkn/laravel-file-uploader/src/database/migrations
php artisan vendor:publish --provider="mkn\LaravelFileUploader\FileUploaderServiceProvider"
```

## Usage

```
use App/FileUpload;

$file = FileUpload::single($request->paramenter, $directory); //for single upload return an id
$file = FileUpload::multiple($request->paramenters, $directory); //for multiple upload return array with ids

$file = FilUpload::findOrFail(1);
$file->deleteFile(); //to delete file
$file->showFile(); //to show file
$file->downloadFile(); //to download file
```

** All code can edit in App/FileUpload **
