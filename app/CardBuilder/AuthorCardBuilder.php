<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\AuthorService;

class AuthorCardBuilder extends BaseCardBuilder {
    function __construct() {
        parent::__construct();
    }
    public function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'create_at' => $item->create_at,
                'short_desc' => $item->short_desc
            ];
        }
        return $posts;
    }
    public function summary($arr_posts) {
        if(empty($arr_posts)) return [];
        $posts = [];
        $authorService = new AuthorService();
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'create_at' => $item->create_at,
                'position' => $item->position,
                'social' => json_decode($item->social),
                'role' => $item->role,
                'total_posts' => $authorService->countPostsByAuthor($item->id)
            ];
        }
        return $posts;
    }
}