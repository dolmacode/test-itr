<?php

namespace App\Services;

use App\Actions\ReadCsvAction;
use App\Models\Product;

class CsvProductService
{
    /**
     * Handle CSV file upload
     *
     * @param $csv_request
     * @return bool
     */
    public function handleCsvUpload($csv_request)
    {
        $csv_source = $this->uploadToStorage($csv_request);

        return $this->uploadToDatabase($csv_request, $csv_source);
    }

    /**
     * Upload file to storage
     *
     * @param $csv_request
     * @return string|bool
     */
    public function uploadToStorage($csv_request): string|bool
    {
        $path = $csv_request->store('csv_archive');

        return $path;
    }

    /**
     * Upload CSV products to database
     *
     * @param $csv_request
     * @param $csv_source
     */
    public function uploadToDatabase($csv_request, $csv_source)
    {
        $data = (new ReadCsvAction())->handle($csv_request);

        unset($data[0]);

        $unloaded_products = [];
        $successfully_uploaded_products = [];

        foreach ($data as $csv_product) {
            try {
                $discontinued = (! empty($csv_product[5]) && ($csv_product[5] == 'yes')) ? now() : null;

                if (
                    ((float)$csv_product[4] >= 5) &&
                    ((float)$csv_product[4] <= 1000) &&
                    ((int)$csv_product[3] >= 10)
                ) {
                    Product::storeProduct([
                        'name' => $csv_product[1],
                        'desc' => $csv_product[2],
                        'code' => $csv_product[0],
                        'stock' => $csv_product[3] ?? 0,
                        'cost' => $csv_product[4],
                        'discontinued' => $discontinued,
                    ]);

                    $successfully_uploaded_products[] = $csv_product[0];
                } else {
                    $unloaded_products[] = $csv_product[0];
                }
            } catch (\Throwable $e) {
                // we're not throwing anything cause it stops uploading
                logger()->warning($e);
            }
        }

        return [
            'errors' => $unloaded_products,
            'success' => $successfully_uploaded_products
        ];
    }
}
