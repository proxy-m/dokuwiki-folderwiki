<?php
/**
 * Template header, included in the main and detail files
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
///
?>
<!-- ********** HEADER ********** -->



    <header class="stylehead">
      <div class="header">
        <?php tpl_includeFile('pageheader.html')?>
        <?php tpl_includeFile('header.html')?>
      </div>

      <ul id="top__nav">
        <?php
	    if(!plugin_isdisabled('npd') && ($npd =& plugin_load('helper', 'npd'))) {
                $npb = $npd->html_new_page_button(true);
                if($npb) {
                    print '<li>' . $npb . '</li>' . DOKU_LF;
                }
            }
            foreach(array('revert', 'edit', 'history', 'subscribe') as $act) {
                ob_start();
                print '<li>';
                if($act == 'revert' && !empty($REV)) {
                    if(tpl_actionlink($act)) {
                        print '</li>' . DOKU_LF;
                        ob_end_flush();
                    } else {
                        ob_end_clean();
                    }
                } else {
                    if(tpl_actionlink($act)) {
                        print '</li>' . DOKU_LF;
                        ob_end_flush();
                    } else {
                        ob_end_clean();
                    }
                }
            }
        ?>
      </ul>

    </header>

<!-- /header -->
