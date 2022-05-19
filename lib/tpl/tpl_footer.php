<?php
/**
 * Template footer, included in the main and detail files
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
//echo p_render('xhtml', p_get_instructions('[[..:|↰..PARENT]]'), $info);
//echo p_render('xhtml', p_get_instructions('~~SUBPAGES~~'), $info);
?>




<!-- ********** FOOTER ********** -->
<div id="dokuwiki__footer"><div class="pad">
    <?php tpl_license(''); // license text ?>

    <div class="buttons">
        <?php
            tpl_license('button', true, false, false); // license button, no wrapper
            $target = ($conf['target']['extern']) ? 'target="'.$conf['target']['extern'].'"' : '';
        ?>
        <a href="https://www.dokuwiki.org/donate" title="Donate" <?php echo $target?>><img
            src="<?php echo tpl_basedir(); ?>images/button-donate.gif" width="80" height="15" alt="Donate" /></a>
        <a href="https://php.net" title="Powered by PHP" <?php echo $target?>><img
            src="<?php echo tpl_basedir(); ?>images/button-php.gif" width="80" height="15" alt="Powered by PHP" /></a>
        <a href="//validator.w3.org/check/referer" title="Valid HTML5" <?php echo $target?>><img
            src="<?php echo tpl_basedir(); ?>images/button-html5.png" width="80" height="15" alt="Valid HTML5" /></a>
        <a target="_blank" href="<?php echo DOKU_BASE?>feed.php" title="Recent changes RSS feed"><img src="<?php echo DOKU_TPL?>images/button-rss.png" width="80" height="15" alt="Recent changes RSS feed" /></a>
        <a href="//jigsaw.w3.org/css-validator/check/referer?profile=css3" title="Valid CSS" <?php echo $target?>><img
            src="<?php echo tpl_basedir(); ?>images/button-css.png" width="80" height="15" alt="Valid CSS" /></a>
        <a href="https://dokuwiki.org/" title="Driven by DokuWiki" <?php echo $target?>><img
            src="<?php echo tpl_basedir(); ?>images/button-dw.png" width="80" height="15"
            alt="Driven by DokuWiki" /></a>
            
<!--cy-pr.com--><a href="http://www.cy-pr.com/" target="_blank"><img src="http://www.cy-pr.com/e/theylied.info_4_107.138.206.gif" border="0" width="88" height="15" alt="Анализ сайта" /></a><!--cy-pr.com-->
            
<!--LiveInternet counter--><a href="https://www.liveinternet.ru/click"
target="_blank"><img id="licnt9C13" width="88" height="31" style="border:0" 
title="LiveInternet: number of pageviews and visitors for 24 hours is shown"
src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAEALAAAAAABAAEAAAIBTAA7"
alt=""/></a><script>(function(d,s){d.getElementById("licnt9C13").src=
"https://counter.yadro.ru/hit?t53.6;r"+escape(d.referrer)+
((typeof(s)=="undefined")?"":";s"+s.width+"*"+s.height+"*"+
(s.colorDepth?s.colorDepth:s.pixelDepth))+";u"+escape(d.URL)+
";h"+escape(d.title.substring(0,150))+";"+Math.random()})
(document,screen)</script><!--/LiveInternet-->

    </div>
</div></div><!-- /footer -->

<div class="litres_fragment_body" style="width: 100%; height: auto;"></div><script type="text/javascript">var litres_fragment_book_view_id = 48411247;var litres_fragment_lfrom = 895231738;var litres_minheight = 500;(function(d, t){var lw = d.createElement(t),s = d.getElementsByTagName(t)[0];lw.async = 1;lw.src = "https://www.litres.ru/static/widgets/js/fragment.js";s.parentNode.insertBefore(lw, s);})(document, "script");</script>

<?php
tpl_includeFile('footer.html');
