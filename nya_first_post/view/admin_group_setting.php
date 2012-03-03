<fieldset class="mf-set set<?php echo ++$forum_page['item_count'] ?>">
    <legend>
        <span><?php echo App::$lang['First post legend'] ?></span>
    </legend>
    <div class="mf-box">
        <div class="mf-item">
            <span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="fp_enable" value="1"<?php if ($group['g_fp_enable'] == '1') echo ' checked="checked"' ?> /></span>
            <label for="fld<?php echo $forum_page['fld_count'] ?>"><?php echo App::$lang['Allow first post label'] ?></label>
        </div>
    </div>
</fieldset>