<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvUploadRequest;
use App\Services\CsvProductService;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function upload(CsvUploadRequest $request)
    {
        $csv_request = $request->file('file');

        $result = (new CsvProductService())->handleCsvUpload($csv_request);

        if (!empty($result['success'])) {
            Session::flash('success', 'CSV uploaded successfully, Unable to load next: '. implode(', ', $result['errors']));
        } else {
            Session::flash('error', 'Error while uploading CSV, Unable to load next: '. implode(', ', $result['errors']));
        }

        return back();
    }
}
