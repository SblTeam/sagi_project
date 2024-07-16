<?php

namespace App\Imports;

use App\Models\ims_itemcodes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class YourImportClassName implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new ims_itemcodes([
            // Map the Excel columns to your model's attributes
            'attribute1' => $row['excel_column1'],
            'attribute2' => $row['excel_column2'],
            // Add more attributes as needed
        ]);
    }
}
