	<div id="rep_form">
	<div class="main-head">
		<h2 class="hn"><span><?php echo $heading ?></span></h2>
	</div>
	<div class="main-content main-frm">
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo App::$forum_page['form_action'] ?>">
			<div class="hidden">
				<input type="hidden" name="form_sent" value="1" />
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(App::$forum_page['form_action']) ?>" />
			</div>
<?php echo $errors ?>
			<fieldset class="frm-group group<?php echo ++App::$forum_page['group_count'] ?>">
			<div class="txt-set set<?php echo ++App::$forum_page['item_count'] ?>">
				<div class="txt-box textarea">
					<label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Form reason'] ?></span></label><br />
					<div class="txt-input"><span class="fld-input"><textarea cols='60' rows='10' wrap='soft' name="req_message" class='textinput'><?php echo (!empty(App::$forum_page['errors'])) ? App::$forum_page['req_message'] : null;?></textarea></span></div>
				</div>
			</div>
			</fieldset>
			<div class="frm-buttons">
				<p class="postlink conr"><input type="submit" name="submit" value="<?php echo App::$lang_common['Submit'] ?>"  /></p>
			</div>
		</form>
	</div>
	</div>