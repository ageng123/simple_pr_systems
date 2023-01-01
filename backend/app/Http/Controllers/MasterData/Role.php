<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\RoleRepository;
use App\Http\Requests\MasterData\RoleRequest;
use DB;
class Role extends Controller
{
    protected $dataResponses = ["data" => [], "errors" => [], "success" => false, "messages" => ""];
    protected $repository;
    public function __construct(RoleRepository $repo){
        $this->repository = $repo;
    }
    public function responseJSON($code){
        return response()->json($this->dataResponses,$code);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->dataResponses["data"] = $this->repository->GetAll();
        $this->dataResponses["success"] = true;
        return $this->responseJSON(200);
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        //
        try{
            $transaction = DB::transaction(function()use($request){
                $save = $this->repository->Save($request->toArray());
                if(!$save) {
                    DB::rollback();
                    throw new \Exception("Could not save role to the resource");
                }
                $this->dataResponses["messages"] = "Successfully Add Role Data";
                $this->dataResponses["success"] = true;
                DB::commit();
                return true;
            },3);
            if($transaction) return $this->responseJSON(200);
        }catch(\Exception $e){
            $this->dataResponses["errors"] = $e->getMessage();
            $this->dataResponses["success"] = false;
            return $this->responseJSON(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try{
            $role = $this->repository->Find(["role_id" => $id]);
            if(!$role) throw new \Exception("Could not find role");
            $this->dataResponses["data"] = $role;
            $this->dataResponses["success"] = true;
            return $this->responseJSON(200);
        }catch(\Exception $e){
            $this->dataResponses["success"] = false;
            $this->dataResponses["errors"] = $e->getMessage();
            return $this->responseJSON(500);
        }
        
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        //
        try{
            $transaction = DB::transaction(function()use($request,$id){
                $update = $this->repository->update($request->toArray(),["role_id" => $id]);
                if(!$update){
                    DB::rollback();
                    throw new \Exception('Updating Role Data Failed !');
                }
                DB::commit();
                $this->dataResponses["messages"] = "Update Role Data Success!";
                $this->dataResponses["success"] = true;
                return true;
            }, 3);
            if($transaction) return $this->responseJSON(200);
            throw new \Exception("Update Rola Data Failed !");
        }catch(\Exception $e){
            $this->dataResponses["errors"] = $e->getMessage();
            $this->dataResponses["success"] = false;
            return $this->responseJSON(500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $transaction = DB::transaction(function()use($id){
                $delete = $this->repository->delete(["role_id" => $id]);
                if(!$delete){
                    DB::rollback();
                    throw new \Exception("Could not delete role data");
                }
                DB::commit();
                $this->dataResponses["messages"] = "Delete Role Data Success";
                $this->dataResponses["success"] = true;
                return true;
            },3);
            if($transaction) return $this->responseJSON(200);
            throw new \Exception("Could not update role data !");
        }catch(\Exception $e){
            $this->dataResponses["errors"] = $e->getMessage();
            $this->dataResponses["success"] = false;
            return $this->responseJSON(500);
        }
    }
}
