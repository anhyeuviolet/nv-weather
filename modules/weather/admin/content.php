<?php

/**
 * @Project Weather 4.3.00
 * @Author Thuc Vinh (tichtacso.com@gmail.com)
 * @Copyright (C) 2017 Tich Tac So. All rights reserved
 * @Createdate 22/12/2017, 15:20
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$row = array();
$error = '';
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['location_code'] = $nv_Request->get_int( 'location_code', 'post', 0 );
	$row['location_name'] = $nv_Request->get_title( 'location_name', 'post', '' );
    
	if( empty( $row['location_name'] ) )
	{
		$error = $lang_module['error_required_location_name'];
	}
	elseif( empty( $row['location_code'] ) ) 
	{
		$error = $lang_module['error_required_location_code'];
	}
    else
	{
		try
		{
			if( empty( $row['id'] ) )
			{
                $weight = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data)->fetchColumn();
                $weight = intval( $weight ) + 1;
        			 
				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (location_code, location_name, weight) VALUES (:location_code, :location_name, ' . $weight . ')' );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET location_code = :location_code, location_name = :location_name WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':location_code', $row['location_code'], PDO::PARAM_INT );
			$stmt->bindParam( ':location_name', $row['location_name'], PDO::PARAM_STR, strlen($row['location_name']) );			

			$exc = $stmt->execute();
			if( $exc )
			{
                $nv_Cache->delMod( $module_name );
                nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['add'] . ' Hoặc ' . $lang_module['edit'], $row['location_name'], $admin_info['userid'] );			 
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
				die();
			}
		}
		catch( PDOException $e )
		{
			$error = $e->getMessage();
		}
	}
}

if( $row['id'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
    
    $page_title = $lang_module['edit'];
}
else
{
	$row['id'] = 0;
	$row['location_code'] = 0;
	$row['location_name'] = '';
    
    $page_title = $lang_module['add'];
}


$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );

if( $error )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';