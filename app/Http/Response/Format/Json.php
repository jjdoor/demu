<?php

namespace App\Http\Response\Format;

//参考：https://learnku.com/laravel/t/16075?#reply81021
class Json extends \Dingo\Api\Http\Response\Format\Json
{
    /**
     * Encode the content to its JSON representation.
     *
     * @param $content
     * @return string
     * @throws
     */
    protected function encode($content)
    {
        $jsonEncodeOptions = [];

        // Here is a place, where any available JSON encoding options, that
        // deal with users' requirements to JSON response formatting and
        // structure, can be conveniently applied to tweak the output.

        if ($this->isJsonPrettyPrintEnabled()) {
            $jsonEncodeOptions[] = JSON_PRETTY_PRINT;
        }
        if (!isset($content['code'])) {
            if (is_array($content) && isset($content['data'])) {
//                $newContent = $content;
                $newContent = [];
                $content['result'] = $content['data'];
                unset($content['data']);
                $newContent['data'] = $content;
                $newContent['code'] = 200;
                $newContent['message'] = '成功';
            } else {
                $newContent['data'] = $content;
                $newContent['code'] = 200;
                $newContent['message'] = '成功';
            }

        } else {
            $newContent = $content;
        }

        if (isset($newContent['errors'])) {
            $newContent['code'] = 422;
        }

        $encodedString = $this->performJsonEncoding($newContent, $jsonEncodeOptions);

        if ($this->isCustomIndentStyleRequired()) {
            $encodedString = $this->indentPrettyPrintedJson(
                $encodedString,
                $this->options['indent_style']
            );
        }

        return $encodedString;
    }
}
