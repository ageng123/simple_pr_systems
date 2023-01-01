<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use DB;
use App\Http\Requests\MasterData\UserRequest;
use Illuminate\Support\Str;
use Hash;
use App\Http\Requests\LoginRequest;
class User extends Controller
{
    //
    protected $repository;
    protected $dataResponses = ["data" => "", "messages" => "", "success" => false, "errors" => false];
    protected $input;
    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }
    public function responseJSON($code = null){
        return response()->json($this->dataResponses, $code);
    }
    public function index()
    {
        try{
            $this->dataResponses["data"] = $this->repository->GetAll();
            $this->dataResponses["success"] = true;
            return $this->responseJSON(200);
        }catch(\Exception $e){
            $this->dataResponses = ["errors" => $e->getMessage(), "success" => false] ;
            return $this->responseJSON(500);
        }
    }
    public function store(UserRequest $request)
    {
        try{

            $transaction = DB::transaction(function()use($request){

                $arrRequest = $request->toArray();
                if(empty($arrRequest["phone_number"])) $arrRequest["phone_number"] = 0;
                if(empty($arrRequest["uuid"])) $arrRequest["uuid"] = Str::orderedUuid();
                $this->dataResponses["data"] = $arrRequest;
                $arrRequest["password"] = Hash::make($arrRequest["password"]);
                $save = $this->repository->save($arrRequest);
                if(!$save) {
                    DB::rollback();
                    throw new \Exception ("Error Save data to DB");
                    return false;
                }
                DB::commit();
                $this->dataResponses["success"] = true;
                $this->dataResponses["messages"] = "Successfully Insert User Data";
                return true;
            },3);
            if($transaction) return $this->responseJSON(201);
            return $this->responseJSON(500);
        }catch(\Exception $e){
            $this->dataResponses = ["errors" => $e->getMessage(), "success" => false] ;
            return $this->responseJSON(500);
        }
    }
    public function find(string $uuid)
    {
        try{
            $user = $this->repository->Find(["uuid" => $uuid]);
            if(!$user) throw new \Exception("Record Not Found");
            $this->dataResponses["data"] = $user;
            $this->dataResponses["messages"] = "Data Found!";
            $this->dataResponses["success"] = true;
            return $this->responseJSON(200);
        }catch(\Exception $e){
            $this->dataResponses = ["errors" => $e->getMessage(), "success" => false] ;
            return $this->responseJSON(500);
        }
    }
    public function update(string $uuid, UserRequest $request)
    {
        try{
            $user = $this->repository->Find(["uuid" => $uuid]);
            if(!$user) throw new \Exception("Record Not Found");
            $trans = DB::transaction(function()use($user, $request){
                $arrRequest = $request->toArray();
                if(!empty($request['password'])){
                    $arrRequest['password'] = Hash::make($arrRequest['password']);
                }
                $save = $this->repository->Update($arrRequest, ["uuid" => $user->uuid]);
                if(!$save){
                    DB::rollback();
                    $this->dataResponses["data"] = $request->toArray();
                    return false;
                }
                $this->dataResponses["data"] = $request->toArray();
                $this->dataResponses["success"] = true;
                $this->dataResponses["messages"] = "Update Data on User Success!";
                DB::commit();
                return true;
            });
            if($trans) return $this->responseJSON(200);
            return $this->responseJSON(500);
        }catch(\Exception $e){
            $this->dataResponses = ["errors" => $e->getMessage(), "success" => false] ;
            return $this->responseJSON(500);
        }
    }
    public function delete(string $uuid)
    {
        try{
            $user = $this->repository->Find(["uuid" => $uuid]);
            $trans = DB::transaction(function()use($user){
                $name = $user->name;
                $delete = $user->delete();
                if(!$delete){
                    DB::rollback();
                    throw new \Exception("User Delete is Failed");
                    return false;
                }
                $this->dataResponses["success"] = true;
                $this->dataResponses["messages"] = "Succesfully Delete User : ".$name; 
                DB::commit();

                return true;
            }, 3);
            if(!$trans) return $this->responseJSON(500);
            return $this->responseJSON(200);
        }catch(\Exception $e){
            $this->dataResponses = ["error" => $e->getMessage(), "success" => false] ;
            return $this->responseJSON(500);
        }
    }
    public function register(UserRequest $request){
        try{
            $user = [];
            $transaction = DB::transaction(function()use($request, &$user){
                $arrRequest = $request->toArray();
                if(empty($arrRequest["phone_number"])) $arrRequest["phone_number"] = 0;
                if(empty($arrRequest["uuid"])) $arrRequest["uuid"] = Str::orderedUuid();
                $this->dataResponses["data"] = $arrRequest;
                $arrRequest["password"] = Hash::make($arrRequest["password"]);
                $save = $this->repository->save($arrRequest);
                if(!$save) {
                    DB::rollback();
                    throw new \Exception ("Error Save data to DB");
                    return false;
                }
                DB::commit();
                $user = $this->repository->Find(["uuid" => $arrRequest["uuid"]]);
                $this->dataResponses["success"] = true;
                $this->dataResponses["messages"] = "Successfully Insert User Data";
                return true;
            },3);
            $signUser = $this->repository->GenerateToken($user);
            $this->dataResponses["data"] = ["token" => $signUser];
            if($transaction) return $this->responseJSON(201);
            return $this->responseJSON(500);
        }catch(\Exception $e){
            $this->dataResponses = ["errors" => $e->getMessage(), "success" => false] ;
            return $this->responseJSON(500);
        }
    }
    public function authenticate(LoginRequest $request){
        try {
            $user = $this->repository->Model()->where("nip", $request->username)->orWhere("email", $request->username)->first();
            $this->dataResponses["data"] = $request->toArray();
            if(!$user) throw new \Exception("Username is wrong");
            if(!Hash::check($request->password, $user->password)) throw new \Exception("Password is wrong");
            $auth = $this->repository->GenerateToken($user);
            if($auth){
                $this->dataResponses["data"] = ["token" => $auth];
                $this->dataResponses["success"] = true;
                return $this->responseJSON(200);
            }
            throw new \Exception("Error generate Token");
        }catch(\Exception $e){
            $this->dataResponses["errors"] = $e->getMessage();
            $this->dataResponses["success"] = false;
            return $this->responseJSON(500);
        }
       

    }
}
