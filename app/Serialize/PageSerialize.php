<?php
namespace App\Serialize;
use App\Serialize\BaseSerialize;
use App\Services\ShortcodeManager;

class PageSerialize extends BaseSerialize {
    public function adminSerialize($data){
        $newData = self::adminCommonSerialize($data);
        $newData['faq'] = empty(json_decode($data->faq, true)) ? [] : json_decode($data->faq, true);
        $newData['faq_title'] = $data->faq_title;
        $newData['author_summary'] = $data->author_summary;
        $newData['content_1'] = $data->content_1;
        $newData['content_2'] = $data->content_2;
        $newData['content_3'] = $data->content_3;
        $newData['content_4'] = $data->content_4;
        $newData['content_5'] = $data->content_5;
        return $newData;
    }
    public function validateUpdate($data){
        $newData = self::commonValidateInsert($data);
        $newData['faq'] = json_encode($data['faq']);
        $newData['faq_title'] = empty($data['faq_title']) ? '' : $data['faq_title'];
        $newData['author_summary'] = empty($data['author_summary']) ? '' : $data['author_summary'];
        $newData['content_1'] = empty($data['content_1']) ? '' : $data['content_1'];
        $newData['content_2'] = empty($data['content_2']) ? '' : $data['content_2'];
        $newData['content_3'] = empty($data['content_3']) ? '' : $data['content_3'];
        $newData['content_4'] = empty($data['content_4']) ? '' : $data['content_4'];
        $newData['content_5'] = empty($data['content_5']) ? '' : $data['content_5'];
        return $newData;
    }
    public function frontSerialize($data) {
        $newData = self::frontCommonSerialize($data);
        $newData['faq'] = empty(json_decode($data->faq, true)) ? [] : json_decode($data->faq, true);
        $newData['faq_title'] = $data->faq_title;
        $newData['author_summary'] = $data->author_summary;
        $cleanContent_1 = self::cleanContent($data->content_1);
        $newData['content_1'] = ShortcodeManager::parse(htmlspecialchars_decode($cleanContent_1), $data);
        $cleanContent_2 = self::cleanContent($data->content_2);
        $newData['content_2'] = ShortcodeManager::parse(htmlspecialchars_decode($cleanContent_2), $data);
        $cleanContent_3 = self::cleanContent($data->content_3);
        $newData['content_3'] = ShortcodeManager::parse(htmlspecialchars_decode($cleanContent_3), $data);
        $cleanContent_4 = self::cleanContent($data->content_4);
        $newData['content_4'] = ShortcodeManager::parse(htmlspecialchars_decode($cleanContent_4), $data);
        $cleanContent_5 = self::cleanContent($data->content_5);
        $newData['content_5'] = ShortcodeManager::parse(htmlspecialchars_decode($cleanContent_5), $data);
        return $newData;
    }
}