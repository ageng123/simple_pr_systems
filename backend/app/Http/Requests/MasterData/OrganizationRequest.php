<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'organization_name' => 'required',
            'organization_code' => '',
            'organization_parent' => "",
            'organization_short_name' => '',
            'organization_status' => "",
            "organization_description" => ""
        ];
    }
}
