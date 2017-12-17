<?php
define("IN_MYBB", 1);
define('THIS_SCRIPT', 'tsonline.php');

require_once "./global.php";
require_once("inc/plugins/ts3func/ts3admin.class.php");
$lang->load("ts3func");

add_breadcrumb($lang->ts3func_statsboard_topic, "tsonline.php");


$userlist = '';

$query = new ts3admin($mybb->settings['ts3func_host'], $mybb->settings['ts3func_portquery']);
if($query->getElement('success', $query->connect())) 
{
	$query->login($mybb->settings['ts3func_username'], $mybb->settings['ts3func_password']);
	$query->selectServer($mybb->settings['ts3func_port']);

	$users = $query->getElement('data',$query->clientList('-groups -voice -away -times'));

	foreach ($users as $client)
	{
		$isserveradmin = strpos($client['client_nickname'], 'serveradmin');
    	if($isserveradmin === false)
    	{
    		$info = $query->getElement('data',$query->clientInfo($client['clid']));

    		$client_connectiontime = floor($info['connection_connected_time'] / 1000 / 60);

    		if($info['client_idle_time'] > $mybb->settings['ts3func_afktime'] * 60 * 1000)
    		{
    			$afkTime = floor($info['client_idle_time'] / 60 / 1000);
    			$status = "<font color=\"orange\"><strong>AFK</strong></font> <span class=\"smalltext\" style=\"color: grey;\">({$lang->ts3func_from} ".$afkTime." {$lang->ts3func_minutes})</span>";
    		}
    		else
    		{
    			$status = "<font color=\"green\"><strong>Online</strong></font>";
    		}

    		$channelInfo = $query->getElement('data', $query->channelInfo($info['cid']));

    		if($channelInfo['channel_maxclients'] == 0)
    			$channelIcon = "16x16_channel_red";
    		else if(!empty($channelInfo['channel_password']))
    			$channelIcon = "16x16_channel_yellow";
    		else
    			$channelIcon = "16x16_channel_green";

    		$channelDesc = "";
    		if(!empty($channelInfo['channel_topic']))
    			$channelDesc = " <span class=\"smalltext\" style=\"color: grey;\">(".$channelInfo['channel_topic'].")</span>";

    		$client_channel = "<img src=\"inc/plugins/ts3func/images/".$channelIcon.".png\">".$channelInfo['channel_name'].$channelDesc;

    		$userlist .= '

    		<tr>
				<td class="trow1">
					<strong><em>'.$info['client_nickname'].'</em></strong></span>
				</td>
				<td align="center" class="trow1">
					'.$client_connectiontime.' '.$lang->ts3func_minutes.'
				</td>
				<td align="center" class="trow1">
					'.$status.'
				</td>
				<td class="trow1" width="50%">
					'.$client_channel.'
				</td>
			</tr>

    		';
    	}
	}
}


eval("\$page = \"".$templates->get("ts3func_userlist")."\";");

output_page($page);

?>