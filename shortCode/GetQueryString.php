<?php

function get_id_val() {
 // パラメーター「id」の値を取得
 $val = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET["id"] : '';
 // エスケープ処理
 $val = htmlspecialchars($val, ENT_QUOTES);

 // $valを戻り値として設定（ショートコードの値となる）
 return $val;
}

// ショートコード[id]にget_id_val関数をセット
add_shortcode('id', 'get_id_val');
