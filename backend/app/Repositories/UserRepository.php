<?php 
namespace App\Repositories;
use App\Models\User;
use App\Interfaces\MasterDataInterface;
use JWTAuth;
class UserRepository implements MasterDataInterface {
    protected $model;
    public function __construct(User $model){
        $this->model = $model;
    }
    public function GetAll()
    {
        return $this->model->all();
    }
    public function Find(Array|Object $conditions)
    {
        return $this->model->where($conditions)->first();
    }
    public function Save(Array|Object $data){
        return $this->model()->create($data);
    }
    public function Update(Array|Object $data, Array|Object $conditions){
        $record = $this->model->where($conditions)->first();
        return $record->update($data);
    }
    public function Delete(Array|Object $conditions){
        $record = $this->model->where($conditions)->first();
        return $record->delete();
    }
    public function Model(){
        return $this->model;
    }
    public function GenerateToken(User $user){
        $token = JWTAuth::fromUser($user);
        if($token){
            return $token;
        }
    }
    
}