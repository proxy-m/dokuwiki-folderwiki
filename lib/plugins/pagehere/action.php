<?php
/**
 * DokuWiki Plugin pagehere (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <gohr@cosmocode.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'action.php';

class action_plugin_pagehere extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
       $controller->register_hook('DOKUWIKI_STARTED', 'AFTER', $this, 'handle_dokuwiki_started');
    }
    
    private function fixSpaces ($page) {
        $page = str_replace(array(' ', '%20'/*пробел*/, "\t"/*табуляция*/, "\r"/*возврат*/, "\n"/*перенос*/, "_", "֊", "̄", "¯", "ˉ", "̱", "ˍ"/*подчеркивание, надчеркиваение, макрон, ентамна, перенос, */, "ー"/*тёон*/, "·", "&nbsp;", "‐", "‑", "-"/*дефис*/, "−"/*минус*/, "–", "—", "‒", "―"/*тире*/, ), '-', $page);
        $page = preg_replace("/([-])\\1+/", "$1", $page);
        return $page;
    }
    
    private function fixQuotes ($page) {
        $page = str_replace(array('"', '`', "'", "’", "«", "»", "<", ">", "⟨", "⟩", "(", ")", "[", "]", "{", "}", "„", "”", "『", "』", "「", "」", "‹", "›", "‚", "‘", "“", "”", "◌̏", "״", "֞", "῎", "◌᷾", "˂", "◌ࣷ"/*апострофы, кавычки, скобки*/,), '', $page);
        return $page;
    }
    
    private function fixSlashes ($page) {
        $page = str_replace(array("\\"/*забой*/, "/"/*слеш*/,), '-', $page);
        return $page;
    }

    private function fixLowerCase ($page) {
        $page = strtolower($page);
        return $page;
    }

    public function handle_dokuwiki_started(Doku_Event &$event, $param) {
        if(!$_REQUEST['pagehere']) return;

        global $ID;
        global $conf;
        
        $page = $_REQUEST['pagehere'];
        
        $page = $this->fixSpaces($page);
        $page = $this->fixQuotes($page);

        $page = cleanID($page);
        if(!$this->getConf('subns')){
            $page = str_replace(':', $conf['sepchar'], $page);
            $page = $this->fixSlashes($page); // it is different
            $page = $this->fixSpaces($page);
            $page = $this->fixLowerCase($page);
        }

        $ns = getNS($ID);
        $newpage = cleanID($ns.':'.$page);
        
        if (substr($newpage, -1) !== ':' && substr($newpage, -(1+strlen($config['start']))) !== (':'.$config['start'])) { // eding slash
			$newpage .= ':'.$config['start'];
		}

        send_redirect(wl($newpage,array('do'=>'edit'),true,'&'));
    }

}

// vim:ts=4:sw=4:et:
