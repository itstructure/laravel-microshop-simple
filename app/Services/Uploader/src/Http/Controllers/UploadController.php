<?php

namespace App\Services\Uploader\src\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\Uploader\src\Facades\Uploader;

/**
 * Class UploadController
 * @package App\Services\Uploader\src\Http\Controllers
 */
class UploadController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        try {
            $data = $request->post('data');
            $file = $request->file('file');

            if (!Uploader::upload($data, $file) && Uploader::hasErrors()) {
                return response()->json([
                    'success' => false,
                    'errors' => Uploader::getErrors()->getMessages(),
                ]);
            }
            return response()->json([
                'success' => true
            ]);

        } catch (Exception $exception) {
            abort($exception->getCode(), $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $id = $request->post('id');
            $data = $request->post('data');
            $file = $request->hasFile('file') ? $request->file('file') : null;

            if (!Uploader::update($id, $data, $file) && Uploader::hasErrors()) {
                return response()->json([
                    'success' => false,
                    'errors' => Uploader::getErrors()->getMessages(),
                ]);
            }
            return response()->json([
                'success' => true
            ]);

        } catch (Exception $exception) {
            abort($exception->getCode(), $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $id = $request->post('id');

            if (!Uploader::delete($id)) {
                return response()->json([
                    'success' => false
                ]);
            }
            return response()->json([
                'success' => true
            ]);

        } catch (Exception $exception) {
            abort($exception->getCode(), $exception->getMessage());
        }
    }
}
