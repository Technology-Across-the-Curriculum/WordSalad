<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Home extends Controller
{
    function __construct(){
        /** This is an example for loading models and entities for the controller to use.
          * The name in the array should be the same as the file name.
        **/

        // Model
        // $models = array('modelexample');
        // $this->_loadModel($models);

        // Entity
        // $entities = array('entityexample');
        // $this->_loadEntity($entities);
    }

    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function index()
    {
        // load view
        require APP . 'view/home/index.php';

    }

}
