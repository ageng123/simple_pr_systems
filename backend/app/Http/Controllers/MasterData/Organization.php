<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OrganizationRepository;
use DB;
use App\Http\Requests\MasterData\OrganizationRequest;
class Organization extends Controller
{
    //
    protected $repository;
    public function __construct(OrganizationRepository $repository){
        $this->repository = $repository;
    }
    protected $dataResponses = [
        "success" => false,
        "errors" => [],
        "messages" => "",
    ];
    public function responseJson($code)
    {
        if(is_string($code) || $code == 0){
            $code = 500;
        }
        return response()->json($this->dataResponses, $code);
    }
    public function index(){
        $data = $this->repository->GetAll();
        $this->dataResponses["success"] = true;
        $this->dataResponses["data"] = $data;
        return $this->responseJson(200);
    }
    public function show($id){
       try{
            $data = $this->repository->Find(["organization_id" => $id]);
            if(!$data) throw new \Exception("Could not find data", 404);
            $this->dataResponses["success"] = true;
            $this->dataResponses["data"] = $data;
            return $this->responseJson(200);
       }catch(\Exception $e){
            $this->dataResponses["success"] = false;
            $this->dataResponses["errors"] = $e->getMessage();
            return $this->responseJson($e->getCode());
       }
    }
    public function store(OrganizationRequest $request){
        try{
            $transaction = DB::transaction(function()use($request){
                $store = $this->repository->Save($request->toArray());
                if(!$store){
                    DB::rollback();
                    throw new \Exception("Error Saving Organization Data", 500);
                }
                $this->dataResponses["success"] = true;
                $this->dataResponses["messages"] = "Saving Organization Data Successfully";
                return true;
            }, 3);
            if($transaction) return $this->responseJSON(201);
            throw new \Exception("Errors saving Organization Data", 500);
           }catch(\Exception $e){
            $this->dataResponses["success"] = false;
            $this->dataResponses["errors"] = $e->getMessage();
            return $this->responseJson($e->getCode());
           }
    }
    public function update($id, OrganizationRequest $request){
        try{
            $transaction = DB::transaction(function()use($id, $request){
               
                $update = $this->repository->Update($request->toArray(),["organization_id" => $id]);
                if(!$update) {
                    DB::rollback();
                    throw new \Exception("Could not update organization data", 400);
                };
                $this->dataResponses['success'] = true;
                $this->dataResponses["messages"] = "Success Update Data";
                return true;
            },3);
            if($transaction) return $this->responseJson(200);
            throw new \Exception("Error updating organization data", 400);
        }catch(\Exception $e){
            $this->dataResponses["errors"] = $e->getMessage();
            $this->dataResponses["success"] = false;
            return $this->responseJson($e->getCode());
        }
    }
    public function delete($id){
        try{
            $transaction = DB::transaction(function()use($id){

                $update = $this->repository->Delete(["organization_id" => $id]);
                if(!$update) {
                    DB::rollback();
                    throw new \Exception("Could not Delete organization data", 400);
                };
                $this->dataResponses['success'] = true;
                $this->dataResponses["messages"] = "Success Delete Data";
                return true;
            },3);
            if($transaction) return $this->responseJson(200);
            throw new \Exception("Error deleting organization data", 400);
        }catch(\Exception $e){
            $this->dataResponses["errors"] = $e->getMessage();
            $this->dataResponses["success"] = false;
            return $this->responseJson($e->getCode());
        }
    }
    public function tree($id){
        $data = $this->repository->GetParentTree((int)$id);
        return response()->json($data, 200);
    }
}
