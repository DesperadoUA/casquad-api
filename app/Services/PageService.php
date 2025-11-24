<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Pages;
use App\Models\Relative;
use App\Models\Category;
use App\Serialize\PageSerialize;
use App\Services\BaseService;
use App\Models\Cash;
use App\CardBuilder\CasinoCardBuilder;
use App\CardBuilder\BonusCardBuilder;
use App\CardBuilder\GameCardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\CardBuilder\NewsCardBuilder;
use App\CardBuilder\VendorCardBuilder;
use App\CardBuilder\AuthorCardBuilder;
use App\CardBuilder\FunnelCardBuilder;
use Illuminate\Support\Facades\DB;

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
    const FILTER_LIMIT_VENDORS = 25;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->config = config('constants.PAGES');
        $this->serialize = new PageSerialize();
    }
    public function main($geo) {
        $post = new Pages();
        $data = $post->getPublicPostByUrl('/');
        if(!$data->isEmpty()) {
            $casinoCardBuilder = new CasinoCardBuilder();
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $casino = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => 3,
                'order_key' => 'rating',
                'geo' => $geo
            ];
            $this->response['body']['casino'] = $casinoCardBuilder->main($casino->getPublicPostsByGeo($settings));
           
            $gameCardBuilder = new GameCardBuilder();
            $game = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
            $gameSettings = [
                'lang' => $data[0]->lang,
                'limit' => 10
            ];
            $this->response['body']['games'] = $gameCardBuilder->slider($game->getPublicPosts($gameSettings));

            $bonusCardBuilder = new BonusCardBuilder();
            $bonus = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
            $bonusSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::SLIDER_LIMIT_BONUS,
                'geo' => $geo
            ];
            $this->response['body']['bonuses'] = $bonusCardBuilder->slider($bonus->getPublicPostsByGeo($bonusSettings));

            $topBonusSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::ASIDE_LIMIT_BONUS,
                'order_key' => 'rating',
                'geo' => $geo
            ];
            $this->response['body']['top_bonuses'] = $bonusCardBuilder->main($bonus->getPublicPostsByGeo($topBonusSettings));

            $newsCardBuilder = new NewsCardBuilder();
            $news = new Posts(['table' => $this->tables['NEWS'], 'table_meta' => $this->tables['NEWS_META']]);
            $newsSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::SLIDER_LIMIT_NEWS,
            ];
            $this->response['body']['news'] = $newsCardBuilder->main($news->getPublicPosts($newsSettings));

            $vendorCardBuilder = new VendorCardBuilder();
            $vendorModel = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
            $vendorSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => 100,
                'order_key' => 'rating'
            ];
            $this->response['body']['vendors'] = $vendorCardBuilder->default($vendorModel->getPublicPosts($vendorSettings));
            
            $this->response['geo'] = $geo;
            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function bestCasinos($geo) {
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['BEST_CASINOS']);
        if(!$data->isEmpty()) {
            $casinoCardBuilder = new CasinoCardBuilder();
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $casino = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_CASINO,
                'order_key' => 'rating',
                'geo' => $geo
            ];
            $this->response['body']['casino'] = $casinoCardBuilder->main($casino->getPublicPostsByGeo($settings));
            $casinoSliderSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::SLIDER_LIMIT_CASINO,
                'geo' => $geo
            ];
            $this->response['body']['casino_slider'] = $casinoCardBuilder->sliderCard($casino->getPublicPostsByGeo($casinoSliderSettings));
            $gameCardBuilder = new GameCardBuilder();
            $game = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
            $gameSettings = [
                'lang' => $data[0]->lang,
                'limit' => self::SLIDER_LIMIT_GAME
            ];
            $this->response['body']['games'] = $gameCardBuilder->slider($game->getPublicPosts($gameSettings));

            $bonusCardBuilder = new BonusCardBuilder();
            $bonus = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
            $bonusSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::SLIDER_LIMIT_BONUS,
                'geo' => $geo
            ];
            $this->response['body']['bonuses'] = $bonusCardBuilder->slider($bonus->getPublicPostsByGeo($bonusSettings));

            $topBonusSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::ASIDE_LIMIT_BONUS,
                'order_key' => 'rating',
                'geo' => $geo
            ];
            $this->response['body']['top_bonuses'] = $bonusCardBuilder->main($bonus->getPublicPostsByGeo($topBonusSettings));

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
            $this->response['geo'] = $geo;
            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function bonuses($geo) {
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['BONUSES']);
        if(!$data->isEmpty()) {
            $bonusCardBuilder = new BonusCardBuilder();
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $bonusCategory = new Category([
                'table' => $this->tables['BONUS'],
                'table_meta' => $this->tables['BONUS_META'],
                'table_category' => $this->tables['BONUS_CATEGORY'],
                'table_relative' => $this->tables['BONUS_CATEGORY_RELATIVE']
            ]);
            $bonusCategorySettings = [
                'lang' => $data[0]->lang,
            ];
            $this->response['body']['bonus_category'] = [];
            $bonus_category = $bonusCategory->getPublicPosts($bonusCategorySettings);
            foreach($bonus_category as $item) {
                $posts = Relative::getPostIdByRelative($this->tables['BONUS_CATEGORY_RELATIVE'], $item->id);
                if(!empty($posts)) {
                    $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
                    $postsByGeo = $post->getPublicPostsByArrIdAndGeo($posts, $geo);
                    $this->response['body']['bonus_category'][] = [
                        'title' => $item->title,
                        'permalink' => '/'.$item->slug.'/'.$item->permalink,
                        'posts' => $bonusCardBuilder->main($postsByGeo->take(3)->toArray())
                    ];
                }
            }

            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function vendors(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['VENDOR']);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function news(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['NEWS']);
        if(!$data->isEmpty()) {
            $newsCardBuilder = new NewsCardBuilder();
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $newsModel = new Posts(['table' => $this->tables['NEWS'], 'table_meta' => $this->tables['NEWS_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_NEWS,
            ];
            $this->response['body']['news'] = $newsCardBuilder->main($newsModel->getPublicPosts($settings));

            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function games() {
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
            $all_games = $gameCardBuilder->main($game->getPublicPosts($settings));
            for($i=0; $i<count($all_games); $i++) {
                if($all_games[$i]['game_week']) {
                    $this->response['body']['game_week'][] = $all_games[$i];
                    break;
                }
            }
            if(empty($this->response['body']['game_week']) and !empty($all_games)) $this->response['body']['game_week'] = $all_games[0];

            $this->response['body']['games_week_list'] = array_splice($all_games, 0, 10);
            $this->response['body']['games'] = $all_games;

            $vendorCardBuilder = new VendorCardBuilder();
            $vendorModel = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
            $vendorSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::FILTER_LIMIT_VENDORS,
                'order_key' => 'rating'
            ];
            $this->response['body']['vendors'] = $vendorCardBuilder->filterCard($vendorModel->getPublicPosts($vendorSettings));

            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function search($searchWord, $lang) {
        if(empty($searchWord)) {
            $this->response['body']['posts'] = [];
            $this->response['confirm'] = 'ok';
        } else {
            $arrDb = [
                'CASINO', 'GAME', 'NEWS', 'VENDOR'
            ];
            $posts = [];
            foreach($arrDb as $db) {
                $posts = array_merge($posts, BaseCardBuilder::defaultCard(Posts::searchPublicByTitle($lang, $this->tables[$db], $searchWord)));
            }
            $this->response['body']['posts'] = $posts;
            $this->response['confirm'] = 'ok';
            $this->response['search'] = 'true';
        }
        return $this->response;
    }
    public function bonusRoomCasino() {
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['BONUS_ROOM_CASINO']);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $newsCardBuilder = new NewsCardBuilder();
            $newsModel = new Posts(['table' => $this->tables['NEWS'], 'table_meta' => $this->tables['NEWS_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => 4,
            ];
            $this->response['body']['news'] = $newsCardBuilder->main($newsModel->getPublicPosts($settings));

            $funnelCardBuilder = new FunnelCardBuilder();
            $funnelModel = new Posts(['table' => $this->tables['FUNNEL'], 'table_meta' => $this->tables['FUNNEL_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => 1000,
            ];
            $this->response['body']['funnels'] = $funnelCardBuilder->main($funnelModel->getPublicPosts($settings));

            $this->response['body']['authors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['PAGE_AUTHOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new AuthorCardBuilder();
                $Model = new Posts(['table' => $this->tables['AUTHOR'], 'table_meta' => $this->tables['AUTHOR_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->summary($publicPosts);
                $this->response['body']['authors'] = $posts;     
            }
            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
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
            $this->response['body']['page_author'] = self::relativePostPost($id, $this->tables['PAGES'], 
                                                                                   $this->tables['AUTHOR'], 
                                                                                   $this->tables['PAGE_AUTHOR_RELATIVE']);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $post = new Pages();
        $data_save = $this->serialize->validateUpdate($data);
        $post->updateById($data['id'], $data_save);
         self::updatePostPost($data['id'], $data['page_author'], $this->tables['PAGES'], 
                                                                  $this->tables['AUTHOR'], 
                                                                  $this->tables['PAGE_AUTHOR_RELATIVE']); 
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
    public function default($id) {
        $post = new Pages();
        $data = $post->getPublicPostByUrl($id);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $this->response['body']['authors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['PAGE_AUTHOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new AuthorCardBuilder();
                $Model = new Posts(['table' => $this->tables['AUTHOR'], 'table_meta' => $this->tables['AUTHOR_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->summary($publicPosts);
                $this->response['body']['authors'] = $posts;
            }
            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    protected static function relativePostPost($id, $table_1, $table_2, $relative_table) {
        $data = [];
        $current_post = DB::table($table_1)->where('id', $id)->get();
        if($current_post->isEmpty()) {
            return $data;
        }
        else {
            $arr_title_relative = [];
            $list_relative = DB::table($table_2)->where('lang', $current_post[0]->lang)->get();
            if(!$list_relative->isEmpty()) {
                foreach ($list_relative as $item) $arr_title_relative[] = $item->title;
            }
            $data['all_value'] = $arr_title_relative;
            $arr_relative_post_id = Relative::getRelativeByPostId($relative_table, $current_post[0]->id);
            if(empty($arr_relative_post_id)) $data['current_value'] = [];
            else {
                $arr_category = DB::table($table_2)
                    ->whereIn('id', $arr_relative_post_id)
                    ->get();
                $data['current_value'] = [];
                foreach ($arr_category as $item) $data['current_value'][] = $item->title;
            }
            return $data;
        }
    }
    public function updatePostPost($id, $arr_titles, $table_1, $table_2, $relative_table) {
        DB::table($relative_table)->where('post_id', $id)->delete();
        if(!empty($arr_titles)) {
            $current_post = DB::table($table_1)->where('id', $id)->get();
            if(!$current_post->isEmpty()) {
                $arr_relative_posts = DB::table($table_2)
                    ->whereIn('title', $arr_titles)
                    ->where('lang', $current_post[0]->lang)
                    ->get();
                $data = [];
                foreach ($arr_relative_posts as $item) {
                    $data[] = [
                        'post_id' => $current_post[0]->id,
                        'relative_id' => $item->id
                    ];
                }
                Relative::insert($relative_table, $data);
            }
        }
    }
}
