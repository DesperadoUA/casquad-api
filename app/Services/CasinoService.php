<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Models\Category;
use App\Models\Cash;
use App\Services\FrontBaseService;
use App\CardBuilder\VendorCardBuilder;
use App\CardBuilder\PaymentCardBuilder;
use App\CardBuilder\CurrencyCardBuilder;
use App\CardBuilder\BonusCardBuilder;
use App\CardBuilder\LanguageCardBuilder;
use App\CardBuilder\CasinoCardBuilder;
use App\CardBuilder\GameCardBuilder;
use App\CardBuilder\BaseCardBuilder;

class CasinoService extends FrontBaseService {
    protected $response;
    protected $config;
    const MAIN_PAGE_LIMIT_CASINO = 10;
    const CATEGORY_LIMIT_CASINO = 1000;
    const CATEGORY_LIMIT_GAME = 1000;
    const SLUG = 'casino';
    const ASIDE_LIMIT_BONUS = 5;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.CASINO');
        $this->configTables =  [
            'table' => $this->tables['CASINO'],
            'table_meta' => $this->tables['CASINO_META'],
            'table_category' => $this->tables['CASINO_CATEGORY'],
            'table_relative' => $this->tables['CASINO_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new CasinoCardBuilder();
    }
    public function show($id, $geo) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);

            $this->response['body']['vendors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_VENDOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new VendorCardBuilder();
                $Model = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->filterCard($publicPosts);
                usort($posts, function($a, $b) {
                    return -((int)$a['rating'] - (int)$b['rating']);
                });
                $this->response['body']['vendors'] = $posts;
            }

            $this->response['body']['payments'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_PAYMENT_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new PaymentCardBuilder();
                $Model = new Posts(['table' => $this->tables['PAYMENT'], 'table_meta' => $this->tables['PAYMENT_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->main($publicPosts);
                usort($posts, function($a, $b) {
                    return -((int)$a['rating'] - (int)$b['rating']);
                });
                $this->response['body']['payments'] = $posts;
            }

            $this->response['body']['deposit'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_DEPOSIT_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new PaymentCardBuilder();
                $Model = new Posts(['table' => $this->tables['PAYMENT'], 'table_meta' => $this->tables['PAYMENT_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->main($publicPosts);
                usort($posts, function($a, $b) {
                    return -((int)$a['rating'] - (int)$b['rating']);
                });
                $this->response['body']['deposit'] = $posts;
            }

            $this->response['body']['currencies'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_CURRENCY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new CurrencyCardBuilder();
                $Model = new Posts(['table' => $this->tables['CURRENCY'], 'table_meta' => $this->tables['CURRENCY_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->main($publicPosts);
                usort($posts, function($a, $b) {
                    return -((int)$a['rating'] - (int)$b['rating']);
                });
                $this->response['body']['currencies'] = $posts;
            }

            $this->response['body']['languages'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_LANGUAGE_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new LanguageCardBuilder();
                $Model = new Posts(['table' => $this->tables['LANGUAGE'], 'table_meta' => $this->tables['LANGUAGE_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->main($publicPosts);
                usort($posts, function($a, $b) {
                    return -((int)$a['rating'] - (int)$b['rating']);
                });
                $this->response['body']['languages'] = $posts;
            }

            $this->response['body']['bonuses'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['BONUS_CASINO_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new BonusCardBuilder();
                $Model = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
                $publicPosts = $Model->getPublicPostsByArrIdAndGeo($arr_posts, $geo);
                $this->response['body']['bonuses'] = $CardBuilder->main($publicPosts);
            }

            $this->response['body']['games'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['GAME_CASINO_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new GameCardBuilder();
                $Model = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['games'] = $CardBuilder->main($publicPosts);
            }

            $this->response['body']['casinos'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_CASINO_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new CasinoCardBuilder();
                $Model = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
                $publicPosts = $Model->getPublicPostsByArrIdAndGeo($arr_posts, $geo);
                $this->response['body']['casinos'] = $CardBuilder->main($publicPosts);
            }
            $ref_list = [];
            foreach($this->response['body']['ref'] as $item) {
                $ref_list[$item['value_2']] = $item['value_1'];
            }
            $this->response['body']['ref'] = $ref_list;
            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function categoryWithGeo($id, $geo) {
        $parentData = parent::categoryWithGeo($id, $geo);
        if($parentData['confirm'] !== 'error') {
            $bonusCardBuilder = new BonusCardBuilder();
            $bonus = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
            $topBonusSettings = [
                'lang'      => $parentData['body']['lang'],
                'limit'     => self::ASIDE_LIMIT_BONUS,
                'order_key' => 'rating',
                'geo' => $geo
            ];
            $this->response['body']['top_bonuses'] = $bonusCardBuilder->main($bonus->getPublicPostsByGeo($topBonusSettings));

            $baseCardBuilder = new BaseCardBuilder();
            $casinoCategory = new Category([
                'table' => $this->tables['CASINO'], 
                'table_meta' => $this->tables['CASINO_META'], 
                'table_category' => $this->tables['CASINO_CATEGORY'],
                'table_relative' => $this->tables['CASINO_CATEGORY_RELATIVE']
            ]);
            $casinoCategorySettings = [
                'lang' => $parentData['body']['lang'],
            ];
            $this->response['body']['casino_category'] = $baseCardBuilder->defaultCard($casinoCategory->getPublicPosts($casinoCategorySettings));
            Cash::updatePost(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
}