<?php
//	Last Change 2021-01-03

if(!defined('DOKU_INC')) {
    define ('DOKU_INC', realpath(dirname(__FILE__).'/../../').'/');
}
if(!defined('DOKU_PLUGIN')) {
    define ('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
}

require_once (DOKU_PLUGIN.'syntax.php');
require_once (DOKU_INC.'inc/search.php');
require_once (DOKU_INC.'inc/pageutils.php');

class syntax_plugin_subpages extends DokuWiki_Syntax_Plugin {
    var $debug = false;
    var $pages = Array();
    var $subpages = Array();
    var $start = "start";
    var $useheading = 0;
    var $datadir = "";

    /**
     * Constructor
     */
    function syntax_plugin_subpages() {
        global $conf;

//         if($conf ["allowdebug"] == 1)
//             $this->debug = true;

        $this->start		= $conf['start'];
        $this->useheading	= $conf['useheading'];
        $this->datadir		= $conf['datadir'];
		//$this->style   = $this->getConf("style");
    }

    /**
     * return some info
     */
    function getInfo() {
    }

    /**
     * What kind of syntax are we?
     */
    function getType() {
        return "substition";
    }

    /**
     * Just before build in links
     */
    function getSort() {
        return 299;
    }

    /**
     * Register the ~~SUBPAGES~~ verb
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~SUBPAGES~~', $mode, 'plugin_subpages');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler) {
        return preg_replace("%~~SUBPAGES~~%", "\\2", $match);
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        $this->rdr         =& $renderer;
        $this->rdrMode     = $mode;

		$data = $this->_listSubpages($data);

		$this->_put($data);

        return true;
    }
 
     /**
      * Put a debug message on screen...
      */
    function _showDebugMsg($msg) {
        if(!$this->debug) return;

        if(is_array($msg)) {
            foreach($msg as $index => $m) {
                $this->_showDebugMsg("Array [$index]: ".$m);
            }
            return;
        }

        echo DOKU_LF."<span style='color:red;'>SUBPAGES_PLUGIN: ".hsc($msg)."</span><br>";
    }

    /**
     * Write data to the output stream
     */
    function _put($data) {
        if($data == NULL || $data == '')
            return;

        switch($this->rdrMode) {
            case 'xhtml':
                $this->rdr->doc .= $data;
                break;
            case 'latex':
                $this->rdr->put($data);
                break;
        }
    }

    /**
     * Get the namespace of the parent directory
     * (always prefixed and postfixed with a colon, root is ':')
     */
    function _getParentNS($id) {
        // global $ID ;
        $curNS = getNS($id);

        if($curNS == '') return ':';

        if(substr($curNS, 0, 1) != ':') {
            $curNS = ':'.$curNS;
        }

        return $curNS.':';
    }

    /**
     * Create a fully qualified namespace from the specified one.
     * The second parameter must be true when the given namespace
     * is never a page id. In that case, the returned namespace
     * always ends with a colon.
     */
    function _getFqidOfNS($ns, $mustBeNSnoPage) {
        global $ID;

        if(substr($ns, 0, 2) == '.:') {
            $ns = ':'.getNS($ID).substr($ns, 1);
        } elseif(substr($ns, 0, 3) == '..:') {
            $ns = $this->_getParentNS($ID).substr($ns, 3);
        } elseif($ns == '..') {
            $ns = $this->_getParentNS($ID);
        } elseif(substr($ns, 0, 1) == ':') {
        } elseif($ns == '.' || $ns == '*') {
            $ns = ':'.getNS($ID);
        } else {
            $ns = ':'.getNS($ID).':'.$ns;
        }

        if($mustBeNSnoPage && substr($ns, -1) <> ':') $ns .= ':';

        return $ns;
    }

    /**
     * Convert namespace to its path
     */
    function _getPathOfNS($ns) {
        if($ns == ':' || $ns == '') return $this->datadir;
        $ns = trim($ns, ':');
        $path = $this->datadir.'/'.utf8_encodeFN(str_replace(':', '/', $ns));
        return $path;
    }

    /**
     * Get title of page
     */
	function _getPageTitle($fqid) {
		if ($this->useheading == 1) {
            $p = p_get_first_heading($fqid);
        }
        if(!empty($p)) return $p;

        $p = noNS($fqid);
        if ($p == $this->start || $p == false) {
            $p = noNS(getNS($fqid));
            if ($p == false) {
                return $this->start;
            }
        }
        return $p;
    }

    /**
     * Receive the search result and add it to pages-array
     */
	function _searchCallback(&$data, $base, $file, $type, $level, $opts) {
		$this->_showDebugMsg('Entering '.__FUNCTION__);
		global $ID;

		if ($type == 'd') {
			// subdirectory
			$chkfile = $file.'/'.$this->start.'.txt';
			$pgid = pathID($chkfile);
			$fqid = $opts['ns'].$pgid;
			if (auth_quickaclcheck($pgid) > AUTH_NONE) {
				$data['title']	= $this->_getPageTitle($fqid);
				$data['linkid']	= $fqid;
				$data['type']	= $type;
				$data['level']	= $level;
			}
			else
				return false; // no access
		}
		else {
			// page in current directory
			$pgid = pathID($file);
			$fqid = $opts['ns'].$pgid;
			if ($fqid != ':'.$ID) {
				if (auth_quickaclcheck($pgid) > AUTH_NONE) {
					$data['title']	= $this->_getPageTitle($fqid);
					$data['linkid']	= $fqid;
					$data['type']	= $type;
					$data['level']	= $level;
				}
				else
					return false; // no access
			}
			else
				return false; // don't display current page
		}

		array_push($this->pages, $data);
		$this->_showDebugMsg('Leaving '.__FUNCTION__);
	}

    /**
     * Search all pages below the current one
     */
	function _listSubpages($data) {
		$this->_showDebugMsg('Entering '.__FUNCTION__);

		$ns    = '.';
        $ns    = $this->_getFqidOfNS($ns, true);
        $path  = $this->_getPathOfNS($ns);

        //
        // Search the directory $dir, only if the pages array
        // is empty, since we can pass here several times (xhtml, latex).
        //
        $this->_showDebugMsg("Namespace is  $ns");
        $this->_showDebugMsg("Search dir is $path");

        if (count($this->pages) == 0) {
        	// https://xref.dokuwiki.org/reference/dokuwiki/////nav.html?inc/Search/index.html
            $dummy = array();
			search($dummy, $path, array($this, '_searchCallback'), array('ns' => $ns),'',1,'natural');

			//echo highlight_string(print_r($this->pages, true));
			//asort($this->pages);
			sort($this->pages);
			//echo highlight_string(print_r($this->pages, true));
        }
        $count = count($this->pages);

        //$this->_showDebugMsg("Found ".$count." pages!");
        //foreach ($this->pages as $page) {
        //	$this->_showDebugMsg($page);
        //}

		if (count($this->pages) > 0) {
			$ret  = '<ul class="subpages">';
	        foreach ($this->pages as $page) {
	        	//$this->_showDebugMsg($page);
        		$ret .= '<li>'.html_wikilink($page['linkid'], $page['title']).'</li>';
	        }
			$ret .= '</ul>';
		}
		else
			$ret = '';

		$this->_showDebugMsg('Leaving '.__FUNCTION__);
		return $ret;
	}

} // syntax_plugin_subpages
