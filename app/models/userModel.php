<?php

namespace Models;

require_once './app/models/MyModels.php';

use Models\MyModels;

class UserModel extends MyModels
{
    protected $table = 'users';

    /**
     * Lấy user theo ID
     */
    public function findById($id)
    {
        $result = $this->select_array('*', ['id' => $id]);
        return $result[0] ?? null;
    }

    /**
     * Lấy user theo Google ID
     */
    public function findByGoogleId($googleId)
    {
        $result = $this->select_array('*', ['google_id' => $googleId]);
        return $result[0] ?? null;
    }

    /**
     * Lấy user theo Email
     */
    public function findByEmail($email)
    {
        $result = $this->select_array('*', ['email' => $email]);
        return $result[0] ?? null;
    }
}