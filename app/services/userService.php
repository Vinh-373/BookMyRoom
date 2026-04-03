<?php
namespace Services;
use Models\UserModel;

class UserService 
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getAllUsers()
    {
        return $this->userModel->select_array();
    }
    public function getUserByEmail($email)
    {
        return $this->userModel->findByEmail($email);
    }
    public function createUser($data)
    {
        return $this->userModel->insert($data);
    }
    public function updateUser($id, $data)
    {
        return $this->userModel->update($id, $data);
    }
    public function updatePasswordByEmail($email, $password){
        return $this->userModel->update(['password' => $password],['email' => $email]);
    }

}