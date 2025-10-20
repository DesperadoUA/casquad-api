<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\Models\Posts;
use App\Models\Relative;
use App\CardBuilder\AuthorCardBuilder;

class FunnelCardBuilder extends BaseCardBuilder {
    function __construct() {
        parent::__construct();
    }
    public function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        $authorCardBuilder = new AuthorCardBuilder();
        $authorModel = new Posts(['table' => $this->tables['AUTHOR'], 'table_meta' => $this->tables['AUTHOR_META']]);
        foreach ($arr_posts as $item) {
            $authorPublicPosts = [];
            $author_posts = Relative::getRelativeByPostId($this->tables['FUNNEL_AUTHOR_RELATIVE'], $item->id);
            if(!empty($author_posts)) {
                $authorPublicPosts = $authorCardBuilder->main($authorModel->getPublicPostsByArrId($author_posts));
            }
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'update_at' => $item->update_at,
                'short_desc' => $item->short_desc,
                'authors' => $authorPublicPosts,
                'hash' => $item->permalink
            ];
        }
        return $posts;
    }
}