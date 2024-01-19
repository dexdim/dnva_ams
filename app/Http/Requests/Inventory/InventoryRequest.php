<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        } elseif ($this->isMethod('put')) {
            return $this->updateRules();
        }
    }
    /**
     * Define validation rules to store method for resource creation
     *
     * @return array
     */
    public function createRules(): array
    {
        return [
            'idcode' => 'required|string|max:30',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'required|string|max:1000',
            'brand' => 'required|string|max:45',
            'serialnumber' => 'required|string|max:45',
            'purchasecost' => 'required|numeric',
            'purchasedate' => 'required|string|max:45',
            'supplier' => 'required|string|max:45',
            'name' => 'required|string|max:255|exists:employees,name',
            'email' => 'required|string|max:255',
            'dept' => 'required|string|max:255',
            'status' => 'required|string|max:45',
            'history' => 'required|string|max:255',
            'notes' => 'required|string|max:1000',
            'checkdate' => 'required|string|max:45',
            'checkedby' => 'required|string|max:45',
        ];
    }

    /**
     * Define validation rules to update method for resource update
     *
     * @return array
     */
    public function updateRules(): array
    {
        return [
            'idcode' => 'required|string|max:30',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'required|string|max:1000',
            'brand' => 'required|string|max:45',
            'serialnumber' => 'required|string|max:45',
            'purchasecost' => 'required|numeric',
            'purchasedate' => 'required|string|max:45',
            'supplier' => 'required|string|max:45',
            'name' => 'required|string|max:255|exists:employees,name',
            'email' => 'required|string|max:255',
            'dept' => 'required|string|max:255',
            'status' => 'required|string|max:45',
            'history' => 'required|string|max:255',
            'notes' => 'required|string|max:1000',
            'checkdate' => 'required|string|max:45',
            'checkedby' => 'required|string|max:45',
        ];
    }
}
