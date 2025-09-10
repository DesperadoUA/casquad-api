<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Models\Review;
use App\Services\FrontBaseService;
use App\CardBuilder\GameCardBuilder;
use App\CardBuilder\VendorCardBuilder;
use App\CardBuilder\CasinoCardBuilder;
use App\Models\Cash;
use App\CardBuilder\ReviewCardBuilder;

class GameService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.GAME');
        $this->configTables =  [
            'table' => $this->tables['GAME'],
            'table_meta' => $this->tables['GAME_META'],
            'table_category' => $this->tables['GAME_CATEGORY'],
            'table_relative' => $this->tables['GAME_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new GameCardBuilder();
    }
    public function show($id, $geo) {
        $post = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);

            $this->response['body']['vendor'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['GAME_VENDOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new VendorCardBuilder();
                $Model = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['vendor'] = $CardBuilder->filterCard($publicPosts);
            }

            $this->response['body']['casinos'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['GAME_CASINO_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new CasinoCardBuilder();
                $Model = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
                $publicPosts = $Model->getPublicPostsByArrIdAndGeo($arr_posts, $geo);
                $this->response['body']['casinos'] = $CardBuilder->sliderCard($publicPosts);
            }
            $ref_list = [];
            foreach($this->response['body']['ref'] as $item) {
                $ref_list[$item['value_2']] = $item['value_1'];
            }

            $reviewModel = new Review();
            $reviews = $reviewModel->getPublicByParentPostId($data[0]->id, 'game');
            $this->response['body']['reviews'] = ReviewCardBuilder::main($reviews);
            $this->response['body']['ref'] = $ref_list;
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
            $posts = $review_model->getPublicByParentPostId($id, 'game', [
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