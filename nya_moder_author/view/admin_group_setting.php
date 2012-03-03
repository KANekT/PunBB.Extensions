

<fieldset class="mf-set set<?php echo ++$forum_page['item_count'] ?>">
    <legend>
        <span><?php echo App::$lang['Allow moder topic author legend'] ?></span>
    </legend>
    <div class="mf-box">
        <div class="mf-item">
            <span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="mta_enable" value="1"<?php if ($group['g_mta_enable'] == '1') echo ' checked="checked"' ?> /></span>
            <label for="fld<?php echo $forum_page['fld_count'] ?>"><?php echo App::$lang['Allow moder topic author'] ?></label>
        </div>
    </div>
</fieldset>