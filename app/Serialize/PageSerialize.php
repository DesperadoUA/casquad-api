<?php

namespace App\Serialize;

use App\Serialize\BaseSerialize;
use App\Services\ShortcodeManager;

class PageSerialize extends BaseSerialize
{
    private const CONTENT_FIELDS = [
        'content_1','content_2','content_3','content_4','content_5',
        'content_6','content_7','content_8','content_9','content_10',
        'content_11','content_12','content_13','content_14','content_15'
    ];

    /** ADMIN */
    public function adminSerialize($data)
    {
        $newData = self::adminCommonSerialize($data);

        $newData['faq']          = json_decode($data->faq ?? '[]', true) ?: [];
        $newData['faq_title']    = $data->faq_title;
        $newData['author_summary'] = $data->author_summary;

        foreach (self::CONTENT_FIELDS as $field) {
            $newData[$field] = $data->{$field};
        }

        return $newData;
    }

    /** UPDATE VALIDATION */
    public function validateUpdate($data)
    {
        $newData = self::commonValidateInsert($data);

        $newData['faq']           = json_encode($data['faq'] ?? []);
        $newData['faq_title']     = $data['faq_title'] ?? '';
        $newData['author_summary'] = $data['author_summary'] ?? '';

        foreach (self::CONTENT_FIELDS as $field) {
            $newData[$field] = $data[$field] ?? '';
        }

        return $newData;
    }

    /** FRONT */
    public function frontSerialize($data)
    {
        $newData = self::frontCommonSerialize($data);

        $newData['faq']           = json_decode($data->faq ?? '[]', true) ?: [];
        $newData['faq_title']     = $data->faq_title;
        $newData['author_summary'] = $data->author_summary;

        foreach (self::CONTENT_FIELDS as $field) {
            $clean = self::cleanContent($data->{$field});
            $newData[$field] = ShortcodeManager::parse(htmlspecialchars_decode($clean), $data);
        }

        return $newData;
    }
}
