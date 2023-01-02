<?php
namespace App\Repositories;

use App\Interfaces\MasterDataInterface;
use App\Models\Organization;
use Illuminate\Support\Collection;
Class OrganizationRepository implements MasterDataInterface
{
    protected $model;
    public function __construct(Organization $model){
        $this->model = $model;
    }
    public function Model(): Organization
    {
        return $this->model;
    }
    public function GetAll()
    {
        return $this->model->all();
    }
    public function Find(Array|Object $conditions){
        return $this->model->where($conditions)->first();
    }
    public function Save(Array|Object $data){
        return $this->model->create($data);
    }
    public function Update(Array|Object $data, Array|Object $conditions){
        $model = $this->model->where($conditions)->first();
        if(!$model) return false;
        return $model->update($data);
    }
    public function Delete(Array|Object $conditions){
        $model = $this->model->where($conditions)->first();
        if(!$model) return false;
        return $model->delete();
    }
    public function GetParentTree(int $id){
       $find = $this->model->find($id)->load('parent');
       $output = [];
       $output = $find->toArray();
       $output["parent"] = [];
       while($find->parent != null){
        $find = $this->model->find($find->parent->organization_id)->load('parent');
        $output["parent"][$find->organization_id] = $find->toArray();
       }
       $collection = collect($output);
       return $collection;
    }
}