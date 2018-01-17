<?php

/**
 * @Project Weather 4.3.00
 * @Author Thuc Vinh (tichtacso.com@gmail.com)
 * @Copyright (C) 2017 Tich Tac So. All rights reserved
 * @Createdate 22/12/2017, 15:20
 */


if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['list'];
$array = array();
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data . ' ORDER BY weight ASC');

$sql = $db->sql();
$sth = $db->prepare($sql);
$sth->execute();
$num = $sth->fetchColumn();

if( $num < 1 ) {
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content' );
	die();
}

$array_status = array( $lang_module['inactive'], $lang_module['active'] );

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$i = 0;
$generate_page = nv_generate_page($base_url, $num, $per_page, $page);

$db->select('*')
->from(NV_PREFIXLANG . '_' . $module_data . ' ORDER BY weight ASC');
$db->limit($per_page);
$db->offset(($page - 1) * $per_page);
$sql = $db->sql();
$sth = $db->prepare($sql);
$sth->execute();
$_rows = $sth->fetchAll();

foreach ( $_rows as $row )
{
	$row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id'];

	for( $i = 1; $i <= $num; ++$i )
	{
		$xtpl->assign( 'WEIGHT', array(
			'w' => $i,
			'selected' => ( $i == $row['weight'] ) ? ' selected="selected"' : ''
		) );

		$xtpl->parse( 'main.row.weight' );
	}

	foreach( $array_status as $key => $val )
	{
		$xtpl->assign( 'STATUS', array(
			'key' => $key,
			'val' => $val,
			'selected' => ( $key == $row['status'] ) ? ' selected="selected"' : ''
		) );

		$xtpl->parse( 'main.row.status' );
	}

	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.row' );
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';