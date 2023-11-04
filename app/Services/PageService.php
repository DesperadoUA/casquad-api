<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Pages;
use App\Models\Category;
use App\Serialize\PageSerialize;
use App\Services\BaseService;
use App\Models\Cash;
use App\CardBuilder\CasinoCardBuilder;
use App\CardBuilder\BonusCardBuilder;
use App\CardBuilder\GameCardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\CardBuilder\NewsCardBuilder;
class PageService extends BaseService {
    protected $response;
    protected $config;
    const MAIN_PAGE_LIMIT_CASINO = 10;
    const MAIN_PAGE_LIMIT_BONUSES = 1000;
    const MAIN_PAGE_LIMIT_NEWS = 1000;
    const CATEGORY_LIMIT_CASINO = 1000;
    const CATEGORY_LIMIT_GAME = 1000;
    const SLIDER_LIMIT_GAME = 10;
    const SLIDER_LIMIT_BONUS = 10;
    const SLIDER_LIMIT_NEWS = 10;
    const SLIDER_LIMIT_CASINO = 10;
    const ASIDE_LIMIT_BONUS = 5;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->config = config('constants.PAGES');
        $this->serialize = new PageSerialize();
    }
    public function main(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl('/');
        if(!$data->isEmpty()) {
            $casinoCardBuilder = new CasinoCardBuilder();
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $casino = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_CASINO,
                'order_key' => 'rating'
            ];
            $this->response['body']['casino'] = $casinoCardBuilder->main($casino->getPublicPosts($settings));
            $casinoSliderSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::SLIDER_LIMIT_CASINO,
            ];
            $this->response['body']['casino_slider'] = $casinoCardBuilder->sliderCard($casino->getPublicPosts($casinoSliderSettings));
            $gameCardBuilder = new GameCardBuilder();
            $game = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
            $gameSettings = [
                'lang' => $data[0]->lang,
                'limit' => self::SLIDER_LIMIT_GAME
            ];
            $this->response['body']['games'] = $gameCardBuilder->main($game->getPublicPosts($gameSettings));

            $bonusCardBuilder = new BonusCardBuilder();
            $bonus = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
            $bonusSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::SLIDER_LIMIT_BONUS,
            ];
            $this->response['body']['bonuses'] = $bonusCardBuilder->slider($bonus->getPublicPosts($bonusSettings));

            $topBonusSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::ASIDE_LIMIT_BONUS,
                'order_key' => 'rating'
            ];
            $this->response['body']['top_bonuses'] = $bonusCardBuilder->main($bonus->getPublicPosts($topBonusSettings));

            $newsCardBuilder = new NewsCardBuilder();
            $news = new Posts(['table' => $this->tables['NEWS'], 'table_meta' => $this->tables['NEWS_META']]);
            $newsSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::SLIDER_LIMIT_NEWS,
            ];
            $this->response['body']['news'] = $newsCardBuilder->main($news->getPublicPosts($newsSettings));

            $baseCardBuilder = new BaseCardBuilder();
            $casinoCategory = new Category([
                'table' => $this->tables['CASINO'], 
                'table_meta' => $this->tables['CASINO_META'], 
                'table_category' => $this->tables['CASINO_CATEGORY'],
                'table_relative' => $this->tables['CASINO_CATEGORY_RELATIVE']
            ]);
            $casinoCategorySettings = [
                'lang'      => $data[0]->lang,
            ];
            $this->response['body']['casino_category'] = $baseCardBuilder->defaultCard($casinoCategory->getPublicPosts($casinoCategorySettings));

            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function bonuses(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['BONUSES']);
        if(!$data->isEmpty()) {
            $bonusCardBuilder = new BonusCardBuilder();
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $bonusModel = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_BONUSES,
                'order_key' => 'rating'
            ];
            $this->response['body']['bonus'] = $bonusCardBuilder->main($bonusModel->getPublicPosts($settings));

            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function vendors(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['VENDOR']);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function news(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['NEWS']);
        if(!$data->isEmpty()) {
            $newsCardBuilder = new NewsCardBuilder();
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $bonusModel = new Posts(['table' => $this->tables['NEWS'], 'table_meta' => $this->tables['NEWS_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_NEWS,
            ];
            $this->response['body']['news'] = $newsCardBuilder->main($bonusModel->getPublicPosts($settings));

            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function games(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['GAMES']);
        if(!$data->isEmpty()) {
            $gameCardBuilder = new GameCardBuilder();
            $game = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::CATEGORY_LIMIT_GAME
            ];
            $this->response['body']['games'] = $gameCardBuilder->main($game->getPublicPosts($settings));
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function search($searchWord, $lang) {
        if(empty($searchWord)) {
            $this->response['body']['posts'] = [];
            $this->response['confirm'] = 'ok';
        } else {
            $arrDb = [
                'CASINO', 'BONUS', 'POKER', 'GAME', 'BETTING', 'NEWS', 'VENDOR', 'SHARES'
            ];
            $posts = [];
            foreach($arrDb as $db) {
                $posts = array_merge($posts, BaseCardBuilder::defaultCard(Posts::searchPublicByTitle($lang, $this->tables[$db], $searchWord)));
            }
            $this->response['body']['posts'] = $posts;
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function adminIndex($settings) {
        $posts = new Pages();
        $arrPosts = $posts->getPosts($settings);
        if(!$arrPosts->isEmpty()) {
            $data = [];
            foreach ($arrPosts as $item) $data[] = $this->serialize->adminSerialize($item);
            $this->response['body'] = $data;
            $this->response['confirm'] = 'ok';
            $this->response['total'] = $posts->getTotalCountByLang($settings['lang']);
            $this->response['lang'] = config('constants.LANG')[$settings['lang']];
        }
        return $this->response;
    }
    public function adminShow($id) {
        $post = new Pages();
        $data = $post->getPostById($id);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->adminSerialize($data[0]);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $post = new Pages();
        $data_save = $this->serialize->validateUpdate($data);
        $post->updateById($data['id'], $data_save);
        $this->response['confirm'] = 'ok';
        $this->response['test'] = $data_save;
        Cash::deleteAll();
        return $this->response;
    }
}