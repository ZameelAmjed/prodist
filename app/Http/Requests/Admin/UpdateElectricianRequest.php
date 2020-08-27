<?php
namespace App\Http\Requests\Admin;

use App\Dealers;
use Illuminate\Foundation\Http\FormRequest;

class UpdateElectricianRequest extends FormRequest
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
        return [
            'name' => 'required_without:status',
            'photo' => 'image|mimes:jpeg,png,jpg|max:1024',
        ];
    }

	public function prepareForValidation() {
		$dealer = Dealers::where('name','=',$this->input('dealer'))->get()->first();
		if($dealer){
			$this->merge(['dealer_id'=>$dealer->id]);
		}

	}
}
