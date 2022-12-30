<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use DB;
use App\Http\Requests\MasterData\UserRequest;
use Illuminate\Support\Str;

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
                $save = $this->repository->Update($request->toArray(), ["uuid" => $user->uuid]);
                if(!$save){
                    $this->dataResponses["data"] = $request->toArray();
                    return false;
                }
                $this->dataResponses["data"] = $request->toArray();
                $this->dataResponses["success"] = true;
                $this->dataResponses["messages"] = "Update Data on User Success!";
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
            $trans = DB::transactions(function()use($user){
                $name = $user->name;
                $delete = $user->delete();
                if(!$delete){
                    throw new \Exception("User Delete is Failed");
                    return false;
                }
                $this->dataResponses["success"] = true;
                $this->dataResponses["messages"] = "Succesfully Delete User : ".$name; 
                return true;
            }, 3);
            if(!$trans) return $this->responseJSON(500);
            return $this->responseJSON(200);
        }catch(\Exception $e){
            $this->dataResponses = ["error" => $e->getMessage(), "success" => false] ;
            return $this->responseJSON(500);
        }
    }
}