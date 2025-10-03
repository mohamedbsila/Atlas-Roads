<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize()
    {
        // Authorization is handled in controller/policy
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'max_participants' => 'nullable|integer|min:1',
            'is_public' => 'sometimes|boolean',
            'thumbnail' => 'nullable|image|max:2048',
        ];
    }
}
