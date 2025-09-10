<?php 
namespace App\Serialize;
use App\Validate;

class ReviewSerialize {
    static function validateInsert($data) {
        $newData = [];
        $newData['title']          = $data['title'];
        $newData['name']           = $data['name'];
        $newData['email']          = $data['email'];
        $newData['content']        = $data['description'];
        $newData['post_type']      = $data['post_type'] ?? 'casino';
        $newData['parent_post_id'] = $data['post_id'] ?? 0;
        $newData['thumbnail']      = $data['thumbnail'] ?? config('constants.DEFAULT_USER_REVIEW_SRC');
        $newData['rating']         = $data['rating'];
        return $newData;
    }
}