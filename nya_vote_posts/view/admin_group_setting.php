				<div class="content-head">
					<h3 class="hn"><span><?php echo App::$lang['Vote Posts permissions'] ?></span></h3>
				</div>
				<fieldset class="mf-set set<?php echo ++App::$forum_page['item_count'] ?>">
					<legend><span><?php echo App::$lang['Vote Posts enable legend'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="vote_enable" value="1"<?php if ($group['g_vote_enable'] == '1') echo ' checked="checked"' ?> /></span>
							<label for="fld<?php echo App::$forum_page['fld_count'] ?>"><?php echo App::$lang['Group enable'] ?></label>
						</div>
					</div>
				</fieldset>
				<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Min post for down'] ?></span> <small><?php echo App::$lang['Min post down help'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo App::$forum_page['fld_count'] ?>" name="vote_down_min" size="5" maxlength="4" value="<?php echo $group['g_vote_down_min'] ?>" /></span>
					</div>
					<div class="sf-box text">
						<label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Min post for up'] ?></span> <small><?php echo App::$lang['Min post up help'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo App::$forum_page['fld_count'] ?>" name="vote_up_min" size="5" maxlength="4" value="<?php echo $group['g_vote_up_min'] ?>" /></span>
					</div>
				</div>	
