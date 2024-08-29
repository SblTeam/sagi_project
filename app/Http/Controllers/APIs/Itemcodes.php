<?php
namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Countable;

class Itemcodes extends Controller
{
    public function getdata(Request $request)
    {
        // Fetch database and table name from request
        $databaseName = $request->input('database_name');
        $tableName = $request->input('table_name');
        $level = $request->input('level');

        // Check if the database and table exist
        $tableExists = DB::connection('dynamic')
            ->getSchemaBuilder()
            ->hasTable($tableName);

        if (! $tableExists) {
            return response()->json(['error' => 'Table does not exist'], 404);
        }

        // Fetch data from the specified table
        if(!$level){$data = DB::connection('dynamic')
            ->table($tableName)
            ->where('halt_flag', '0')
            ->where('flag','<>', '1')
            ->orderBy('catgroup', 'asc')
            ->get();}else{
        $data = DB::connection('dynamic')
            ->table($tableName)
            ->where('halt_flag', '0')
            ->where('flag', '1')
            ->where('lel2flag','<>', '1')
            ->orderBy('catgroup', 'asc')
            ->get();
            }

        return response()->json(['data' => $data], 200);
    }

    public function storeflag(Request $request)
    {
        $tableName = $request->input('table_name');
        $itid = $request->input('itemid');

        DB::connection('dynamic')
        ->table($tableName)
        ->where('halt_flag', '0')
        ->whereIn('id', $itid)
        ->update(['flag' => '1']);
        return response()->json(['data' => $itid], 200);
    }
    public function numofitems(Request $request)
    {
        $tableName = $request->input('table_name');
        $level = $request->input('level');
        $profile_name = $request->input('profile_name');
        if($level){$data=DB::connection('dynamic')
        ->table($tableName)
        ->where('halt_flag', '0')
        ->where('flag', '1')->where('lel2flag','<>', '1')->get();}else{
        $data=DB::connection('dynamic')
            ->table($tableName)
            ->where('flag','<>', '1')
            ->where('halt_flag', '0')->get();
        }
        $pro_data=DB::connection('dynamic')
            ->table("contactdetails")
            ->where('name', $profile_name)->get();    
if ($data !== null && count($data) > 0){$count = count($data);}else {$count = 0;}
        return response()->json(['data' => $count,'pro_data' =>$pro_data], 200);
    }
    public function getitemdata(Request $request)
    {
        try {
        // Begin a database transaction
        DB::connection('dynamic')->beginTransaction();
        $tableName = $request->input('table_name');
        $itemid = $request->input('itemid');
        $profile_name = $request->input('profile_name');
        $itmup=DB::connection('dynamic')
        ->table($tableName)
        ->where('halt_flag', '0')
        ->where('flag', '1')
        ->whereIn('id', $itemid)
        ->update(['lel2flag' => '1','updated_at' => now()]);
       //venkey folders
       $pfup=DB::connection('dynamic')
        ->table('contactdetails')
        ->where('name', $profile_name)
        ->update(['auth_flag2' => '1','updated_at' => now()]);
        if ($itmup !== false && $pfup !== false) {
            DB::connection('dynamic')->commit();
        
        $prodata=DB::connection('dynamic')
        ->table('contactdetails')
        ->where('name', $profile_name)
        ->where('auth_flag2' , '1')->get();

        $data=DB::connection('dynamic')
        ->table($tableName)
        ->where('halt_flag', '0')
        ->whereIn('id', $itemid)
        ->where('flag', '1')->where('lel2flag', '1')->get();
        return response()->json(['data' => $data,'prodata' => $prodata], 200);}else {
            DB::connection('dynamic')->rollBack();
            return response()->json(['error' => 'Failed to update records'], 500);
        }
    } catch (\Exception $e) {
        DB::connection('dynamic')->rollBack();
        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
    }

    public function numofitemswtitem(Request $request)
    {
        $tableName = $request->input('table_name');
        $itemid = $request->input('itemid');
        $columns = ['flag', 'lel2flag'];
       $data=DB::connection('dynamic')
        ->table($tableName)
        ->select($columns)
        ->where('halt_flag', '0')
        ->where('id', $itemid)->get();    
        return response()->json(['data' => $data], 200);
    }
}
?>