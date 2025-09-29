<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMappingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create mapping');
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:mapping_indices,code|max:255',
            'description' => 'required|string|max:255',
            'table_name' => 'required|string|max:255',
            'header_row' => 'required|integer|min:1',
            'columns' => 'required|array|min:1',
            'columns.*.excel_column_index' => 'required|string|max:10',
            'columns.*.table_column_name' => 'required|string|max:255',
            'columns.*.data_type' => 'required|string|in:string,integer,date',
            'columns.*.is_required' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Mapping code is required.',
            'code.unique' => 'This mapping code already exists.',
            'columns.required' => 'At least one column mapping is required.',
            'columns.*.excel_column_index.required' => 'Excel column index is required for all columns.',
            'columns.*.table_column_name.required' => 'Database column name is required for all columns.',
        ];
    }
}