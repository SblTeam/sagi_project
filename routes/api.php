<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIs\Itemcodes;
use App\Http\Controllers\APIs\Invoicedata;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::namespace('APIs')->group(function () {
    Route::post('/invdata_invoice', [Invoicedata::class, 'invdata_invoice'])->name('APIs-Invoicedata.invdata_invoice');
});
Route::middleware(['dynamic.database'])->group(function () {
    Route::post('/invdata', [Invoicedata::class, 'getdata'])->name('APIs-Invoicedata.getdata');
    Route::post('/getinvoicedata', [Invoicedata::class, 'getinvoicedata'])->name('APIs-Invoicedata.getinvoicedatas');
    Route::post('/updateinvoicedata', [Invoicedata::class, 'updateinvoicedata'])->name('APIs-Invoicedata.updateinvoicedata');
    Route::post('/viewinvoice', [Invoicedata::class, 'viewinvoice'])->name('APIs-Invoicedata.viewinvoice');

    Route::post('/getitems', [Itemcodes::class, 'getdata'])->name('APIs-Itemcodes.getdata');
    Route::post('/storeflag', [Itemcodes::class, 'storeflag'])->name('APIs-Itemcodes.storeflag');
    Route::post('/numofitems', [Itemcodes::class, 'numofitems'])->name('APIs-Itemcodes.numofitems');
    Route::post('/numofitemswtitem', [Itemcodes::class, 'numofitemswtitem'])->name('APIs-Itemcodes.numofitemswtitem');
    Route::post('/getitemdata', [Itemcodes::class, 'getitemdata'])->name('APIs-Itemcodes.getitemdata');
    Route::post('/getitemdata2', [Itemcodes::class, 'getitemdata2'])->name('APIs-Itemcodes.getitemdata2');
});
