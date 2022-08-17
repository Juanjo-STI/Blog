<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
       //Valida, para ignorar el slug, porque sino me va a decir que ya existe.
       $slug = request()->isMethod('put') ? 'required|unique:categories,slug,'.$this->id : 'required|unique:categories';
       $image = request()->isMethod('put') ? 'nullable|mimes:jpeg,jpg,png,gif,svg|max:8000' : 'required|image';

       return [
           'name' => 'required|max:60',
           'slug' => $slug,
           'image' => $image,
           'is_featured' => 'required|boolean',
           'status' => 'required|boolean',
       ];
    }
}
