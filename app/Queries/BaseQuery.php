<?php

namespace App\Queries;

abstract class BaseQuery
{
    public abstract function query();

    public function get(){
        return $this->query()->get();
    }

    public function count(){
        return $this->query()->count();
    }

    public function avg(){
        return $this->query()->count();
    }

    public function sum(){
        return $this->query()->count();
    }

    public function update(array $data){
        return $this->query()->update($data);
    }
}
