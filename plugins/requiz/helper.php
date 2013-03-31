<?php
/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Daniel-Constantin Mierla <miconda@gmail.com>
 */
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_INC.'inc/blowfish.php');

class helper_plugin_requiz extends DokuWiki_Plugin {

    /**
     * Check if the REQUIZ should be used. Always check this before using the methods below.
     *
     * @return bool true when the REQUIZ should be used
     */
    function isEnabled(){
        if(!$this->getConf('requizusr') && $_SERVER['REMOTE_USER']) return false;
        return true;
    }

    /**
     * Returns the HTML to display the REQUIZ with the chosen method
     */
    function getHTML(){
        global $ID;

		$rand = rand(17,1000000);
		$qset = $this->getConf('requizset');
		$qcnt = count($qset);
	   	$qidx = $rand % $qcnt;
        $secret = PMA_blowfish_encrypt($rand,auth_cookiesalt());

        $out  = '';
        $out .= '<div id="plugin__requiz_wrapper">';
        $out .= '<input type="hidden" name="plugin__requiz_secret" value="'.hsc($secret).'" />';
        $out .= '<br /><b>'.$qset[$qidx]['question'].'</b><br /> ';
        $out .= '<label for="plugin__requiz">'.$this->getLang('fillrequiz').'</label> ';
        $out .= '<select name="plugin__requiz" id="plugin__requiz" class="edit" style="width:200px"> ';
		$out .= '  <option value="--">--</option>';
		$acnt = count($qset[$qidx]['answers']);
		$ilist = range(0, $acnt-1);
		shuffle($ilist);
		foreach ($ilist as $i) {
			$out .= '  <option value="'.$qset[$qidx]['answers'][$i].'">'.$qset[$qidx]['answers'][$i].'</option>';
		}
        $out .= '</select>';
        $out .= '</div><br />';
        return $out;
    }

    /**
     * Checks if the the REQUIZ was solved correctly
     *
     * @param  bool $msg when true, an error will be signalled through the msg() method
     * @return bool true when the answer was correct, otherwise false
     */
    function check($msg=true){
        // compare provided string with decrypted requiz
        $rand = PMA_blowfish_decrypt($_REQUEST['plugin__requiz_secret'],auth_cookiesalt());
		$qset = $this->getConf('requizset');
		$qcnt = count($qset);
	   	$qidx = $rand % $qcnt;

        if(!$_REQUEST['plugin__requiz_secret'] ||
           !$_REQUEST['plugin__requiz'] ||
           $_REQUEST['plugin__requiz'] != $qset[$qidx]['valid']){
            if($msg) msg($this->getLang('testfailed'),-1);
            return false;
        }
        return true;
    }

    /**
     * Build a semi-secret fixed string identifying the current page and user
     *
     * This string is always the same for the current user when editing the same
     * page revision.
     */
    function _fixedIdent(){
        global $ID;
        $lm = @filemtime(wikiFN($ID));
        return auth_browseruid().
               auth_cookiesalt().
               $ID.$lm;
    }


}
