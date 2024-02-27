<?php

namespace App\Actions;

class ReadCsvAction
{
    /**
     * Convert CSV to PHP array
     *
     * @param $csv
     * @return array
     */
    public function handle($csv)
    {
        $csvToRead = fopen($csv, 'r');

        while (! feof($csvToRead)) {
            $csvArray[] = fgetcsv($csvToRead, 1000, ',');
        }

        fclose($csvToRead);

        return $csvArray;
    }
}
