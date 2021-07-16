<?php
    /**
     * @author Wikator
     */
    if (!defined('DOKU_INC')) die();
    if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
    require_once (DOKU_PLUGIN . 'action.php');

    class action_plugin_automove extends DokuWiki_Action_Plugin
    {
        function getInfo() {
            return array (
                'author' => 'Wikator',
                'email' => 'zydeco@namedfork.net',
                'date' => '2021-06-29',
                'name' => 'Automove Plugin',
                'desc' => "Move sync convertion page to subnamespace start page (use filesystem hardlinks)",
                'url' => 'https://www.dokuwiki.org/plugin:automove',
            );
        }

        function page_exists($id) {
            if (function_exists('page_exists'))
                return page_exists($id);
            else
                return @file_exists(wikiFN($id));
        }

        function register(Doku_Event_Handler $controller) {
            $controller->register_hook('ACTION_ACT_PREPROCESS', 'AFTER', $this, 'preprocess', array ());
        }

        function preprocess(Doku_Event $event, $param) {
            global $conf;
            $ID = cleanID(getID());
            //echo "IDIDID $ID";
            $normalStart = urlencode(urldecode($conf['start'])); ///'%E2%96%B6st';
            
            $tmpFullName = urldecode($ID);
            $tmpDirName = urldecode(getNS($tmpFullName));
            $tmpBaseName = urldecode(noNS($ID)); ///urldecode(preg_replace('/^'.$tmpDirName.':/', '', $tmpFullName));
            
            $tmpDirName0 = $tmpDirName;
            
            $urlDirPrefix = dirname($_SERVER['PHP_SELF']);
            if (
                 ((!!$this->page_exists($ID) ||  
                   (!$this->page_exists($ID)  && 
                     !!$this->page_exists($tmpDirName))
                    ) && 
                    $event->data == 'show' && 
                    strlen(getNS($ID)) > 0
                 )
               )
            //if (!!$this->page_exists($ID))
            {
                	//echo "page exists $ID";
                	


$tmpFullName = str_replace(':', '/', $tmpFullName);
$tmpDirName = str_replace(':', '/', $tmpDirName);

//echo '--f='.$tmpFullName.'--d='.$tmpDirName.'--b='.$tmpBaseName;

if (urlencode($tmpBaseName) ===  ($normalStart)) {
	$ID = $tmpDirName0;
	
	$tmpFullName = urldecode($ID);
    $tmpDirName = urldecode(getNS($tmpFullName));
    $tmpBaseName = urldecode(noNS($ID));
            
    $tmpFullName = str_replace(':', '/', $tmpFullName);
    $tmpDirName = str_replace(':', '/', $tmpDirName);
}

if (urlencode($tmpBaseName) !==  ($normalStart)) {
 //echo "start no here";
 
 $curDir = trim(shell_exec('pwd'));
 $newDir = $curDir . '/' . 'data/pages/' . $tmpDirName . '/' . $tmpBaseName;
 shell_exec('mkdir -p ' . $newDir);
 
 $oldStart = $curDir . '/' . 'data/pages/' . $tmpDirName . '/' . $tmpBaseName . '.txt';
 $newStart = $curDir . '/' . 'data/pages/' . $tmpDirName . '/' . $tmpBaseName . '/' . $normalStart . '.txt';
 
 //echo '1: '.$oldStart;
 //echo '2:'.$newStart;
 shell_exec('ln -f ' . $newStart . ' ' . $oldStart);
 shell_exec('ln -f ' . $oldStart . ' ' . $newStart);
 
 
 //////echo "<script>window.location.href += '/$normalSart';</script>";
 ///header('Location: ' . "$urlDirPrefix/$tmpDirName/$tmpBaseName/$normalStart"/*wl($id,'',true)*/); ///echo "<script> window.location.reload();</script>";
} else {
	//echo 'norm start: '.$normalStart;
}

            }
        }

    }


