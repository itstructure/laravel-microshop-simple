<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Class FileController
 *
 * @package App\Http\Controllers\Admin
 */
class FileController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.file.index', [
            'title' => 'File manager',
            'fileManagerRoute' => route('uploader_file_list_manager')
        ]);
    }
}
