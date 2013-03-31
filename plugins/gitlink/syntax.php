<?php
/**
 * GIT commit link for gitweb plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Daniel-Constantin Mierla <miconda at gmail dot com>
 */
 
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

//-----------------------------------CONFIGURE GITWEB ROOT HERE---------
global $gitweb_root_url;
$gitweb_root_url = "http://yourgitweb.com/cgi-bin/gitweb.cgi/project/";
//----------------------------------------------------------------------
 

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_gitlink extends DokuWiki_Syntax_Plugin {
 
    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Daniel-Constantin Mierla',
            'email'  => 'miconda@gmail.com',
            'date'   => '2009-07-30',
            'name'   => 'GITlink Plugin',
            'desc'   => 'Enables links to GIT commits via gitweb',
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
        $this->Lexer->addSpecialPattern('GIT#[0-9a-fA-F]{6,40}',$mode,'plugin_gitlink');
        $this->Lexer->addSpecialPattern('GIT\?[0-9a-fA-F]{6,40}',$mode,'plugin_gitlink');
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
        global $gitweb_root_url;
		$iframe = false;
        if($mode == 'xhtml'){
            $gitv = explode('#', $data[0]);
			if(count($gitv) < 2) {
				$iframe = true;
                $gitv = explode('?', $data[0]);
            }
            $url = $gitweb_root_url."?a=commit&h=".$gitv[1];
            if($iframe) {
                $w = "75%";
                $h = "400px";
                $renderer->doc .= '<iframe title="GIT '.$gitv[1].'" src="'.$url.'" style="width:'.$w.'; height: '.$h.';">GIT '.$gitv[1].'</iframe>';                
            } else {
                $renderer->doc .= "<a href=\"".$url."\">GIT ".$gitv[1]."</a>";
            }
            return true;
        }
        return false;
    }
     
}
 
//Setup VIM: ex: et ts=4 enc=utf-8 :
?>
