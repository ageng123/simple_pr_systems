<?php
namespace App\Interfaces;
interface MasterDataInterface {
    public function GetAll();
    public function Find(Array|Object $conditions);
    public function Save(Array|Object $data);
    public function Update(Array|Object $data,Array|Object $conditions);
    public function Delete(Array|Object $conditions);
    public function Model();
}
