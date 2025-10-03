<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WishlistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'isbn' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'priority' => 'required|in:low,medium,high',
            'max_price' => 'nullable|numeric|min:0|max:99999.99',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Book title is required',
            'title.max' => 'Title must not exceed 255 characters',
            'author.required' => 'Author name is required',
            'author.max' => 'Author name must not exceed 100 characters',
            'isbn.max' => 'ISBN must not exceed 20 characters',
            'description.max' => 'Description must not exceed 1000 characters',
            'image.image' => 'The file must be an image',
            'image.mimes' => 'Image must be a JPEG, PNG, JPG, or GIF file',
            'image.max' => 'Image must not exceed 10MB',
            'priority.required' => 'Priority is required',
            'priority.in' => 'Priority must be low, medium, or high',
            'max_price.numeric' => 'Max price must be a number',
            'max_price.min' => 'Max price must be at least 0',
            'max_price.max' => 'Max price must not exceed 99999.99',
        ];
    }
} 