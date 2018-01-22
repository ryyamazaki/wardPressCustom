<?php

function yomilistFunc( $atts ) {
	// インデックス（行）
	$indexes_parents = array(
		"あ行" => "[ア-オ]",
		"か行" => "[カ-コ]",
		"さ行" => "[サ-ソ]",
		"た行" => "[タ-ト]",
		"な行" => "[ナ-ノ]",
		"は行" => "[ハ-ホ]",
		"ま行" => "[マ-モ]",
		"や行" => "[ヤ-ヨ]",
		"ら行" => "[ラ-ロ]",
		"わ行" => "[ワ-ン]",
		"その他" => ".*"
	);
	// インデックス（50音）
	$indexes = array(
		"ア" => "[ア]", "イ" => "[イ]", "ウ" => "[ウ]", "エ" => "[エ]", "オ" => "[オ]",
		"カ" => "[カ]", "キ" => "[キ]", "ク" => "[ク]", "ケ" => "[ケ]", "コ" => "[コ]",
		"サ" => "[サ]", "シ" => "[シ]", "ス" => "[ス]", "セ" => "[セ]", "ソ" => "[ソ]",
		"タ" => "[タ]", "チ" => "[チ]", "ツ" => "[ツ]", "テ" => "[テ]", "ト" => "[ト]",
		"ナ" => "[ナ]", "ニ" => "[ニ]", "ヌ" => "[ヌ]", "ネ" => "[ネ]", "ノ" => "[ノ]",
		"ハ" => "[ハ]", "ヒ" => "[ヒ]", "フ" => "[フ]", "ヘ" => "[ヘ]", "ホ" => "[ホ]",
		"マ" => "[マ]", "ミ" => "[ミ]", "ム" => "[ム]", "メ" => "[メ]", "モ" => "[モ]",
		"ヤ" => "[ヤ]", "ユ" => "[ユ]", "ヨ" => "[ヨ]",
		"ラ" => "[ラ]", "リ" => "[リ]", "ル" => "[ル]", "レ" => "[レ]", "ロ" => "[ロ]",
		"ワ" => "[ワ]", "ン" => "[ン]",
		"その他" => ".*"
	);
	global $post;
	// 記事データを取得
	// 記事の取得条件
	$args = array(
		'posts_per_page' => '-1', // 取得記事数無制限
		'post_status' => 'publish', // 公開中
		'meta_key' => 'yomi', // カスタムフィールドyomiを持っている記事
		'meta_value' => '', // カスタムフィールドyomiの値は指定しない
		'orderby' => meta_value, // カスタムフィールドyomiの値を基準に並び替え
		'order' => asc // 昇順に並び替え
	);
	$my_posts = get_posts($args); // 上記条件で記事を取得
	$post_data_set = array(); // 配列$post_data_setを用意
	// 上記条件の投稿があるなら
	if ($my_posts) {
		foreach ($my_posts as $post) : // ループスタート
			setup_postdata($post); // get_the_title() などのテンプレートタグを使えるようにする
			// ヨミガナの1文字目を取得する（濁点、半濁点は分離）
			$yomi = get_post_meta($post->ID, 'yomi', true); // カスタムフィールドyomiの値を取得
			$yomi_conv = mb_convert_kana($yomi, 'k', 'UTF-8'); // 全角カタカナを半角カタカナに変換（濁点、半濁点を分離）
			$yomi_conv = mb_convert_kana($yomi_conv, 'K', 'UTF-8'); // 半角カタカナを全角カタカナに変換
			$yomi_first = mb_substr($yomi_conv, 0, 1); // 先頭の１文字を取得
			// 配列に格納する
			$posts['title'] = get_the_title(); // タイトル
			$posts['permalink'] = get_permalink(); // URL
			$posts['yomi'] = $yomi; // ヨミガナ
			$posts['yomi_first'] = $yomi_first; // ヨミガナ1文字目
			$post_data_set[] = $posts; // 配列に格納
		endforeach; // ループ終わり
	}
	// インデックス（50音）ごとの配列に格納する
	$post_data_set_index = array();
	foreach ( $post_data_set as $key => $val) {
		$char = mb_substr( $val['yomi_first'], 0, 1);
		foreach ( $indexes as $index => $pattern ) {
			if (preg_match("/^" . $pattern . "/u", $char)) {
				$post_data_set_index[$index][] = $post_data_set[$key];
				break;
			}
		}
	}
	// インデックス（行）ごとの配列に格納する
	$post_data_set_index_parent = array();
	foreach ( $post_data_set_index as $key => $val) {
		foreach ( $indexes_parents as $indexes_parent => $patterns ) {
			if (preg_match("/^" . $patterns . "/u", $key)) {
				$post_data_set_index_parent[$indexes_parent][$key] = $post_data_set_index[$key];
				break;
			}
		}
	}
	// HTML出力
	$output = "";
	if ($post_data_set_index_parent) {
		// 行ごとに展開
		foreach ($post_data_set_index_parent as $indexes_parent => $posts) : // ループスタート
			$output .= '<h2>'.$indexes_parent.'</h2>';
			// 50音ごとに展開
			foreach ($posts as $index => $post) : // ループスタート
				$output .= '<h3>'.$index.'</h3>';
				$output .= '<ul>' . "\n";
				foreach ($post as $key => $val) {
					$output .= '<li>';
					$output .= '<a href="' .$val['permalink']. '">' .$val['title']. '</a>';
					$output .= '</li>' . "\n";
				}
				$output .= '</ul>' . "\n";
			endforeach; // ループ終わり
		endforeach; // ループ終わり
	}
	// クエリのリセット
	wp_reset_postdata();
	return $output;
}
add_shortcode('yomilist', 'yomilistFunc');
