<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cash extends Model
{
    const TABLE = 'cash';
    function __construct()
    {
        parent::__construct([]);
    }
    public static function get($url) {
        return DB::table(self::TABLE)->where('url',  $url)->get();
    }
    public static function store($url, $data){
        $data_insert = [
            'url' => $url,
            'data' => $data
        ];
        DB::table(self::TABLE)->insert($data_insert);
    }
    public static function updatePost($url, $data) {
        DB::table(self::TABLE)->where('url', $url)->update(['data' => $data]);
    }
    public static function deleteAll(){
        DB::table(self::TABLE)->truncate();
    }
}