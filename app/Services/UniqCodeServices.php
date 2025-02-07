<?php

namespace App\Services;

class UniqCodeServices {
    private $model = null;
    private $parent = null;

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function handle(){

        $parent_id = $this->parent;
        $parent_data = null;
        if(!empty($parent_id)){
            $parent_data = $this->model->find($parent_id);
        }

        $max_data = $this->model->where(function($query) use ($parent_id){
            return $query->where('id_parent', $parent_id);
        })->max('id_increament');

        if(empty($max_data)) $max_data = 1;
        else $max_data++;

        $code = '';
        if(empty($parent_data)){
            $code = $max_data;
        }
        else{
            $code = $parent_data->increament.'.'.$max_data;
        }

        return $code;
    }

}
