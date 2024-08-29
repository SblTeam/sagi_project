<?php
namespace App\Imports;

use App\Models\ims_itemcodes;
use App\Models\ims_itemtypes;
use App\Models\ims_itemunits;
use App\Models\ims_taxcodes;
use App\Models\contactdetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class ImportUser implements ToModel, WithHeadingRow
{
    private $errors = []; // Array to hold errors
    private $validtax = [];
    private $validCategoryGroups = [];
    private $validCategories = [];
    private $validunits = [];
    private $validunits1 = [];  // Array to hold valid category groups

    public function __construct()
    {
        // Fetch valid category groups from the database
        $this->validCategoryGroups = ims_itemtypes::pluck('catgroup')->toArray();
        $this->validunits = ims_itemunits::pluck('sunits')->toArray();
        $this->validtax = ims_taxcodes::where('code', 'like', '%gst%')
            ->pluck('code')
            ->unique()
            ->toArray();

            $this->validCategories = ims_itemtypes::select('catgroup', 'type')
            ->get()
            ->groupBy('catgroup')
            ->map(function ($group) {
                return $group->pluck('type')->toArray();
            })
            ->toArray();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

      $activeContacts = contactdetails::where('active_flag', 1)->pluck('company')->first();

        // Define base validation rules
        $rules = [
'item_code' => ['required', 'string', 'max:255', 'unique:ims_itemcodes,code', 'regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[a-zA-Z0-9]+$/'],

'description' => ['required', 'max:255', 'regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[a-zA-Z0-9]+(?: [a-zA-Z0-9]+)*$/'],


            'category_group' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, $this->validCategoryGroups)) {
                        $fail('The selected category group is not present in category group list.');
                    }
                },
            ],
            'category' => [
              'required',
              'max:255',
              function ($attribute, $value, $fail) use ($row) {
                  if (!isset($this->validCategories[$row['category_group']]) || !in_array($value, $this->validCategories[$row['category_group']])) {
                      $fail('The selected category is not valid for the given category group.');
                  }
              },
          ],
            'type' => ['required', 'max:255'],
            'storage_units_of_measure' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, $this->validunits)) {
                        $fail('The selected storage units of measure is not present in the list.');
                    }
                },
            ],


      'bag_weight' => ['required', 'regex:/^(?!\s)(?!.*\s$)\d+(\.\d+)?$/', 'max:255'],
'packet_weight' => ['required', 'regex:/^(?!\s)(?!.*\s$)\d+(\.\d+)?$/', 'max:255'],

            'consumption_units_of_measure' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, $this->validunits)) {
                        $fail('The selected consumption units of measure is not present in the list.');
                    }
                },
            ],
            'sales_units_of_measure' => [
              'required',
              'string',
              'max:255',
              function ($attribute, $value, $fail) {
                  if (strtoupper($value) !== 'BAGS') {
                      $fail('The sales units of measure  is not present in the list.');
                  }
              },
          ],
            'source' => ['required', 'max:255'],
            'usage' => ['required', 'max:255'],
            'item_ac' => ['required', 'max:255'],
            'tax_applicable' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, $this->validtax)) {
                        $fail('The selected tax is not present in tax list.');
                    }
                },
            ],
            'consumption_ac' => ['required', 'max:255'],
            'cogs_ac' => ['required', 'max:255'],
            'sales_return_ac' => ['required', 'max:255'],
 'ean_no' => ['required', 'regex:/^(?!\s)(?!.*\s$)[0-9]{13}$/', 'digits:13'],
'hsnsac' => ['required', 'regex:/^(?!\s)(?!.*\s$)[0-9]{6,10}$/', 'min:6', 'max:10'],

            'sales_ac' => ['required', 'max:255'],
        ];

        // Add conditional rules
        if ($row['usage'] === 'Sale') {
            $rules = array_merge($rules, [
                'item_ac' => ['required', 'max:255'],
                'cogs_ac' => ['required', 'max:255'],
                'sales_return_ac' => ['required', 'max:255'],
                'sales_ac' => ['required', 'max:255'],
                'consumption_ac' => ['nullable', 'max:255'],
            ]);
        } elseif ($row['usage'] === 'General Consumption') {
            $rules = array_merge($rules, [
                'consumption_ac' => ['required', 'max:255'],
                'item_ac' => ['nullable', 'max:255'],
                'cogs_ac' => ['nullable', 'max:255'],
                'sales_return_ac' => ['nullable', 'max:255'],
                'sales_ac' => ['nullable', 'max:255'],
            ]);
        } else {
            // If not sales or general consumption, set these fields to 'nullable'
            $rules = array_merge($rules, [
                'item_ac' => ['nullable', 'max:255'],
                'cogs_ac' => ['nullable', 'max:255'],
                'sales_return_ac' => ['nullable', 'max:255'],
                'sales_ac' => ['nullable', 'max:255'],
                'consumption_ac' => ['nullable', 'max:255'],
            ]);
        }
        

        // Define custom error messages
        $messages = [
            'item_code.unique' => 'The item code already exists in the database. Please use a different code.',
            'item_code.regex' => 'For code Only alphabets (A-Z), numbers (0-9) are allowed  and Consecutive,Leading,Trailing spaces not allowed',
            'description.regex' => 'For description Only alphabetic characters (A-Z, a-z), numbers (0-9), and a single space are allowed and Consecutive,Leading,Trailing spaces not allowed',
            'bag_weight.regex' => 'Only numbers (0-9) and one decimal point are allowed  and Consecutive,Leading,Trailing spaces not allowed',
            'packet_weight.regex' => 'Only numbers (0-9) and one decimal point are allowed  and Consecutive,Leading,Trailing spaces not allowed',
            'ean_no.regex' => 'EAN must be exactly 13 characters long and Only numbers (0-9) are allowed and Consecutive,Leading,Trailing spaces not allowed',
            'hsnsac.regex' => 'HSN must be at least 6 characters long and HSN must be no more than 10 characters long Only numbers (0-9) are allowed  and Consecutive,Leading,Trailing spaces not allowed',
        ];

        // Validate the row data
        $validator = Validator::make($row, $rules, $messages);

        if ($validator->fails()) {
            $this->errors[] = [
                'row' => $row,
                'errors' => $validator->errors()->all(),
            ];
            return null; // Skip this row if validation fails
        }

        if (ims_itemcodes::where('code', $row['item_code'])->exists()) {
            $this->errors[] = [
                'row' => $row,
                'errors' => ['Item code already exists.'],
            ];
            return null;
        }

        return new ims_itemcodes([
            'catgroup' => trim($row['category_group']),
            'code' => $row['item_code'],
            'description' => $row['description'],
            'cat' => $row['category'],
            'type' => $row['type'],
            'pieces' => $row['noof_pieces'],
            'weight' => $row['bag_weight'],
            'packetweight' => $row['packet_weight'],

            'sunits' => $row['storage_units_of_measure'],
            'cunits' => $row['consumption_units_of_measure'],
            'sales_units' => $row['sales_units_of_measure'],

            'source' => $row['source'],
            'iusage' => $row['usage'],
            'iac' => $row['item_ac'],
            'tax_applicable' => $row['tax_applicable'],
            'wpac' => $row['consumption_ac'],
            'cogsac' => $row['cogs_ac'],
            'srac' => $row['sales_return_ac'],
            'ean_no' => $row['ean_no'],
            'hsn' => $row['hsnsac'],
            'sac' => $row['sales_ac'],
            'client' => $activeContacts,
            'updated_by' =>  session()->get("valid_user"),

        ]);
    }


    public function getErrors()
    {
        return $this->errors;
    }
}
