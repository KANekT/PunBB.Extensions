			<fieldset class="frm-group group<?php echo ++App::$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong><?php echo App::$lang['Display settings'] ?></strong></legend>
				<fieldset class="mf-set set<?php echo ++App::$forum_page['item_count'] ?>">
					<legend><span><?php echo App::$lang['Manage habr'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-item">
<?php if ($user['id'] == App::$forum_user['id']): 
			if (App::$forum_user['is_admmod'] || $user['habr_disable_adm'] == 0) : ?>						
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="form[habr_enable]" value="1"<?php if ($user['habr_enable'] == '1') echo ' checked="checked"' ?> /></span>
							<label for="fld<?php echo App::$forum_page['fld_count'] ?>"><?php echo App::$lang['Manage habr help'] ?></label>
<?php		else : ?>
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="disabled" value="1" disabled="disabled" /></span>
							<label for="fld<?php echo App::$forum_page['fld_count'] ?>"><?php echo App::$lang['Individual Disabled'] ?></label>
<?php 		endif; ?>
<?php else : ?>
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="form[habr_disable_adm]" value="1"<?php if ($user['habr_disable_adm'] == '1') echo ' checked="checked"' ?> /></span>
							<label for="fld<?php echo App::$forum_page['fld_count'] ?>"><?php echo App::$lang['Individual adm'] ?></label>
<?php endif; ?>							
						</div>
					</div>
				</fieldset>
			</fieldset>	
