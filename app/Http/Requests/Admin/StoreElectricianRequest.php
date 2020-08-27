<?php
namespace App\Http\Requests\Admin;

use App\Dealers;
use Illuminate\Foundation\Http\FormRequest;

class StoreElectricianRequest extends FormRequest
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
protected $format = 'json';
    public function prepareForValidation() {
	    $dealer = Dealers::where('name','=',$this->input('dealer'))->get()->first();
	    if($dealer){
		    $this->merge(['dealer_id'=>$dealer->id]);
	    }
    }

	/**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'nic' => 'required|unique:electricians,nic',
            'telephone' => 'required|unique:electricians,telephone',
            'photo' => 'image|mimes:jpeg,png,jpg|max:1024',
            'city' => 'required',
            'region' => 'required',
        ];
    }
}
