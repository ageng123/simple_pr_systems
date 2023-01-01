<?php 
namespace App\Repositories;
use App\Models\Role;
use App\Interfaces\MasterDataInterface;
class RoleRepository implements MasterDataInterface
{
    protected $model;
    public function __construct(Role $model){
        $this->model = $model;
    }
    public function Model(){
        return $this->model;
    }
    public function GetAll(){
        return $this->model->all();
    }
    public function Find(Array|Object $conditions){
        return $this->model->where($conditions)->first();
    }
    public function Save(Array|Object $data){
        return $this->model->create($data);
    }
    public function Update(Array|Object $data, Array|Object $conditions){
        $role = $this->model->where($conditions)->first();
        return $role->update($data);
    }
    public function Delete(Array|Object $conditions){
        $role = $this->model->where($conditions)->first();
        return $role->delete();
    }
}