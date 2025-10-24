<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bookId = $this->route('book') ? $this->route('book')->id : null;
        
        return [
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'image_url' => 'nullable|url|max:500',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $bookId,
            'category' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'language' => 'required|string|max:30',
            'published_year' => 'required|integer|min:1900|max:2025',
            'is_available' => 'boolean',
            'price' => 'nullable|numeric|min:0|max:99999.99'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The book title is required',
            'title.max' => 'The title must not exceed 255 characters',
            'author.required' => 'The author name is required',
            'author.max' => 'The author name must not exceed 100 characters',
            'isbn.unique' => 'This ISBN already exists in the database',
            'category_id.exists' => 'The selected category does not exist',
            'language.required' => 'The language is required',
            'published_year.required' => 'The publication year is required',
            'published_year.integer' => 'The publication year must be a number',
            'published_year.min' => 'The publication year must be greater than 1900',
            'published_year.max' => 'The publication year cannot exceed 2025',
            'image.image' => 'The file must be an image',
            'image.mimes' => 'The image must be in JPG, JPEG or PNG format',
            'image.max' => 'The image must not exceed 2 MB',
            'image_url.url' => 'The image URL must be a valid URL',
            'image_url.max' => 'The image URL must not exceed 500 characters',
            'price.numeric' => 'The price must be a valid number',
            'price.min' => 'The price must be at least 0',
            'price.max' => 'The price cannot exceed 99999.99'
        ];
    }
}
