<div class="wrap">
    <h2>MojTv Settings</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('mojtv-group'); ?>
        <?php @do_settings_fields('mojtv-group'); ?>

        <table class="form-table">  
            <tr valign="top">
                <th scope="row"><label for="setting_a">Tv Kanali</label></th>
            </tr>
            <tr>
            	<td colspan="2">
              	<table cellpadding="0" cellspacing="0" border="0">
                	<?php
                  	$channels=simplexml_load_file(MOJTV_PLUGIN_CACHE_DIR.'/channels.xml');
										$chCo = 0;
										$isArray = is_array(get_option('mojtv_setting_kanali'));
										foreach($channels->xpath('//channel') as $channel) {
											if ($chCo%6 == 0) {
												echo '<tr>';
											}
											if($isArray == true) {
												echo '<td style="padding:0 5px;"><input '. ( in_array($channel->id, get_option('mojtv_setting_kanali')) ? ' checked' : '' ) .' type="checkbox" name="mojtv_setting_kanali[]" value="'. $channel->id .'"> '.$channel->name.'<td>';
											} else {
												echo '<td style="padding:0 5px;"><input type="checkbox" name="mojtv_setting_kanali[]" value="'. $channel->id .'"> '.$channel->name.'<td>';
											}
											if ($chCo%6 == 5) {
												echo '</tr>';
											}
											$chCo++;
										}
										if ($chCo%6 == 0) {
											echo '</tr>';
										}
									?>
                </table>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="mojtv_setting_refresh_ch">Osvježi popis programa svakih;</label></th>
                <td>
                	<select name="mojtv_setting_refresh_ch" id="mojtv_setting_refresh_ch">
	                  <option value="1"<?php echo ( get_option('mojtv_setting_refresh_ch') == '1' ? ' selected' : '' ) ?>>1 dan</option>
                  	<option value="2"<?php echo ( get_option('mojtv_setting_refresh_ch') == '2' ? ' selected' : '' ) ?>>2 dana</option>
                    <option value="3"<?php echo ( get_option('mojtv_setting_refresh_ch') == '3' ? ' selected' : '' ) ?>>3 dana</option>
                    <option value="4"<?php echo ( get_option('mojtv_setting_refresh_ch') == '4' ? ' selected' : '' ) ?>>4 dana</option>
                    <option value="7"<?php echo ( get_option('mojtv_setting_refresh_ch') == '7' ? ' selected' : '' ) ?>>7 dana</option>
                  </select>
               	</td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="mojtv_setting_type">Prikaži;</label></th>
                <td>
                	<select name="mojtv_setting_type" id="mojtv_setting_type">
	                  <option value="0"<?php echo ( get_option('mojtv_setting_type') == 0 ? ' selected' : '' ) ?>>Filmove</option>
                  	<option value="1"<?php echo ( get_option('mojtv_setting_type') == 1 ? ' selected' : '' ) ?>>Serije</option>
                  </select>
               	</td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="mojtv_setting_next">Za narednih</label></th>
                <td>
                	<select name="mojtv_setting_next" id="mojtv_setting_next">
	                  <option value="1"<?php echo ( get_option('mojtv_setting_next') == 1 ? ' selected' : '' ) ?>>1 dan</option>
                  	<option value="2"<?php echo ( get_option('mojtv_setting_next') == 2 ? ' selected' : '' ) ?>>2 dana</option>
                    <option value="3"<?php echo ( get_option('mojtv_setting_next') == 3 ? ' selected' : '' ) ?>>3 dana</option>
                    <option value="4"<?php echo ( get_option('mojtv_setting_next') == 4 ? ' selected' : '' ) ?>>4 dana</option>
                    <option value="7"<?php echo ( get_option('mojtv_setting_next') == 7 ? ' selected' : '' ) ?>>7 dana</option>
                    <option value="14"<?php echo ( get_option('mojtv_setting_next') == 14 ? ' selected' : '' ) ?>>14 dana</option>
                    <option value="30"<?php echo ( get_option('mojtv_setting_next') == 30 ? ' selected' : '' ) ?>>30 dana</option>
                  </select>
               	</td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="mojtv_setting_height">Visina widgeta;</label></th>
                <td>
                	<input type="text" name="mojtv_setting_height" id="mojtv_setting_height" value="<?php echo get_option('mojtv_setting_height') ?>"/>px
               	</td>
            </tr>
        </table>

        <?php @submit_button(); ?>
    </form>
</div>