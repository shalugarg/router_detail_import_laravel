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
        array_shift($csvData);
        $rules=array(
            'Sapid'=>'required |min:18 |max:18 |alpha_num |unique:router_details',
            'hostname'=>'required |min:14 |max:14|alpha_num  |unique:router_details',    
            'loopback' => 'required |ipv4',
            'macaddress' => 'required |ipv6'
        );
        $messages=array(
            'unique' => ':input :attribute already exists.',
            'ipv4'  =>  ':input :attribute must be a valid IPv4 address',
            'alpha_num' =>  ':input :attribute may only contain letters and numbers',
            'ipv6'  =>  ':input :attribute must be a valid IPv6 address'
        );

        $isError=false;
        $errorMessage=array();
        $routerDetail=array();
        foreach ($csvData as $key=>$data) {
            $routerDetail [substr($key, -1)][substr_replace($key ,"", -1)] =$data;
        }
        foreach($routerDetail as $detail){
            // Setup the validator
            $validator = Validator::make($detail, $rules, $messages);
            if ($validator->fails()) {
                $isError=True;
                $errorMessage[]=$validator->getMessageBag();
               
            }
        }
        if($isError){
              return Response::json(array(
                     'success' => false,
                     'errors' => $errorMessage
            
                 ), 400); // 400 being the HTTP code for an invalid request.
        }
           
        foreach ($routerDetail as $detail) {
            $routerObject = new RouterDetail();
            foreach ($detail as $field=>$fieldValue) {
                $routerObject->$field = $fieldValue;
            }
            $routerObject->save();
            }
        return response()->json(['success'=>'Data imported Successfully!!']);
    }
}