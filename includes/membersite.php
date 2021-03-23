<?php
/**
 * サイトのアクセス権限の制御に関する記述
 */

/**
 * 会員でない場合は、ログインページへリダイレクトする
 */
add_action(
	'template_redirect',
	function () {
		$p = get_post();
		$not_restrict = get_field( 'not_restrict', $p->ID );
		if ( ! $not_restrict && ! is_user_logged_in() && ! is_feed() ) {
			wp_redirect( wp_login_url( get_permalink() ) );
			exit;
		}
	}
);

/**
 * リダイレクトの調整
 */
add_filter(
	'login_redirect',
	function ( $redirect_to, $request ) {
		if ( empty( $request ) ) {
				$redirect_to = home_url( '' );
		}
		return $redirect_to;
	},
	10,
	3
);

/**
 * 管理バーを表示しない
 */
add_filter(
	'show_admin_bar',
	function ( $content ) {
		return ( current_user_can( 'administrator' ) ) ? $content : false;
	}
);

/**
 * 指定されたシリーズに対してユーザーがアクセス権限を持っているか？をチェックする。
 *
 * @param object  $series
 * @param integer $user_id
 * @return void
 */
function bpcast_has_access_series( $series, $user_id ) {

	if ( 0 === get_user_by( 'ID', $user_id ) ) {
		return false;
	}

	if ( is_numeric( $series ) ) {
		$series = get_term_by( 'id', $series, 'series' );
		if ( false === $series ) {
			return false;
		}
	}

	$restrict = get_field( 'restrict_setting', $series ); // all, restrict
	if ( 'all' === $restrict ) {
		return true;
	} elseif ( 'restrict' === $restrict ) {

		$users = get_field( 'restrict_user', $series );
		if ( is_array( $users ) && ( false !== array_search( $user_id, $users ) ) ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * ユーザー固有のトークンを生成、取得する
 *
 * @param string $user_id
 * @return void
 */
function bpcast_get_user_token( $user_id = '' ) {
	if ( $user_id === '' ) {
		$user = wp_get_current_user();
	} else {
		$user = get_user_by( 'ID', $user_id );
	}

	if ( false === $user || $user === 0 ) {
		return false;
	} else {
		$user_id = $user->ID;
	}

	$token = get_user_meta( $user_id, 'bpcast_token', true );

	if( '' === $token ) {
		$stuck = str_split( 'abcdefghijklmnopqrstuvwxyz01234567890' );
		$token = '';
		$len   = count( $stuck );
		for ( $i = 0; $i < 8; $i++ ) {
			$token .= $stuck[ rand(0, $len) ];
		}

		update_user_meta( $user_id, 'bpcast_token', $token );
	}

	return $token;
}

/**
 * SSPのものではなく、別途アクセス権限を調査して、グローバル変数（あぁ、やってしまった・・・）に格納する。
 * あとで、 ssp_feed_item_enclosure などで使う。
 */
function bpcast_ssp_feed_access( $give_access, $series_id ){
	global $bpcast_series_access;

	$ret = preg_match( '|/bpcast_token/([^/]+)/?|', $_SERVER['REQUEST_URI'], $matches );
	if ( 0 === $ret || false === $ret ) {
		$bpcast_series_access = false;
		return $give_access;
	}
	$token = $matches[1];

	$user_query = get_users(
		array(
			'meta_key'   => 'bpcast_token',
			'meta_value' => $token,
		)
	);

	if ( count( $user_query ) ) {
		$user_id    = $user_query[0]->ID;
		$bpcast_series_access = bpcast_has_access_series( $series_id, $user_id );
	} else {
		$bpcast_series_access = false;
	}

	return $give_access;
}
add_filter( 'ssp_feed_access', 'bpcast_ssp_feed_access', 10, 2 );


function bpcast_ssp_feed_item_enclosure( $enclosure, $id ) {
	global $bpcast_series_access;

	if ( false === $bpcast_series_access ) {
		return '';
	} else {
		return $enclosure;
	}
}
add_filter( 'ssp_feed_item_enclosure', 'bpcast_ssp_feed_item_enclosure', 10, 2 );

function bpcast_ssp_feed_title( $title, $series_id ) {
	global $bpcast_series_access;

	if ( false === $bpcast_series_access ) {
		return '【ご利用できません】' . $title;
	} else {
		return $title;
	}
}
add_filter( 'ssp_feed_title', 'bpcast_ssp_feed_title', 10, 2 );
