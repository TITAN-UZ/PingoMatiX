<?php
/**
 * pingomatix
 *
 * Copyright 2011 Peter Edley <peter@cww.org.uk>
 *
 * @author Peter Edley <peter@cww.org.uk>
 * @version Version 1.0.0 Beta-1
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
 * MODx PingoMatix Plugin
 *
 * Description: Allows you to ping Pingomtic which will then ping several sites to inform them of new pages.
 * Events: OnDocFormSave, OnDocPublished
 *
 * @package pingomatix
 *
 * @property
 */

/*function pingGoogleSitemaps( $url_xml )
{
   $status = 0;
   $google = 'www.google.com';
   if( $fp=@fsockopen($google, 80) )
   {
      $req =  'GET /webmasters/sitemaps/ping?sitemap=' .
              urlencode( $url_xml ) . " HTTP/1.1\r\n" .
              "Host: $google\r\n" .
              "User-Agent: Mozilla/5.0 (compatible; " .
              PHP_OS . ") PHP/" . PHP_VERSION . "\r\n" .
              "Connection: Close\r\n\r\n";
      fwrite( $fp, $req );
      while( !feof($fp) )
      {
         if( @preg_match('~^HTTP/\d\.\d (\d+)~i', fgets($fp, 128), $m) )
         {
            $status = intval( $m[1] );
            break;
         }
      }
      fclose( $fp );
   }
   return( $status );
}*/

function pingomatic($title,$url,$debug=false,$modx) {
  /* Code in this function thanks to
  *  <a href="http://www.barattalo.it/2010/02/24/ping-pingomatic-com-services-with-php/" target="_blank" rel="nofollow">http://www.barattalo.it/2010/02/24/ping-pingomatic-com-services-with-php/</a>

  */
    $content='<?xml version="1.0"?>'.
        '<methodCall>'.
        ' <methodName>weblogUpdates.ping</methodName>'.
        '  <params>'.
        '   <param>'.
        '    <value>'.$title.'</value>'.
        '   </param>'.
        '  <param>'.
        '   <value>'.$url.'</value>'.
        '  </param>'.
        ' </params>'.
        '</methodCall>';

    $headers="POST / HTTP/1.0\r\n".
    "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1) Gecko/20090624 Firefox/3.5 (.NET CLR 3.5.30729)\r\n".
    "Host: rpc.pingomatic.com\r\n".
    "Content-Type: text/xml\r\n".
    "Content-length: ".strlen($content);

    if ($debug) nl2br($headers);

    $request=$headers."\r\n\r\n".$content;
    $response = "";
    $fs=fsockopen('rpc.pingomatic.com',80, $errno, $errstr);
    if ($fs) {
        fwrite ($fs, $request);
        while (!feof($fs)) $response .= fgets($fs);
      if ($debug) $modx->log(modX::LOG_LEVEL_DEBUG,'Debug Response :: '.$response);
        fclose ($fs);
        preg_match_all("/<(name|value|boolean|string)>(.*)<\/(name|value|boolean|string)>/U",$response,$ar, PREG_PATTERN_ORDER);
        for($i=0;$i<count($ar[2]);$i++) $ar[2][$i]= strip_tags($ar[2][$i]);
        return array('status'=> ( $ar[2][1]==1 ? 'ko' : 'ok' ), 'msg'=>$ar[2][3] );
    } else {
      if ($debug) $modx->log(modX::LOG_LEVEL_DEBUG,'Debug Response :: '.$errstr.' ('.$errno.')');
        return array('status'=>'ko', 'msg'=>$errstr." (".$errno.")");
    }
}

$notify = false;

$eventName = $modx->event->name;
switch($eventName) {
    case 'OnDocFormSave':
        if ($resource->published==1 && $resource->getTVValue('Ping')){
            $notify = true;
        }
        else {
            $notify = false;
        }
        break;
    case 'OnDocPublished':
        if ($resource->getTVValue('Ping')){
            $notify = true;
        }
        else {
            $notify = false;
        }
        break;
}

if ($notify) {
   /* $url_xml = $modx->config['site_url'].'/sitemap.xml';
    $returncode = pingGoogleSitemaps( $url_xml );
    if (!$returncode == 200) {
         $modx->log(modX::LOG_LEVEL_DEBUG, 'There was an error connecting to the google server it returned the following code '.$returncode);
    } */
    $url = $modx->config['site_url'].$modx->resource->uri;

    $response = pingomatic($modx->resource->pagetitle,$url,false,$modx);

    if ($response['status'] == 'ko') {
         $modx->log(modX::LOG_LEVEL_DEBUG, 'There was an error connecting to the pingomatic site it returned the following message '.$response['msg']);
    }
}