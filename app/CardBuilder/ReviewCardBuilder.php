<?php
namespace App\CardBuilder;

class ReviewCardBuilder {
    static function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'userName' => $item->name,
                'src' => $item->thumbnail,
                'desc' => $item->content,
                'verified' => $item->verified,
                'rating' => $item->rating,
                'date' => $item->update_at
            ];
        }
        return $posts;
    }
}