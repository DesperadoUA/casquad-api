<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\Models\Posts;
use App\Models\Relative;

class ArticleCardBuilder extends BaseCardBuilder {
    function __construct() {
        parent::__construct();
    }
    public function author($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'update_at' => $item->update_at,
                'short_desc' => $item->short_desc
            ];
        }
        return $posts;
    }
}