<?php
return [
    'logo_url'=>'backend/assets/images/logo.png?v='.$_ENV['APP_VERSION'],
    'logo_white_url'=>'backend/assets/images/logo-white.png?v='.$_ENV['APP_VERSION'],
    'fav_icon'=>'backend/assets/images/favicon.ico?v='.$_ENV['APP_VERSION'],

    'default_password'=>123456,
    'bsDependencyEnabled' => false,
    'currencyPrefix'=>'$',
    'itemsPerPage'=>1,
    'gridViewTemplate'=>'{items}<div class="table-footer-content">{summary}<div class="pager">{pager}</div></div>',

    'uploadPath'=>'uploads/',
    'viewPath'=>'../uploads/',

    'allowedVideoExtensions'=>array('mp4','3gp','ogg','wmv','avi','mkv','avi','webm'),
    'allowedExtensions'=>array('jpg','png','gif','jpeg','webp'),
    'allowedFileExtensions'=>array('doc','ppt','pdf','docx','txt','zip','jpg','png','gif','jpeg','webp'),

];
