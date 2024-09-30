<?php

switch ($path[2]) {
    case 'load_media':
        require_once 'op_viewmedia.php';
        break;
    case 'upload':
        require_once 'op_upload.php';
        break;
    case 'uploadmedia':
        require_once 'op_uploadmedia.php';
        break;
    case 'reordermedia':
        require_once 'op_reordermedia.php';
        break;
    case 'deletemedia':
        require_once 'op_deletemedia.php';
        break;
    case 'update_visibility':
        require_once  'op_updatevisibility.php';
        break;
    default:
        break;
}