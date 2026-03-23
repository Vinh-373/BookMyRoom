<?php
class Controller{
    public function view($name, $data = []) {
        extract($data);
        
        require_once './app/views/' . $name . '.php';
    }
}
