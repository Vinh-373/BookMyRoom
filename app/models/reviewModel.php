<?php
namespace Models;
require_once './app/models/MyModels.php';

class ReviewModel extends MyModels
{
        protected $table = "reviews";
        function getReviewsByHotelId($hotelId){
            return $this->join_multi( [
                [
                    'table' => 'users',
                    'on'    => 'users.id = reviews.userId'
                ],
            ],
            'reviews.*,users.fullName,users.avatarUrl',
            ['hotelId' => $hotelId]);
        }
       

}