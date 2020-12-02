<?php

/**
 * @Project Weather 4.3.00
 * @Author Thuc Vinh (tichtacso.com@gmail.com)
 * @Copyright (C) 2017 Tich Tac So. All rights reserved
 * @Createdate 22/12/2017, 15:20
 */


if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_weather_blocks' ) )
{
	function nv_block_config_weather_blocks( $module, $data_block, $lang_block )
	{
		global $nv_Cache;
		$html = '';
		$html .= '<div>';
		$html .= '<label class="control-label col-sm-6">' . $lang_block['location'] . '</label>';
		$html .= "<div class=\"col-sm-18\"><select class=\"form-control\" name=\"location\" id=\"location_weather\">\n";
		$sql = "SELECT location_code, location_name FROM " . NV_PREFIXLANG . "_weather WHERE status = 1 ORDER BY weight ASC";
        $list = $nv_Cache->db( $sql, '', $module );
        foreach( $list as $l )
		{
			$html .= '<option value="' . $l['location_code'] . '" ' . ( ( $data_block['location'] == $l['location_code'] ) ? ' selected="selected"' : '' ) . '>' . $l['location_name'] . '</option>';
		}
		$html .= '</select></div>';
		$html .= '</div>';
        
		$html .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/select2/select2.min.js"></script>
                    <script type="text/javascript" src="' . NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
                    <script type="text/javascript" src="' . NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/jquery-ui/jquery-ui.min.js"></script>
                    ';
		$html .= '<link type="text/css" href="' . NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
                    <link rel="stylesheet" href="' . NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/select2/select2.min.css">
                    ';
                    
		$html .= '<script type="text/javascript">
                //<![CDATA[
                $(document).ready(function() {
                    $("#location_weather").select2({
                    placeholder: "---------"
                    });
                });
                //]]>
                </script>
                ';
        
		return $html;
	}

	function nv_block_config_weather_blocks_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['location'] = $nv_Request->get_int( 'location', 'post', 0 );
		return $return;
	}

	function nv_weather_blocks( $block_config )
	{
		global $global_config, $site_mods, $db, $module_config, $module_name, $nv_Cache, $lang_module;
		$module = $block_config['module'];
        if ($module_name != $module ){
            require NV_ROOTDIR . '/modules/' . $module . '/language/' . NV_LANG_DATA . '.php';
        }
		$weather_config = $module_config[$module];
        $array_th = array();
        
        $cache_file = '';
        $contents = '';

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_weather WHERE status = 1 ORDER BY weight ASC';
        $list = $nv_Cache->db( $sql, '', $module );
        
        $cache_file = NV_LANG_DATA . '_' . $module . '_' . NV_CURRENTTIME . '_' . $block_config['location'] . '_weather_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module, $cache_file, 660)) != false) {
            $contents = $cache;
        }

        if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/weather/block.weather.tpl' ) )
        {
            $block_theme = $global_config['module_theme'];
        }
        elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/weather/block.weather.tpl' ) )
        {
            $block_theme = $global_config['site_theme'];
        }
        else
        {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate( 'block.weather.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/weather' );
        $xtpl->assign( 'LANG', $lang_module );
        
        if( !empty($weather_config['openweather_api'])){
            $xtpl->assign( 'TEMPLATE', $block_theme );
            $xtpl->assign( 'CODE', $block_config['location'] );
            
            
            if (empty($contents)) {
                $json_array = file_get_contents('http://api.openweathermap.org/data/2.5/weather?id=' . $block_config['location'] . '&lang=' . NV_LANG_DATA . '&units=metric&appid=' . $weather_config['openweather_api'] . '');
                $contents = json_decode($json_array, true);
            }
            if ( $contents != '' and $cache_file != '') {
                $nv_Cache->setItem($module, $cache_file, $contents);
            }
            
            $row['description'] = $contents['weather'][0]['description'];
            $row['icon'] = $contents['weather'][0]['icon'];
            
            $row['temp'] = $contents['main']['temp'];
            $row['humidity'] = $contents['main']['humidity'];
            $row['temp_min'] = $contents['main']['temp_min'];
            $row['temp_max'] = $contents['main']['temp_max'];
            
            $row['wind_speed'] = $contents['wind']['speed'];
            
            $row['country'] = $contents['sys']['country'];
            $row['sunrise'] = nv_date('H:i', $contents['sys']['sunrise']);
            $row['sunset'] = nv_date('H:i', $contents['sys']['sunset']);
            
            $row['location_name'] = $contents['name'];
            
            $row['now'] = nv_date('H:i D', NV_CURRENTTIME);
            
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
	$content = nv_weather_blocks( $block_config );
}
