<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RouterDetailImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\RouterDetail;


class RouterController extends Controller
{
   /**Function to import the data of csv file and display
    * 
    * 
    */
    public function import(Request $request) 
    {
        $rules= array(
            'csv_file'=>'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return view('import_fields')->with('error','Csv File required');
        } 
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header_data=array_slice($data, 0, 1);
        $csv_data=array_shift($data);
        return view('import_fields')->with('csv_data',$data)->with('header',$header_data[0]);
    }

    /**Function to get the csv data displayed and save it to db
    * 
    * 
    */
    public function processImport(Request $request)
    {
        $csvData = $request->all();
        $csvData=$csvData['data'];
        $rules=array(
            'sapid'=>'required|unique:router_details',
            'hostname'=>'required',    
            'loopback' => 'required',
            'macaddress' => 'required|min:17'
        );
        foreach ($csvData as $data) {
            // Setup the validator
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()
            
                ), 400); // 400 being the HTTP code for an invalid request.
            }
        } 
            foreach ($csvData as $data) {
                $routerDetail = new RouterDetail();
                foreach ($data as $field=>$fieldValue) {
                    $routerDetail->$field = $fieldValue;
                }
                $routerDetail->save();
             }
            return response()->json(['success'=>'Data imported Successfully!!']);
    }
}
