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
    
    /**
     * Import data from excel file and display
     *
     * @param  object  $request
     * @return void
     *
     */
    public function import(Request $request) 
    {
        //Get the excel data in array
        $data= \Excel::toArray(new RouterDetailImport(), $request->file('csv_file'));
        $rules= array(
            'csv_file'=>'required|mimes:xls,xlsx',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return view('welcome')->withErrors($validator);
        } 
        $excelData=$data[0]; 
        $headerData=array_change_key_case(array_shift($excelData));
        return view('import_fields')->with('csv_data',$excelData)->with('header',$headerData);
    }
    /**
     *Save the excel data to db
     *
     * @param  object  $request
     * @return view
     *
     */
    public function processImport(Request $request)
    {   
        //Get all the excel data
        $csvData = $request->all();
        array_shift($csvData);
        $rules=array(
            'sapid'=>'required |min:18 |max:18 |alpha_num |unique:router_details',
            'hostname'=>'required |min:14 |max:14|alpha_num  |unique:router_details',    
            'loopback' => 'required |ipv4',
            'macaddress' => 'required |ipv6'
        );

        //Custom messages
        $messages=array(
            'unique' => ':input :attribute already exists.',
            'ipv4'  =>  ':input :attribute must be a valid IPv4 address',
            'alpha_num' =>  ':input :attribute may only contain letters and numbers',
            'ipv6'  =>  ':input :attribute must be a valid IPv6 address'
        );

        $isError=false;
        $errorMessage=array();
        $routerDetail=array();
        //Reformatting the excel data array
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
        //Saving the router details to the database 
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