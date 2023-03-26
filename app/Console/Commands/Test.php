<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Services\Uploader\src\Models\Mediafile;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Throwable
     */
    public function handle()
    {
        //$defaultDiskName = config('filesystems.default');
        //$defaultDiskConfig = config('filesystems.disks.' . $defaultDiskName);
        //dd($defaultDiskConfig);

        $defaultDiskName = Storage::getDefaultDriver();
        //dd($defaultDiskName);
        $defaultDiskConfig = Storage::getConfig();
        //dd($defaultDiskConfig);

        //$filePath = 'uploads/ab/cdef/example.jpg';
        //$exampleFile = storage_path('app') . DIRECTORY_SEPARATOR . 'example.jpg';
        //Storage::put($filePath, file_get_contents($exampleFile));

        //$fileUrl = Storage::url($filePath);
        //$filePath = Storage::path($filePath);
        //dd($filePath);
        //$pathinfo = pathinfo($filePath);
        //dd($pathinfo);
        //dd($fileUrl, $filePath, pathinfo($filePath));

        //$directories = Storage::disk('test')->directories('uploads/ab');
        //dd($directories);

        //Storage::deleteDirectory('uploads/cd');

        $model = Mediafile::find(2);
        $content = Storage::disk($model->disk)->get($model->path);
        $url = Storage::disk($model->disk)->url($model->path);
        dd($content);
    }
}