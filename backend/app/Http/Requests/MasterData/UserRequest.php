<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\UserRepository;
use App\Models\User;
class UserRequest extends FormRequest
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
        $rules = [];
        $repo = new UserRepository(new User);
        $user = $repo->Find(["uuid" => $this->uuid]);
        if(!$user) throw new \Exception("User Not found");

        if(request()->isMethod("put")):
            if(empty(request("password")) && empty(request("c_password"))){
                $rules =  [
                    //
                    'name' => 'required',
                    'email' => 'required|unique:users,email,'.$user->id.'|email',
                    'nip' => 'required|unique:users,nip,'.$user->id,
                    'phone_number' => 'required',
                    'user_status' => ''
                ];
            }else{
                $rules =  [
                    //
                    'name' => 'required',
                    'email' => 'required|unique:users,email|email',
                    'password' => 'required',
                    'c_password' => 'required|same:password',
                    'nip' => 'required|unique:users,nip',
                    'phone_number' => 'required',
                    'user_status' => ''
                ];
            }
           
        else:
            $rules = [
                //
                'name' => 'required',
                'email' => 'required|unique:users,email|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
                'nip' => 'required|unique:users,nip',
                'phone_number' => 'required',
                'user_status' => ''
            ];
        endif;
        return $rules;
    }
}
