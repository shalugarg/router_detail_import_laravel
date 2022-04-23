<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CsvImportRequest extends FormRequest
{
    /**
    *Determine if the user is authorised to make this request
    */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'csv_file' => 'required|file'
        ];
    }
}

?>