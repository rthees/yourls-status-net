<?php
/**
 * StatusNet, the distributed open-source microblogging tool
 *
 * Plugin to push RSS/Atom updates to a PubSubHubBub hub
 *
 * PHP version 5
 *
 * LICENCE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * The plugin is based on the UrlShortenerPlugin and its sub-plugins of Craig Andrews
 * 
 * @category  Plugin
 * @package   StatusNet
 * @author    Ralf Thees <ralf@wuerzblog.de>, Craig Andrews <candrews@integralblue.com>
 * @copyright 2010, Ralf Thees http://wuerzblog.de, 2009 Craig Andrews http://candrews.integralblue.com
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link      http://status.net/
 */

if (!defined('STATUSNET') && !defined('LACONICA')) {
    exit(1);
}

require_once INSTALLDIR.'/plugins/UrlShortener/UrlShortenerPlugin.php';

class WueInPlugin extends UrlShortenerPlugin
{
    public $serviceUrl;

    function onInitializePlugin(){
        parent::onInitializePlugin();
        if(!isset($this->serviceUrl)){
            throw new Exception("must specify a serviceUrl");
        }
    }

    protected function shorten($url) {
		$response = $this->http_get(sprintf($this->serviceUrl,urlencode($url)));
        if(!$response) return;
        return json_decode($response)->shorturl;
    }

    function onPluginVersion(&$versions)
    {
        $versions[] = array('name' => sprintf('YOURLS (%s)', $this->shortenerName),
                            'version' => STATUSNET_VERSION,
                            'author' => 'Ralf Thees',
                            'homepage' => 'http://wuerzblog.de',
                            'rawdescription' =>
                            sprintf(_m('Uses <a href="http://%1$s/">%1$s</a> URL-shortener service.'),
                                    $this->shortenerName));

        return true;
    }
}

