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
    </div>
    <div class="clear"></div>
    <div class="gic" style="margin-bottom:-1px;padding:0;padding-bottom:1px;">
        <div style="padding-top: 7px; white-space: nowrap; left: 0px; top: 0px; transform: translate3d(0px, 0px, 0px); transition: -moz-transform 0ms ease-in-out 0s;" id="wob_dp">
            <!-- BEGIN: forecast -->
            <div class="wob_df wob_ds" style="display:inline-block;line-height:1;text-align:center;transition-duration:200ms,200ms,200ms;transition-property:background-image,border,font-weight;font-weight:13px;height:90px;width:73px">
                <div class="vk_lgy" style="padding-top:7px;line-height:15px">{DATA.date}</div>
                <div style="display:inline-block">
                    <img style="margin:1px 4px 0;height:48px;width:48px" alt="{DATA.description}" title="{DATA.description}" src="http://openweathermap.org/img/w/{DATA.icon}.png"/></div>
                <div style="font-weight:normal;line-height:15px;font-size:13px">
                    <div class="vk_gy" style="display:block;"><span class="wob_t" style="display:inline">{DATA.temp_min}</span>°</div>
                    <div class="vk_lgy" style="display:block;color:#878787 !important;"><span class="wob_t" style="display:inline">{DATA.temp_max}</span>°</div>
                </div>
            </div>
        <!-- END: forecast -->
        </div>
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