<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMappingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create mapping');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'description' => 'required|string|max:255',
            'table_name' => 'required|string|max:255',
            'columns' => 'required|array|min:1',
            'columns.*.excel_column_index' => 'required|string|max:10',
            'columns.*.table_column_name' => 'required|string|max:255',
            'columns.*.data_type' => 'required|string|in:string,integer,date',
            'columns.*.is_required' => 'required|boolean',
        ];
    }
}