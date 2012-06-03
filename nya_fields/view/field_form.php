<div class="main-content main-frm">
<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link(App::$forum_url['admin_fields_add']) ?>">
<div class="hidden">
<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link(App::$forum_url['admin_fields_add'])) ?>" />
</div>
<div class="ct-box" id="info-ranks-intro">
<p><?php echo $lang['Fields intro']; ?></p>
</div>
<fieldset class="frm-group group<?php echo ++App::$forum_page['group_count'] ?>">
	<legend class="group-legend"><strong><?php echo $lang['Fields legend'] ?></strong></legend>
	<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
		<div class="sf-box text">
			<label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo $lang['Fields name'] ?></span></label><br />
			<span class="fld-input"><input type="text" id="fld<?php echo App::$forum_page['fld_count'] ?>" name="fields_name" size="24" maxlength="50" /></span>
		</div>
	</div>
	<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
		<div class="sf-box text">
			<label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo $lang['Fields desc'] ?></span></label><br />
			<span class="fld-input"><input type="text" id="fld<?php echo App::$forum_page['fld_count'] ?>" name="fields_desc" size="24" maxlength="100" /></span>
		</div>
	</div>
	<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
		<div class="sf-box text">
			<label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo $lang['Fields url'] ?></span></label><br />
			<span class="fld-input"><input type="text" id="fld<?php echo App::$forum_page['fld_count'] ?>" name="fields_url" size="24" maxlength="100" /></span>
		</div>
	</div>
	<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
		<div class="sf-box checkbox">
			<span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="fields_in_vt" value="1" /></span>
			<label for="fld<?php echo App::$forum_page['fld_count'] ?>"><span><?php echo $lang['Fields checkbox'] ?></span> <?php echo $lang['Fields checkbox label'] ?></label>
		</div>
	</div>
</fieldset>
	<div class="frm-buttons">
		<span class="submit primary"><input type="submit" name="add" value="<?php echo $lang['Add'] ?>" /></span>
	</div>

</form>
</div>
<?php echo $content;