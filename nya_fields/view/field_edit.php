<div class="main-content main-frm">
	<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link(App::$forum_url['admin_fields_id'], $records['id']) ?>">
		<div class="hidden">
			<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link(App::$forum_url['admin_fields_id'], $records['id'])) ?>" />
			<input type="hidden" name="field" value="<?php echo $records['fields_name'] ?>" />
		</div>
		<div class="ct-box" id="info-ranks-intro">
			<p><?php printf(App::$lang['Fields intro id'], $records['id']); ?></p>
		</div>
		<fieldset class="frm-group group<?php echo ++App::$forum_page['group_count'] ?>">
			<legend class="group-legend"><strong><?php echo App::$lang['Fields legend'] ?></strong></legend>
			<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
				<div class="sf-box text">
					<label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Fields name'] ?></span></label><br />
					<span class="fld-input"><input type="text" id="fld<?php echo App::$forum_page['fld_count'] ?>" name="fields_name" size="24" maxlength="50" value="<?php echo $records['fields_name'] ?>" required /></span>
				</div>
			</div>
			<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
				<div class="sf-box text">
					<label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Fields desc'] ?></span></label><br />
					<span class="fld-input"><input type="text" id="fld<?php echo App::$forum_page['fld_count'] ?>" name="fields_desc" size="24" maxlength="50" value="<?php echo $records['fields_desc'] ?>" required /></span>
				</div>
			</div>
			<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
				<div class="sf-box text">
					<label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Fields url'] ?></span></label><br />
					<span class="fld-input"><input type="text" id="fld<?php echo App::$forum_page['fld_count'] ?>" name="fields_url" size="24" maxlength="100" value="<?php echo $records['fields_url'] ?>"/></span>
				</div>
			</div>
			<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
				<div class="sf-box checkbox">
					<span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="fields_in_vt" value="1" <?php if ($records['fields_in_vt'] == 1) echo 'checked="checked" ' ?>/></span>
					<label for="fld<?php echo App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Fields checkbox'] ?></span> <?php echo App::$lang['Fields checkbox label'] ?></label>
				</div>
			</div>
			<div class="frm-buttons">
			<span class="submit primary">
				<input type="submit" name="update" value="<?php echo App::$lang['Update'] ?>" />
			</span>
			<span class="submit primary caution">
				<input type="submit" name="delete" value="<?php echo App::$lang_common['Delete'] ?>" onclick="return confirm('<?php echo App::$lang['Are you sure']; ?>')" />
			</span>
			</div>
		</fieldset>
	</form>
</div>