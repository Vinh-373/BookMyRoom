<?php
require_once __DIR__ . '/../models/informationModel.php';
use Models\InformationModel;

class InformationService
{
    private $model;
    public function __construct()
    {
        $this->model = new InformationModel();
    }

    public function getInformationUser($userId)
    {
        return $this->model->getInformationUser($userId);
    }

    public function getCity()
    {
        return $this->model->getCity();
    }

    public function getWardByCityId($cityId)
    {
        return $this->model->getWardByCityId($cityId);
    }

    public function getAllPhoneNumber()
    {
        return $this->model->getAllPhoneNumber();
    }

    public function getAllEmail()
    {
        return $this->model->getAllEmail();
    }

    public function getEmailByUserId($userId)
    {
        return $this->model->getEmailByUserId($userId);
    }

    public function isEmailExist($email)
    {
        return $this->model->isEmailExist($email);
    }

    public function getPhoneByUserId($userId)
    {
        return $this->model->getPhoneByUserId($userId);
    }

    public function isPhoneExist($phone)
    {
        return $this->model->isPhoneExist($phone);
    }

    public function updateFullName($fullName, $userId)
    {
        return $this->model->updateFullName($fullName, $userId);
    }

    public function updateGender($gender, $userId)
    {
        return $this->model->updateGender($gender, $userId);
    }

    public function updateBirthDate($birthDate, $userId)
    {
        return $this->model->updateBirthDate($birthDate, $userId);
    }

    public function updateEmail($email, $userId)
    {
        return $this->model->updateEmail($email, $userId);
    }

    public function updatePhoneNumber($phone, $userId)
    {
        return $this->model->updatePhoneNumber($phone, $userId);
    }

    public function updateAddress($address, $wardId, $cityId,$userId)
    {
        return $this->model->updateAddress( $address, $wardId, $cityId,$userId);
    }
    public function updateAvt($image,$userId)
    {
        return $this->model->updateAvt($image,$$userId);
    }
}
?>