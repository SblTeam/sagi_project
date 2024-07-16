<?php

namespace App\Imports;

use App\Models\ims_itemcodes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ImportUser implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

// echo "<pre>";
// print_r($row);
        return new ims_itemcodes([


            'code' => $row['item_code'],
            'description' => $row['description'],
            'catgroup' => $row['category_group'],
            'cat' => $row['category'],
            'type' => $row['type'],

            'sunits' => $row['storage_units_of_measure'],
            'cunits' => $row['consumption_units_of_measure'],
            'sales_units' => $row['sales_units_of_measure'],
            'source' => $row['source'],
            'iusage' => $row['usage'],
            'iac' => $row['item_ac'],
            'wpac' => $row['consumption_ac'],
            'cogsac' => $row['cogs_ac'],
            'srac' => $row['sales_return_ac'],
            'ean_no' => $row['ean_no'],
            'hsn' => $row['hsnsac'],
            'sac' => $row['sales_ac'],


        ]);
    }
}
