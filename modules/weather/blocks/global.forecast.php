<?php

/**
 * @Project Weather 4.3.00
 * @Author Thuc Vinh (tichtacso.com@gmail.com)
 * @Copyright (C) 2017 Tich Tac So. All rights reserved
 * @Createdate 22/12/2017, 15:20
 */


if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_forecast_blocks' ) )
{
	function nv_block_config_forecast_blocks( $module, $data_block, $lang_block )
	{
		global $nv_Cache;
		$html = '';
		$html .= '<tr>';
		$html .= '	<td>' . $lang_block['location'] . '</td>';
		$html .= "	<td><select name=\"location\">\n";
		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_weather WHERE status = 1 ORDER BY weight ASC";
        $list = $nv_Cache->db( $sql, '', $module );
        foreach( $list as $l )
		{
			$html .= '<option value="' . $l['location_code'] . '" ' . ( ( $data_block['location'] == $l['location_code'] ) ? ' selected="selected"' : '' ) . '>' . $l['location_name'] . '</option>';
		}
		$html .= '</select></td>';
		$html .= '</tr>';

		return $html;
	}

	function nv_block_config_forecast_blocks_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['location'] = $nv_Request->get_int( 'location', 'post', 0 );
		return $return;
	}

	function nv_forecast_blocks( $block_config )
	{
		global $global_config, $site_mods, $db, $module_config, $nv_Cache;
		$module = $block_config['module'];
		$weather_config = $module_config[$module];
        $array_th = array();
        
        $cache_file = '';
        $contents = '';

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_weather WHERE status = 1 ORDER BY weight ASC';
        $list = $nv_Cache->db( $sql, '', $module );
        
        $cache_file = NV_LANG_DATA . '_' . $module . '_' . NV_CURRENTTIME . '_' . $block_config['location'] . '_forecast_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module, $cache_file, 660)) != false) {
            $contents = $cache;
        }

        if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/weather/block.forecast.tpl' ) )
        {
            $block_theme = $global_config['module_theme'];
        }
        elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/weather/block.forecast.tpl' ) )
        {
            $block_theme = $global_config['site_theme'];
        }
        else
        {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate( 'block.forecast.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/weather' );
        if( !empty($weather_config['openweather_api'])){
            $xtpl->assign( 'TEMPLATE', $block_theme );
            $xtpl->assign( 'CODE', $block_config['location'] );
            
            
            if (empty($contents)) {
                $json_array = file_get_contents('http://api.openweathermap.org/data/2.5/forecast?id=' . $block_config['location'] . '&lang=' . NV_LANG_DATA . '&units=metric&appid=' . $weather_config['openweather_api']);
                $contents = json_decode($json_array, true);
            }
            if ( $contents != '' and $cache_file != '') {
                $nv_Cache->setItem($module, $cache_file, $contents);
            }
            
            $forecast = array();
            $arr_forecast_id = array('0','8','16','24','32');
            foreach( $contents['list'] as $key => $array_forecast ){
                if( ! in_array( $key, $arr_forecast_id)){
                    unset ($array_forecast);
                }
                if( isset($array_forecast) and !empty($array_forecast) ){

                    $forecast[$key]['date'] = nv_date('d/m', $array_forecast['dt']);
                    $forecast[$key]['temp'] = $array_forecast['main']['temp'];
                    $forecast[$key]['temp_min'] = $array_forecast['main']['temp_min'];
                    $forecast[$key]['temp_max'] = $array_forecast['main']['temp_max'];
                    $forecast[$key]['icon'] = $array_forecast['weather'][0]['icon'];
                    $forecast[$key]['humidity'] = $array_forecast['main']['humidity'];
                    $forecast[$key]['wind_speed'] = $array_forecast['wind']['speed'];
                    $forecast[$key]['description'] = $array_forecast['weather'][0]['description'];
                }
            }
            $row = $forecast[0];

            $row['country'] = $contents['city']['country'];
            $row['location_name'] = $contents['city']['name'];
            $row['now'] = nv_date('H:i D', NV_CURRENTTIME);
            
            foreach( $forecast as $data ){
                $xtpl->assign( 'DATA', $data );
                $xtpl->parse( 'main.forecast' );
            }
            
            $xtpl->assign( 'ROW', $row );
            
            $xtpl->parse( 'main' );
            return $xtpl->text( 'main' );
        }else{
            $xtpl->parse( 'no_api' );
            return $xtpl->text( 'no_api' );
        }
	}

}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_forecast_blocks( $block_config );
}