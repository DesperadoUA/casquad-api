<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\AuthorCardBuilder;
use App\CardBuilder\CasinoCardBuilder;
use App\CardBuilder\GameCardBuilder;
use App\CardBuilder\NewsCardBuilder;
use App\Models\Review;
use App\CardBuilder\ReviewCardBuilder;

class ArticleService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.ARTICLE');
        $this->configTables =  [
            'table' => $this->tables['ARTICLE'],
            'table_meta' => $this->tables['ARTICLE_META'],
            'table_category' => $this->tables['ARTICLE_CATEGORY'],
            'table_relative' => $this->tables['ARTICLE_CATEGORY_RELATIVE']
        ];
    }
    public function show($id) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);

            $this->response['body']['authors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['ARTICLE_AUTHOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new AuthorCardBuilder();
                $Model = new Posts(['table' => $this->tables['AUTHOR'], 'table_meta' => $this->tables['AUTHOR_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->summary($publicPosts);
                $this->response['body']['authors'] = $posts;
            }

            $this->response['body']['casinos'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['ARTICLE_CASINO_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new CasinoCardBuilder();
                $Model = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['casinos'] = $CardBuilder->main($publicPosts);
            }

            $this->response['body']['games'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['ARTICLE_GAME_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new GameCardBuilder();
                $Model = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['games'] = $CardBuilder->main($publicPosts);
            }

            $this->response['body']['news'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['ARTICLE_NEWS_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new NewsCardBuilder();
                $Model = new Posts(['table' => $this->tables['NEWS'], 'table_meta' => $this->tables['NEWS_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['news'] = $CardBuilder->main($publicPosts);
            }

            $reviewModel = new Review();
            $reviews = $reviewModel->getPublicByParentPostId($data[0]->id, 'article');
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
            $posts = $review_model->getPublicByParentPostId($id, 'article', [
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
