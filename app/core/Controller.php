<?php
class Controller{
    public function view($name, $data = []) {
        extract($data);
        if (file_exists("./app/views/" . $name . ".php")) {
            require_once "./app/views/" . $name . ".php";
        }
    }
}
