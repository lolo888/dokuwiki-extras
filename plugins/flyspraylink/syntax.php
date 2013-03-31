<?php
/**
 * FlySpray link plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Daniel-Constantin Mierla <miconda at gmail dot com>
 */
 
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

//-----------------------------------CONFIGURE FlySpray ROOT HERE---------
global $fsweb_root_url;
$fsweb_root_url = "http://yourflyspray.com/index.php?do=details";
//----------------------------------------------------------------------
 

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_flyspraylink extends DokuWiki_Syntax_Plugin {
 
    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Daniel-Constantin Mierla',
            'email'  => 'miconda@gmail.com',
            'date'   => '2009-07-30',
            'name'   => 'FlySpray-link Plugin',
            'desc'   => 'Enables links to FlySpray tracker items',
            'url'    => 'http://www.asipto.com/',
        );
    }
 
    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'substition';
    }
 
    /**
     * Where to sort in?
     */
    function getSort(){
        return 812;
    }
 
    /**
     * Connect pattern to lexer
     */
     
    function connectTo($mode) {
        // Word boundaries?
        $this->Lexer->addSpecialPattern('FlySpray#\d+',$mode,'plugin_flyspraylink');
        $this->Lexer->addSpecialPattern('FlySpray\?\d+',$mode,'plugin_flyspraylink');
    }
 
    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
        return array($match, $state);
    }            
 
    /**
     * Create output
     */
	function render($mode, &$renderer, $data) {
        global $fsweb_root_url;
		$iframe = false;
        if($mode == 'xhtml'){
            $fsv = explode('#', $data[0]);
			if(count($fsv) < 2) {
				$iframe = true;
                $fsv = explode('?', $data[0]);
            }
            $url = $fsweb_root_url."&task_id=".$fsv[1];
            if($iframe) {
                $w = "75%";
                $h = "400px";
                $renderer->doc .= '<iframe title="FlySpray '.$fsv[1].'" src="'.$url.'" style="width:'.$w.'; height: '.$h.';">FlySpray '.$fsv[1].'</iframe>';                
            } else {
                $renderer->doc .= "<a href=\"".$url."\">FlySpray ".$fsv[1]."</a>";
            }
            return true;
        }
        return false;
    }
     
}
 
//Setup VIM: ex: et ts=4 enc=utf-8 :
?>
