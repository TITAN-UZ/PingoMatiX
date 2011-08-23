<?php
/**
* Template variable objects for the PingoMatiXs package
* @author Peter Edley <you@yourdomain.com>
* 22/8/11
*
* @package pingomatixs
* @subpackage build
*/

/* Common 'type' options:
 * textfield (text line)
 * textbox
 * richtext
 * textarea
 * textareamini
 * email
 * html
 * image
 * date
 * option (radio buttons)
 * listbox
 * listbox-multiple
 * number
 * checkbox
 * tag
 * hidden
 */

/* Example template variables */

$templateVariables = array();

$templateVariables[1]= $modx->newObject('modTemplateVar');
$templateVariables[1]->fromArray(array(
    'id' => 1,
    'type' => 'checkbox',
    'name' => 'Ping',
    'caption' => 'Ping pingomatic',
    'description' => 'Use this to use pingomatic to inform sites of this pages creation',
    'display' => 'default',
    'elements' => '==true',  /* input option values */
    'locked' => 0,
    'rank' => 0,
    'display_params' => '',
    'default_text' => 'false',
    'properties' => array(),
),'',true,true);

return $templateVariables;
