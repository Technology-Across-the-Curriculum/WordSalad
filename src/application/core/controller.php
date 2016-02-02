<?php

class Controller
{
    /**
     * @var null Model
     */
    public $model = null;

    /**
     * @var null Entity
     */
    public $entity = null;


    function _loadModel($model)
    {
        foreach ($model as $m) {
            if (file_exists(APP . 'class/model/' . $m . '.php') && empty($this->model[$m])) {
                require APP . 'class/model/' . $m . '.php';
                $mod = ucfirst($m);
                $this->model[$m] = new $mod();
            }
        }
    }

    function _loadEntity($entity)
    {
        foreach ($entity as $e) {
            if (file_exists(APP . 'class/entity/' . $e . '.php') && empty($this->entity[$e])) {
                require APP . 'class/entity/' . $e . '.php';
                $ent = ucfirst($e);
                $this->entity[$e] = new $ent();
            }
        }
    }

}
