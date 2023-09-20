<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\Models\Posts;
use App\Models\Relative;

class CasinoCardBuilder extends BaseCardBuilder {
    public function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        $vendorPublicPosts = [];
        $vendorModel = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
        $vendorCardBuilder = new VendorCardBuilder();
        foreach ($arr_posts as $item) {
            $vendor_posts = Relative::getRelativeByPostId($this->tables['CASINO_VENDOR_RELATIVE'], $item->id);
            if(!empty($vendor_posts)) {
                $vendorPublicPosts = $vendorModel->getPublicPostsByArrId($vendor_posts);
            }
            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'rating' => $item->rating,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'title' => $item->title,
            ];
        }
        return $posts;
    }
    public function sliderCard($arr_posts) {
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                    'title' => $item->title,
                    'permalink' => '/'.$item->slug.'/'.$item->permalink,
                    'thumbnail' => $item->thumbnail
            ];
        }
        return $posts;
    }
}