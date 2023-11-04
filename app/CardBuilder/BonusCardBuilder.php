<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\Models\Posts;
use App\Models\Relative;

class BonusCardBuilder extends BaseCardBuilder {
    function __construct() {
        parent::__construct();
    }
    public function main($arr_posts){
        if(empty($arr_posts)) return [];
        $casinoModel = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $casinoCardBuilder = new CasinoCardBuilder();

        foreach ($arr_posts as $item) {
            $casinoIds = Relative::getRelativeByPostId($this->tables['BONUS_CASINO_RELATIVE'], $item->id);
            $casino = $casinoModel->getPublicPostsByArrId($casinoIds);
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'short_desc' => $item->short_desc,
                'casino' => $casino->isEmpty() ? [] : $casinoCardBuilder->main($casino)[0],
                'rating' => $item->rating,
                'bonus' => $item->bonus,
                'min_deposit' => $item->min_deposit,
                'wagering' => $item->wagering
            ];
        }
        return $posts;
    }
    public function slider($arr_posts) {
        if(empty($arr_posts)) return [];
        $casinoModel = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $casinoCardBuilder = new CasinoCardBuilder();

        foreach ($arr_posts as $item) {
            $casinoIds = Relative::getRelativeByPostId($this->tables['BONUS_CASINO_RELATIVE'], $item->id);
            $casino = $casinoModel->getPublicPostsByArrId($casinoIds);
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'short_desc' => $item->short_desc,
                'bonus' => $item->bonus,
                'casino' => $casino->isEmpty() ? [] : $casinoCardBuilder->main($casino)[0],
                'rating' => $item->rating
            ];
        }
        return $posts;
    }
}