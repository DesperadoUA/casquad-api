<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Review;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\VendorCardBuilder;
use App\CardBuilder\GameCardBuilder;
use App\CardBuilder\CasinoCardBuilder;
use App\CardBuilder\BonusCardBuilder;
use App\CardBuilder\ReviewCardBuilder;
use App\CardBuilder\AuthorCardBuilder;

class VendorService extends FrontBaseService {
    const ASIDE_LIMIT_BONUS = 5; 
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.VENDOR');
        $this->configTables =  [
            'table' => $this->tables['VENDOR'],
            'table_meta' => $this->tables['VENDOR_META'],
            'table_category' => $this->tables['VENDOR_CATEGORY'],
            'table_relative' => $this->tables['VENDOR_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new VendorCardBuilder();
    }
    public function show($id, $geo) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $gameModel = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
            $casinoModel = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);

            $gameIds = Relative::getPostIdByRelative($this->tables['GAME_VENDOR_RELATIVE'], $data[0]->id);
            $games = $gameModel->getPublicPostsByArrId($gameIds);
            $gameCardBuilder = new GameCardBuilder();
            $this->response['body']['games'] = $gameCardBuilder->main($games);

            $casinoIds = Relative::getPostIdByRelative($this->tables['CASINO_VENDOR_RELATIVE'], $data[0]->id);
            $casinos = $casinoModel->getPublicPostsByArrIdAndGeo($casinoIds, $geo);
            $casinoCardBuilder = new CasinoCardBuilder();
            $this->response['body']['casinos'] = $casinoCardBuilder->main($casinos);

            $bonusCardBuilder = new BonusCardBuilder();
            $bonus = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
            $topBonusSettings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::ASIDE_LIMIT_BONUS,
                'order_key' => 'rating',
                'geo' => $geo
            ];
            $this->response['body']['top_bonuses'] = $bonusCardBuilder->main($bonus->getPublicPostsByGeo($topBonusSettings));

            $this->response['body']['authors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['VENDOR_AUTHOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new AuthorCardBuilder();
                $Model = new Posts(['table' => $this->tables['AUTHOR'], 'table_meta' => $this->tables['AUTHOR_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->summary($publicPosts);
                $this->response['body']['authors'] = $posts;
            }

            $reviewModel = new Review();
            $reviews = $reviewModel->getPublicByParentPostId($data[0]->id, 'vendor');
            $this->response['body']['reviews'] = ReviewCardBuilder::main($reviews);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function reviews($id, $sort, $order) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostById($id);
        $sortBy = in_array($sort, config('constants.AVAILABLE_SORT_REVIEW')) ? $sort : 'rating';
        $orderBy = in_array($order, ['asc', 'desc']) ? $order : 'asc';
        if(!$data->isEmpty()) {
            $review_model = new Review();
            $posts = $review_model->getPublicByParentPostId($id, 'vendor', [
                'order_by' => $orderBy,
                'order_key' => $sortBy
            ]);
            $this->response['body']['posts'] = ReviewCardBuilder::main($posts);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
}