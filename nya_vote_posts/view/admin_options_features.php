			<div class="content-head">
				<h2 class="hn"><span><?php echo App::$lang['Vote Posts features head'] ?></span></h2>
			</div>
			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><span><?php echo App::$lang['Vote Posts legend'] ?></span></legend>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page['fld_count'] ?>" name="form[vote_posts_enabled]" value="1"<?php if (App::$forum_config['o_vote_posts_enabled'] == '1') echo ' checked="checked"' ?> /></span>
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><span><?php echo App::$lang['Vote Posts enable legend'] ?></span> <?php echo App::$lang['Vote Posts enable'] ?></label>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page['fld_count'] ?>" name="form[vote_posts_show_full]" value="1"<?php if (App::$forum_config['o_vote_posts_show_full'] == '1') echo ' checked="checked"' ?> /></span>
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><span><?php echo App::$lang['Vote Posts show full'] ?></span> <?php echo App::$lang['Vote Posts show full descr'] ?></label>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Max message'] ?></span><small><?php echo App::$lang['Max message help'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="form[vote_posts_maxmessage]" size="6" maxlength="6" value="<?php echo intval(App::$forum_config['o_vote_posts_maxmessage']) ?>" /></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Timeout'] ?></span><small><?php echo App::$lang['Timeout help'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="form[vote_posts_timeout]" size="6" maxlength="6" value="<?php echo App::$forum_config['o_vote_posts_timeout'] ?>" /></span>
					</div>
				</div>
			</fieldset>
