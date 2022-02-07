<?php
/**
 * Template footer, included in the main and detail files
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();





/**
 * generates the sidebar contents
 *
 * @author Michael Klier <chi@chimeric.de>
 */
function dokubook_tpl_sidebar() {
    global $lang;
    global $ID;
    global $INFO;
    
    global $conf;
    global $ACT;
    
    $hasSidebar = page_findnearest($conf['sidebar']);
    $showSidebar = $hasSidebar && ($ACT=='show');
    
    $ID = cleanID(getID());
    $normalStart = urlencode(urldecode($conf['start']));
    $urlDirPrefix = dirname($_SERVER['PHP_SELF']);
    $tmpFullName = urldecode($ID);
    $tmpDirName = urldecode(getNS($tmpFullName));
    $tmpBaseName = urldecode(noNS($ID));
    

    $svID  = cleanID($ID);
    $navpn = tpl_getConf('sb_pagename');
    $path  = explode(':',$svID);
    $found = false;
    $sb    = '';

?>
<div id="dokuwiki__header"><div class="pad group">
<?php

    print '<aside id="logologo" class="sidebar_box">' . DOKU_LF;
?>
    <div class="headings group">
        <ul class="a11y skip">
            <li><a href="#dokuwiki__content"><?php echo $lang['skip_to_content']; ?></a></li>
        </ul>

        <h5><div style="display: block; margin-left: auto; margin-right: auto;" class="logo"><?php
            // get logo either out of the template images folder or data/media folder
            $logoSize = array();
            $logo = tpl_getMediaFile(array(':wiki:logo.png', ':logo.png', 'images/logo.png'), false, $logoSize);

            // display logo and wiki title in a link to the home page
            tpl_link(
                '/',
                '<img style="float: right;" src="'.$logo.'" '.$logoSize[3].' alt="" />',
                'accesskey="h" title="[H]"'
            );
            tpl_link(
                wl(),
                ' <h5 style="float: right;">'.$conf['title'].'</h5>',
                'accesskey="h" title="[H]"'
            );
        ?></div></h5>
        <?php if ($conf['tagline']): ?>
            <p class="claim"><?php echo $conf['tagline']; ?></p>
        <?php endif ?>
        
        <?php echo p_render('xhtml', p_get_instructions('{{pagehere}}'), $info); ?>
    </div>
<?php
	print '</aside>' . DOKU_LF;

    if(tpl_getConf('closedwiki') && empty($INFO['userinfo'])) {
        print '<span class="sb_label">' . $lang['toolbox'] . '</span>' . DOKU_LF;
        print '<aside id="toolbox" class="sidebar_box">' . DOKU_LF;
        tpl_actionlink('login');
        print '</aside>' . DOKU_LF;
        return;
    }

    // main navigation
    print '<span class="sb_label">' . $lang['navigation'] . '</span>' . DOKU_LF;
    print '<aside id="navigation" class="sidebar_box">' . DOKU_LF;

//    while(!$found && count($path) > 0) { ///
//        $sb = implode(':', $path) . ':' . $navpn;
//        $found =  @file_exists(wikiFN($sb));
//        array_pop($path);
//    }
//
//    if(!$found && @file_exists(wikiFN($navpn))) $sb = $navpn;
//
//    if(@file_exists(wikiFN($sb)) && auth_quickaclcheck($sb) >= AUTH_READ) {
//        print p_dokubook_xhtml($sb);
//    } else {
//        print p_index_xhtml(cleanID($svID));
//
//    } ///
?>
    <?php if($showSidebar): ?>
		<!-- ********** ASIDE ********** -->
		<!--<div id="dokuwiki__aside"><div class="pad aside include group"> -->
			<h3 class="toggle"><?php echo $lang['sidebar'] ?></h3>
			<div class="content"><div class="group">
				<?php tpl_flush() ?>
				<?php tpl_includeFile('sidebarheader.html') ?>
				<?php echo p_render('xhtml', p_get_instructions('[[..:|↰..PARENT]]'), $info); ?>
				<?php tpl_include_page($conf['sidebar'], true, true) ?>
				<?php tpl_includeFile('sidebarfooter.html') ?>
			</div></div>
		<!--</div></div> /aside -->
	<?php endif; ?>
<?php
    print '</aside>' . DOKU_LF;

//    // generate the searchbox
//    print '<span class="sb_label">' . strtolower($lang['btn_search']) . '</span>' . DOKU_LF;
//    print '<div id="search">' . DOKU_LF;
//    tpl_searchform();
//    print '</div>' . DOKU_LF;

    // generate the toolbox
    print '<span class="sb_label">' . $lang['toolbox'] . '</span>' . DOKU_LF;
    print '<aside id="toolbox" class="sidebar_box">' . DOKU_LF;
//    tpl_actionlink('admin');
//    tpl_actionlink('index');
//    tpl_actionlink('media');
//    tpl_actionlink('recent');
//    tpl_actionlink('backlink');
//    tpl_actionlink('profile');
//    tpl_actionlink('login');
?>

    <div class="tools group">
        <!-- USER TOOLS -->
        <?php if ($conf['useacl']): ?>
            <div id="dokuwiki__usertools">
                <h3 class="a11y"><?php echo $lang['user_tools']; ?></h3>
                <ul>
                    <?php
                        if (!empty($_SERVER['REMOTE_USER'])) {
                            echo '<li class="user">';
                            tpl_userinfo(); /* 'Logged in as ...' */
                            echo '</li>';
                            echo (new \dokuwiki\Menu\UserMenu())->getListItems('action ');
                        } else {
                            /////echo '<!--HARDCODE_BEGIN--><li class="action login"><a href="'.wl().'?do=login&amp;sectok=" title="Log In" rel="nofollow"><span>Log In</span></a></li><!--HARDCODE_END-->';
                            echo (new \dokuwiki\Menu\UserMenu())->getListItems('action ');
						}
                    ?>
                </ul>
            </div>
        <?php endif ?>

        <!-- SITE TOOLS -->
        <div id="dokuwiki__sitetools">
            <h3 class="a11y"><?php echo $lang['site_tools']; ?></h3>
            <?php tpl_searchform(); ?>
            <div class="mobileTools">
                <?php echo (new \dokuwiki\Menu\MobileMenu())->getDropdown($lang['tools']); ?>
            </div>
            <ul>
                <?php echo (new \dokuwiki\Menu\SiteMenu())->getListItems('action ', false); ?>
            </ul>
        </div>

    </div>

    <!-- BREADCRUMBS -->
    <?php if($conf['breadcrumbs'] || $conf['youarehere']): ?>
        <div class="breadcrumbs">
            <?php if($conf['youarehere']): ?>
                <div class="youarehere"><?php tpl_youarehere() ?></div>
            <?php endif ?>
            <?php if($conf['breadcrumbs']): ?>
                <div class="trace"><?php tpl_breadcrumbs() ?></div>
            <?php endif ?>
        </div>
    <?php endif ?>

    <hr class="a11y" />
</div></div>

<?php
    print '</aside>' . DOKU_LF;

    // restore ID just in case
    $ID = $svID;
}


?>





  <div id="sidebar_<?php echo tpl_getConf('sb_position')?>" class="sidebar">
    <!--php dokubook_tpl_logo()
    
        /** @var helper_plugin_translation $translation */
        $translation = plugin_load('helper','translation');
        if ($translation) echo $translation->showTranslations();
    -->
    <?php dokubook_tpl_sidebar() ?>
    

  </div>

<?php
