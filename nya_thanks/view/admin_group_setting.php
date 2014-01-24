				<div class="content-head">
					<h3 class="hn"><span><?php echo App::$lang['Thanks permissions'] ?></span></h3>
				</div>
				<fieldset class="mf-set set<?php echo ++App::$forum_page['item_count'] ?>">
					<legend><span><?php echo App::$lang['Thanks enable legend'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="thanks_enable" value="1"<?php if ($group['g_thanks_enable'] == '1') echo ' checked="checked"' ?> /></span>
							<label for="fld<?php echo App::$forum_page['fld_count'] ?>"><?php echo App::$lang['Thanks enable'] ?></label>
						</div>
					</div>
				</fieldset>
				<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Min post'] ?></span> <small><?php echo App::$lang['Min post help'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo App::$forum_page['fld_count'] ?>" name="thanks_min" size="5" maxlength="4" value="<?php echo $group['g_thanks_min'] ?>" /></span>
					</div>
				</div>	
