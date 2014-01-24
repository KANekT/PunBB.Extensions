<div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
    <div class="sf-box checkbox">
<?php if ($user['id'] == App::$forum_user['id']):
            if (App::$forum_user['is_admmod'] || $user['thanks_disable_adm'] == 0) : ?>
                    <span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="form[thanks_enable]" value="1"<?php if ($user['thanks_enable'] == '1') echo ' checked="checked"' ?> /></span>
                    <label for="fld<?php echo App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Thanks Manage'] ?></span><?php echo App::$lang['Thanks Manage help'] ?></label>
<?php		else : ?>
                    <span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="disabled" value="1" disabled="disabled" /></span>
                    <label for="fld<?php echo App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Thanks Manage'] ?></span><?php echo App::$lang['Thanks Individual Disabled'] ?></label>
<?php 		endif; ?>
<?php else : ?>
                    <span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="form[thanks_disable_adm]" value="1"<?php if ($user['thanks_disable_adm'] == '1') echo ' checked="checked"' ?> /></span>
                    <label for="fld<?php echo App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Thanks Manage'] ?></span><?php echo App::$lang['Thanks Individual adm'] ?></label>
<?php endif; ?>
    </div>
</div>
