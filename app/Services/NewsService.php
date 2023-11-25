<?php
namespace App\Services;
use App\Models\Posts;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\NewsCardBuilder;

class NewsService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.NEWS');
        $this->configTables =  [
            'table' => $this->tables['NEWS'],
            'table_meta' => $this->tables['NEWS_META'],
            'table_category' => $this->tables['NEWS_CATEGORY'],
            'table_relative' => $this->tables['NEWS_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new NewsCardBuilder();
    }
    public function show($id) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);
            $settings = [
                'lang' => $data[0]->lang,
                'execute' => [$data[0]->id],
                'limit' => 9
            ];
            $posts = $post->getPublicPostsWithOutIds($settings);
            $all_news = $this->cardBuilder->main($posts);
            $this->response['body']['posts'] = array_slice($all_news, 0, 4);
            $this->response['body']['last_news'] = array_slice($all_news, 4, 5);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
}