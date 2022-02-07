<?php
/**
 * DokuWiki Template DokuBook
 *
 * This is the template you need to change for the overall look
 * of DokuWiki.
 *
 * You should leave the doctype at the very top - It should
 * always be the very first line of a document.
 *
 * @link   http://wiki.splitbrain.org/template:dokubook
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Michael Klier <chi@chimeric.de>
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
require_once('tpl_functions.php');
global $REV;
global $ACT;

?>
<!DOCTYPE html>
<html lang="<?php echo $conf['lang']?>" id="document" dir="<?php echo $lang['direction']?>">
<head<?php if (tpl_getConf('opengraphheading')) { ?> prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# fb: http://ogp.me/ns/fb# place: http://ogp.me/ns/place# book: http://ogp.me/ns/book#"<?php } ?>>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
  </title>

  <?php tpl_metaheaders()?>

  <?php echo tpl_favicon()?>

  <?php tpl_includeFile('meta.html')?>

  <!-- change link borders dynamically -->
  <style type="text/css">
    <?php 
    if($ACT == 'show' || $ACT == 'edit') { 
        if($ACT == 'show' && $INFO['ismanager'] && actionOK('revert') && !empty($REV)) {
    ?>
        div.dokuwiki ul#top__nav a.revert
    <?php 
        } else {
    ?>
        div.dokuwiki ul#top__nav a.edit,
        div.dokuwiki ul#top__nav a.show,
        div.dokuwiki ul#top__nav a.source,
        div.dokuwiki ul#top__nav a.restore
    <?php 
        }
    } else { ?>
        div.dokuwiki ul#top__nav a.<?php echo $ACT;?>
    <?php } ?>
    {
      border-color: #fabd23;
      border-bottom: 1px solid #fff;
      font-weight: bold;
    }
  </style>
</head>
<body class="<?php echo $ACT ?>">
<?php tpl_includeFile('topheader.html')?>
<div class="dokuwiki site">
  <?php html_msgarea()?>

  <?php include(__DIR__ . '/../tpl_leftmenu.php') ?>

  <div id="dokubook_container_<?php echo tpl_getConf('sb_position')?>">
        <?php include(__DIR__ . '/../tpl_header.php') ?>


    <?php flush()?>

    <main class="page">

      <?php if($conf['breadcrumbs']){?>
      <div class="breadcrumbs">
        <?php tpl_breadcrumbs()?>
      </div>
      <?php }?>

      <?php if($conf['youarehere']){?>
      <div class="breadcrumbs">
        <?php tpl_youarehere() ?>
      </div>
      <?php }?>

      <!-- wikipage start -->
      <?php tpl_content()?>
      <!-- wikipage stop -->

      <div class="meta">
        <div class="doc">
          <?php tpl_pageinfo()?>
        </div>
      </div>

      <?php tpl_actionlink('top')?>

      <div class="clearer"></div>

    </main>

    <?php flush()?>

    <div class="clearer"></div>

    <?php dokubook_tpl_footer() ?>

    <footer class="stylefoot">
      <?php tpl_includeFile('pagefooter.html')?>
    </footer>

    <?php tpl_includeFile('footer.html')?>
        <?php include(__DIR__ . '/../tpl_footer.php') ?>
  </div>

</div>
<div class="no"><?php /* provide DokuWiki housekeeping, required in all templates */ tpl_indexerWebBug()?></div>
</body>
</html>
