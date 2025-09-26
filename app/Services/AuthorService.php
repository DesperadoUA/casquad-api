<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\CardBuilder\ArticleCardBuilder;
use App\CardBuilder\CasinoCardBuilder;
use App\Models\Cash;

class AuthorService extends FrontBaseService {
    protected $response;
    protected $config;
    protected $relatives = [
        'CASINO',
        'ARTICLE',
        'BONUS',
        'GAME',
        'NEWS',
        'VENDOR'
    ];
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.AUTHOR');
        $this->configTables =  [
            'table' => $this->tables['AUTHOR'],
            'table_meta' => $this->tables['AUTHOR_META'],
            'table_category' => $this->tables['AUTHOR_CATEGORY'],
            'table_relative' => $this->tables['AUTHOR_CATEGORY_RELATIVE']
        ];
    }
    public function show($id) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);
            $this->response['confirm'] = 'ok';

            $arr_posts = Relative::getPostIdByRelative($this->tables['ARTICLE_AUTHOR_RELATIVE'], $data[0]->id);
            $this->response['body']['articles'] = [];
            $this->response['body']['articles_total'] = 0;
            if(!empty($arr_posts)) {
                $CardBuilder = new ArticleCardBuilder();
                $Model = new Posts(['table' => $this->tables['ARTICLE'], 'table_meta' => $this->tables['ARTICLE_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['articles'] = array_slice($CardBuilder->author($publicPosts), 0, 4);
                $this->response['body']['articles_total'] = count($publicPosts);
            }

            $arr_posts = Relative::getPostIdByRelative($this->tables['CASINO_AUTHOR_RELATIVE'], $data[0]->id);
            $this->response['body']['casinos'] = [];
            $this->response['body']['casinos_total'] = 0;
            if(!empty($arr_posts)) {
                $CardBuilder = new CasinoCardBuilder();
                $Model = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['casinos'] = array_slice($CardBuilder->author($publicPosts), 0, 4);
                $this->response['body']['casinos_total'] = count($publicPosts);
            }
            $this->response['body']['total_posts'] = $this->countPostsByAuthor($data[0]->id);

            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function relativeArticles($id, $offset, $limit) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['confirm'] = 'ok';

            $arr_posts = Relative::getPostIdByRelative($this->tables['ARTICLE_AUTHOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new ArticleCardBuilder();
                $Model = new Posts(['table' => $this->tables['ARTICLE'], 'table_meta' => $this->tables['ARTICLE_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['posts'] = array_slice($CardBuilder->author($publicPosts), $offset, $limit);
                $this->response['body']['total'] = count($publicPosts);
            }
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function relativeCasinos($id, $offset, $limit) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['confirm'] = 'ok';

            $arr_posts = Relative::getPostIdByRelative($this->tables['CASINO_AUTHOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new CasinoCardBuilder();
                $Model = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['posts'] = array_slice($CardBuilder->author($publicPosts), $offset, $limit);
                $this->response['body']['total'] = count($publicPosts);
            }
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function countPostsByAuthor(int $authorId): int {
        $models = [];
        foreach ($this->relatives as $entity) {
            $models[$entity] = new Posts([
                'table'      => $this->tables[$entity],
                'table_meta' => $this->tables[$entity . '_META']
            ]);
        }
        $total = 0;
        foreach ($this->relatives as $entity) {
            $relative = Relative::getPostIdByRelative($this->tables[$entity . '_AUTHOR_RELATIVE'], $authorId);
            $total += count($models[$entity]->getPublicPostsByArrId($relative));
        }
        return $total;
    }
}
