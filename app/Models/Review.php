<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Review extends Model {
    const LIMIT = 8;
    const OFFSET = 0;
    const ORDER_BY = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG = 1;
    function __construct() {
        parent::__construct([]);
        $this->table = 'reviews';
    }
    public function getPostById($id) {
        $post = DB::table($this->table)
            ->where('id', $id)
            ->get();
        return $post;
    }
     public function getPosts($settings = []) {
        $limit = isset($settings['limit']) ? $settings['limit'] : self::LIMIT;
        $offset = isset($settings['offset']) ? $settings['offset'] : self::OFFSET;
        $order_by = isset($settings['order_by']) ? $settings['order_by'] : self::ORDER_BY;
        $order_key = isset($settings['order_key']) ? $settings['order_key'] : self::ORDER_KEY;
        $lang = isset($settings['lang']) ? $settings['lang'] : self::LANG;
        $posts = DB::table($this->table)
            ->where('lang', $lang)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order_key, $order_by)
            ->get();
        return $posts;
    }
    public function getTotalCountByLang($lang = self::LANG) {
        return DB::table($this->table)
            ->where('lang', $lang)
            ->count();
    }
    public function updateById($id, $data) {
        DB::table($this->table)
            ->where('id', $id)
            ->update($data);
    }
    public function deleteById($id){
        DB::table($this->table)->where('id', $id)->delete();
    }
    public function deleteByParentReviewId($id){
        DB::table($this->table)->where('parent_review_id', $id)->delete();
    }
}