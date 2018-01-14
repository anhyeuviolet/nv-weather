<!-- BEGIN: main -->
<div class="panel-body">
    <div class="vk_gy vk_h" id="wob_loc">{ROW.location_name}, {ROW.country}</div>
    <div class="vk_gy vk_sh" id="wob_dts">{ROW.now}</div>
    <div id="wob_dcp"><span class="vk_gy vk_sh" id="wob_dc">Thời tiết hôm nay: {ROW.description}</span></div>
    
    <img style="float:left;height:64px;width:64px" alt="{ROW.description}" src="http://openweathermap.org/img/w/{ROW.icon}.png" id="wob_tci">    
    <div style="padding-left:10px;float:left">
        <div class="vk_bk sol-tmp" style="float:left;margin-top:-3px;font-size:64px">
            <span class="wob_t" id="wob_tm" style="display:inline">{ROW.temp}</span>
        </div>
        <div class="vk_bk wob-unit" style="float:left;font-size:16px;margin-top:6px">
            <span class="wob_t" style="display:inline" aria-label="°Celsius" aria-disabled="true" role="button">°C</span>
        </div>
    </div>

    
    <div class="vk_gy vk_sh wob-dtl" style="float:right;padding-left:5px;line-height:22px;padding-top:2px;min-width:43%">
        <div>Độ ẩm: <span id="wob_hm">{ROW.humidity} %</span></div>
        <div>Gió: <span><span class="wob_t" id="wob_ws">{ROW.wind_speed} m/s</span></span></div>
        <div>Nhiệt độ thấp nhất/cao nhất: <span><span class="wob_t" id="wob_tmpmm">{ROW.temp_min} °C - {ROW.temp_max} °C</span></span></div>
        <div>Mặt trời mọc: <span><span class="wob_t" id="wob_srss">{ROW.sunrise}</span></span></div>
        <div>Mặt trời lặn: <span><span class="wob_t" id="wob_ss">{ROW.sunset}</span></span></div>
    </div>    
</div>
<style>
    #wob_loc{
        text-transform: uppercase;
        font-size: 20px;
    }
    #wob_dcp{
        text-transform: sentence;
    }
</style>
<!-- END: main -->
<!-- BEGIN: no_api --><p class="text-danger"><strong>API is missing</strong></p><!-- END: no_api -->