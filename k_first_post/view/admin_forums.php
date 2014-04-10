<div class="main-subhead">
    <h2 class="hn"><span><?php echo App::$lang['First Post Head'] ?></span></h2>
</div>
<div class="main-content main-frm">
    <form method="post" class="frm-form" accept-charset="utf-8" action="<?php echo forum_link(App::$forum_url['admin_forums']) ?>?addfp">
        <div class="hidden">
            <input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link(App::$forum_url['admin_forums']).'?addfp') ?>" />
        </div>
        <div class="sf-set set<?php echo ++App::$forum_page['item_count'] ?>">
            <div class="sf-box select">
                <label for="fld<?php echo ++App::$forum_page['fld_count'] ?>"><span><?php echo App::$lang['First Post Category'] ?></span></label><br />
                <span class="fld-input"><select id="fld<?php echo App::$forum_page['fld_count'] ?>" name="cat_fp_id">
                <?php
                    $cur_category = 0;
                    foreach ($forums as $cur_forum)
                    {
                        if ($cur_forum['cid'] != $cur_category)	// A new category since last iteration?
                        {
                            if ($cur_category)
                                echo "\t\t\t".'</optgroup>'."\n";

                            echo "\t\t\t".'<optgroup label="'.forum_htmlencode($cur_forum['cat_name']).'">'."\n";
                            $cur_category = $cur_forum['cid'];
                        }
                        echo "\t\t\t\t\t\t\t\t".'<option value="'.$cur_forum['fid'].'">'.forum_htmlencode($cur_forum['forum_name']).'</option>'."\n";
                    }
                ?>
                </select></span>
            </div>
        </div>
        <div class="frm-buttons">
            <span class="submit"><input type="submit" value="<?php echo App::$lang['First Post Submit'] ?>" name="fix_first_post"></span>
        </div>
    </form>
</div>