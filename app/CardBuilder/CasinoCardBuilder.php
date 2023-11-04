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
                $vendorPublicPosts = $vendorCardBuilder->main($vendorModel->getPublicPostsByArrId($vendor_posts));
            }
            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'rating' => $item->rating,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'title' => $item->title,
                'ref' =>  json_decode($item->ref, true),
                'color' => $item->color,
                'label' => $item->label,
                'advantages' => json_decode($item->advantages, true),
                'wager' => $item->wager,
                'min_dep' => $item->min_dep,
                'bonus_value' => $item->bonus_value,
                'vendors' => $vendorPublicPosts
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
                    'icon' => $item->icon,
                    'color' => $item->color,
                    'rating' => $item->rating
            ];
        }
        return $posts;
    }
}