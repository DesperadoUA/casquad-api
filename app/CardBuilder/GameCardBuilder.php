<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\Models\Posts;
use App\Models\Relative;

class GameCardBuilder extends BaseCardBuilder {
    function __construct() {
        parent::__construct();
    }
    public function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        $vendorModel = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
        foreach ($arr_posts as $item) {
            $vendorId = Relative::getRelativeByPostId($this->tables['GAME_VENDOR_RELATIVE'], $item->id);
            $vendor = [
                'title' => 'Default'
            ];
            if(count($vendorId)) {
                    $vendorPublicPosts = $vendorModel->getPublicPostsByArrId($vendorId);
                    if(count($vendorPublicPosts)) {
                        $vendor['title'] = $vendorPublicPosts[0]->title;
                    }
            }
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'vendor' => $vendor,
                'game_week' => $item->game_week,
                'slider_img' => $item->slider_img
            ];
        }
        return $posts;
    }
    public function slider($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $thumbnail = empty($item->slider_img) ? $item->thumbnail : $item->slider_img;
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $thumbnail
            ];
        }
        return $posts;
    }
}
