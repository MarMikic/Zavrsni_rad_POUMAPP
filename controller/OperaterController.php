<?php

class OperaterController extends AdminController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'operater'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'index');
    }

}