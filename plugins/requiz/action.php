<?php
/**
 * REQUIZ antispam plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Daniel-Constantin Mierla <miconda@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');

class action_plugin_requiz extends DokuWiki_Action_Plugin {

    /**
     * register the eventhandlers
     */
    function register(&$controller){
        $controller->register_hook('ACTION_ACT_PREPROCESS',
                                   'BEFORE',
                                   $this,
                                   'handle_act_preprocess',
                                   array());

        // old hook
        $controller->register_hook('HTML_EDITFORM_INJECTION',
                                   'BEFORE',
                                   $this,
                                   'handle_editform_output',
                                   array('editform' => true, 'oldhook' => true));

        // new hook
        $controller->register_hook('HTML_EDITFORM_OUTPUT',
                                   'BEFORE',
                                   $this,
                                   'handle_editform_output',
                                   array('editform' => true, 'oldhook' => false));

        if($this->getConf('requizreg')){
            // old hook
            $controller->register_hook('HTML_REGISTERFORM_INJECTION',
                                       'BEFORE',
                                       $this,
                                       'handle_editform_output',
                                       array('editform' => false, 'oldhook' => true));

            // new hook
            $controller->register_hook('HTML_REGISTERFORM_OUTPUT',
                                       'BEFORE',
                                       $this,
                                       'handle_editform_output',
                                       array('editform' => false, 'oldhook' => false));
        }
    }

    /**
     * Will intercept the 'save' action and check for REQUIZ first.
     */
    function handle_act_preprocess(&$event, $param){
        $act = $this->_act_clean($event->data);
        if(!('save' == $act || ($this->getConf('requizreg') &&
                                'register' == $act &&
                                $_POST['save']))){
            return; // nothing to do for us
        }

        // do nothing if logged in user and no REQUIZ required
        if(!$this->getConf('requizusr') && $_SERVER['REMOTE_USER']){
            return;
        }

        // check requiz
        $helper = plugin_load('helper','requiz');
        if(!$helper->check()){
                if($act == 'save'){
                    // stay in preview mode
                    $event->data = 'preview';
                }else{
                    // stay in register mode, but disable the save parameter
                    $_POST['save'] = false;
                }
        }
    }

    /**
     * Create the additional fields for the edit form
     */
    function handle_editform_output(&$event, $param){
        // check if source view -> no requiz needed
        if(!$param['oldhook']){
            // get position of submit button
            $pos = $event->data->findElementByAttribute('type','submit');
            if(!$pos) return; // no button -> source view mode
        }elseif($param['editform'] && !$event->data['writable']){
            if($param['editform'] && !$event->data['writable']) return;
        }

        // do nothing if logged in user and no REQUIZ required
        if(!$this->getConf('requizusr') && $_SERVER['REMOTE_USER']){
            return;
        }

        // get the REQUIZ
        $helper = plugin_load('helper','requiz');
        $out = $helper->getHTML();

        if($param['oldhook']){
            // old wiki - just print
            echo $out;
        }else{
            // new wiki - insert at correct position
            $event->data->insertElement($pos++,$out);
        }
    }

    /**
     * Pre-Sanitize the action command
     *
     * Similar to act_clean in action.php but simplified and without
     * error messages
     */
    function _act_clean($act){
         // check if the action was given as array key
         if(is_array($act)){
           list($act) = array_keys($act);
         }

         //remove all bad chars
         $act = strtolower($act);
         $act = preg_replace('/[^a-z_]+/','',$act);

         return $act;
     }

}

