<?php
/**
 * PingoMatiX transport plugins
 * Copyright 2011 Peter Edley <you@yourdomain.com>
 * @author Peter Edley <you@yourdomain.com>
 * 22/8/11
 *
 * PingoMatiX is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * PingoMatiX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * PingoMatiX; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package pingomatix
 */
/**
 * Description:  Array of plugin objects for PingoMatiX package
 * @package pingomatix
 * @subpackage build
 */

if (! function_exists('getPluginContent')) {
    function getpluginContent($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<?php','',$o);
        $o = str_replace('?>','',$o);
        $o = trim($o);
        return $o;
    }
}
$plugins = array();

$plugins[1]= $modx->newObject('modplugin');
$plugins[1]->fromArray(array(
    'id' => 1,
    'name' => 'pingomatix',
    'description' => 'PingoMatiX. A plug used to ping pingomatic on new page setup.',
    'plugincode' => getPluginContent($sources['source_core'].'/elements/plugins/pingomatix.plugin.php'),
),'',true,true);

return $plugins;