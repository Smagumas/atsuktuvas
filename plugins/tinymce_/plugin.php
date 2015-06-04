<?php
/**
 * Created by PhpStorm.
 * User: Lexita
 * Date: 14.8.11
 * Time: 11.49
 */
class TinyMce {
    function __construct() {
        global $document;
        $document->includeAsset(Document::ASSET_JS,__DIR__.'/all.min.css');
        $document->includeAsset(Document::ASSET_JS,__DIR__.'/tinymce.js');
        $document->assign('document', [
            'bottom' => $document->fetch(__DIR__.'/templates/script.tpl')
        ], true);
    }
}