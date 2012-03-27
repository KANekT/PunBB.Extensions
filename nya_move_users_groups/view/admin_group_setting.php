<div class="content-head">
    <h3 class="hn">
        <span><?php echo App::$lang['Allow move user to group legend'] ?></span>
    </h3>
</div>
<fieldset class="frm-group group<?php echo ++App::$forum_page['group_count'] ?>">
    <legend class="group-legend">
        <span><?php echo App::$lang['Allow move user to group legend'] ?></span>
    </legend>
    <div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
        <div class="sf-box text">
            <label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['Min post label'] ?></span> <small><?php echo App::$lang['Min post help'] ?></small></label><br />
            <span class="fld-input"><input type="text" id="fld<?php echo App::$forum_page['fld_count'] ?>" name="mug_count" size="5" maxlength="4" value="<?php echo $group['g_mug_count'] ?>" /></span>
        </div>
    </div>
    <div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
        <div class="sf-box checkbox">

            <label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><?php echo App::$lang['Allow move user to group'] ?></label><br />
            <span class="fld-input"><input type="checkbox" id="fld<?php echo ++App::$forum_page['fld_count'] ?>" name="mug_enable" value="1"<?php if ($group['g_mug_enable'] == '1') echo ' checked="checked"' ?> /></span>
        </div>
    </div>
</fieldset>