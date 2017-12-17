<?php

/*
        TS3 Functions [v2.0]
      (c) Copyright 2013-2017 by Piotr 'Inferno' Grencel
 
      @author    : Piotr 'Inferno' Grencel
      @website	 : http://github.com/inferno211
      @contact   : inferno.piotr@gmail.com
      @date      : 07-03-2017
      @update    : 17-12-2017

*/

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.");
}

$plugins->add_hook('index_start', 'ts3func_online');
$plugins->add_hook('member_profile_end', 'ts3func_profile');
$plugins->add_hook("postbit", "ts3func_postbit");


function ts3func_info()
{
	global $lang;
	$lang->load('ts3func');

	return array(
		"name"			=> "TS3 Functions",
		"description"	=> $lang->ts3func_desc,
		"website"		=> "http://www.github.com/inferno211",
		"author"		=> "Piotr 'Inferno' Grencel",
		"authorsite"	=> "http://www.github.com/inferno211",
		"version"		=> "2.0",
		"guid" 			=> "",
		"codename"		=> "tsfunc",
		"compatibility" => "18*"
	);
}

function ts3func_install()
{
	global $db, $mybb, $lang;
	$lang->load('ts3func');

	$setting_group = array(
	    'name' => 'ts3func_sg',
	    'title' => 'TS3 Functions',
	    'description' => 'Konfiguracja pluginu TS3 Functions by Inferno.',
	    'disporder' => 5,
	    'isdefault' => 0
	);

	$gid = $db->insert_query("settinggroups", $setting_group);

	$setting_array = array(
	    'ts3func_host' => array(
	        'title' => 'Adres IP',
	        'description' => 'Adres IP serwera:',
	        'optionscode' => 'text',
	        'value' => 'localhost',
	        'disporder' => 1
	    ),
	    'ts3func_portquery' => array(
	        'title' => 'Port query?',
	        'description' => 'Port query serwera:',
	        'optionscode' => 'numeric',
	        'value' => 10011,
	        'disporder' => 2
	    ),
	    'ts3func_port' => array(
	        'title' => 'Port?',
	        'description' => 'Port serwera:',
	        'optionscode' => 'numeric',
	        'value' => 9987,
	        'disporder' => 3
	    ),
	    'ts3func_username' => array(
	        'title' => 'Login?',
	        'description' => 'Login do połączenia query:',
	        'optionscode' => 'text',
	        'value' => 'serveradmin',
	        'disporder' => 4
	    ),
	    'ts3func_password' => array(
	        'title' => 'Hasło?',
	        'description' => 'Hasło do połączenia query:',
	        'optionscode' => 'text',
	        'value' => 'g5iqHinx',
	        'disporder' => 5
	    ),
	    'ts3func_customfield' => array(
	        'title' => 'ID pola z TS3 UID?',
	        'description' => 'ID dodatkowego pola profilu, gdzie użytkownik może podać swoje UID:',
	        'optionscode' => 'numeric',
	        'value' => '4',
	        'disporder' => 6
	    ),
	    'ts3func_afktime' => array(
	        'title' => 'AFK Time?',
	        'description' => 'Jak długo użytkownik musi być nieaktywny by wyświetliło mu status AFK:',
	        'optionscode' => 'numeric',
	        'value' => '4',
	        'disporder' => 7
	    )
	);

	foreach($setting_array as $name => $setting)
	{
	    $setting['name'] = $name;
	    $setting['gid'] = $gid;

	    $db->insert_query('settings', $setting);
	}

	rebuild_settings();

	$template = '
				<tr>
					<td class="tcat">
						<span class="smalltext"><strong>{$lang->ts3func_statsboard_topic}</strong> [<a href="tsonline.php">{$lang->ts3func_statsboard_list}</a>]
					</td>
				</tr>
				<tr>
					<td class="trow1">
						<span class="smalltext">{$ts3func_usersonline_users}</span>
					</td>
				</tr>';

	$insert_array = array(
	    'title' => 'ts3func_usersonline',
	    'template' => $db->escape_string($template),
	    'sid' => '-1',
	    'version' => '',
	    'dateline' => time()
	);
	$db->insert_query('templates', $insert_array);

	$template = '
<br />
<table border="0" cellspacing="0" cellpadding="5" class="tborder tfixed">
	<colgroup>
	<col style="width: 30%;">
	</colgroup>
	<tbody>
		<tr>
			<td colspan="2" class="thead"><strong>{$lang->ts3func_profile}</strong></td>
		</tr>
		<tr>
			<td class="trow1"><strong>{$lang->ts3func_profile_status}:</strong></td>
			<td class="trow1">{$client_online} {$client_icons}</td>
		</tr>
		<tr>
			<td class="trow1"><strong>{$lang->ts3func_profile_login}:</strong></td>
			<td class="trow1">{$client_nickname} <span class="smalltext" style="color: grey;" title="Opis">{$client_description}</span></td>
		</tr>
		<tr>
			<td class="trow1"><strong>{$lang->ts3func_profile_channel}:</strong></td>
			<td class="trow1">{$client_channel}</td>
		</tr>
		<tr>
			<td class="trow1"><strong>{$lang->ts3func_profile_firstconnect}:</strong></td>
			<td class="trow1">{$client_firstconnect}</td>
		</tr>
		<tr>
			<td class="trow1"><strong>{$lang->ts3func_profile_lastconnect}:</strong></td>
			<td class="trow1">{$client_lastconnect} <span class="smalltext" style="color: grey;">({$lang->ts3func_profile_totalconnections}: {$client_totalconnections})</span></td>
		</tr>
		<tr>
			<td class="trow1"><strong>{$lang->ts3func_profile_connectiontime}:</strong></td>
			<td class="trow1">{$client_connectiontime} minut</td>
		</tr>
		<tr>
			<td class="trow1"><strong>{$lang->ts3func_profile_servergroups}:</strong></td>
			<td class="trow1">{$client_servergroups}</td>
		</tr>
	</tbody>
</table>';

	$insert_array = array(
	    'title' => 'ts3func_profile',
	    'template' => $db->escape_string($template),
	    'sid' => '-1',
	    'version' => '',
	    'dateline' => time()
	);
	$db->insert_query('templates', $insert_array);

	$template = '
<br />
<table border="0" cellspacing="0" cellpadding="5" class="tborder tfixed">
	<colgroup>
	<col style="width: 30%;">
	</colgroup>
	<tbody>
		<tr>
			<td class="thead"><strong>Informacje o statusie na TeamSpeak3</strong></td>
		</tr>
		<tr>
			<td class="trow1" style="color: red;"><strong><center>Offline</center></strong></td>
		</tr>
	</tbody>
</table>';

	$insert_array = array(
	    'title' => 'ts3func_profile_offline',
	    'template' => $db->escape_string($template),
	    'sid' => '-1',
	    'version' => '',
	    'dateline' => time()
	);
	$db->insert_query('templates', $insert_array);

	$template = '
<html>
<head>
<title>{$mybb->settings[\'bbname\']}</title>
{$headerinclude}
</head>
<body>
{$header}
<table border="0" cellspacing="0" cellpadding="5" class="tborder">
	<tbody>
		<tr>
			<td class="thead" colspan="4"><strong>{$lang->ts3func_statsboard_topic}</strong></td>
		</tr>
		<tr>
			<td class="tcat" align="center"><span class="smalltext"><strong>{$lang->ts3func_userlist_user}</strong></span></td>
			<td class="tcat" align="center"><span class="smalltext"><strong>{$lang->ts3func_userlist_timeonline}</strong></span></td>
			<td class="tcat" align="center"><span class="smalltext"><strong>{$lang->ts3func_userlist_status}</strong></span></td>
			<td class="tcat" width="50%"><span class="smalltext"><strong>{$lang->ts3func_userlist_channel}</strong></span></td>
		</tr>
		{$userlist}
	</tbody>
</table>
<br class="clear" />
{$footer}
</body>
</html>';

	$insert_array = array(
	    'title' => 'ts3func_userlist',
	    'template' => $db->escape_string($template),
	    'sid' => '-1',
	    'version' => '',
	    'dateline' => time()
	);
	$db->insert_query('templates', $insert_array);

	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";

	find_replace_templatesets(
		"index_boardstats",
		"#" . preg_quote('{$whosonline}') . "#i",
		'{$whosonline}{$ts3func_usersonline}'
	);

	find_replace_templatesets(
		"postbit_classic",
		"#" . preg_quote('{$post[\'user_details\']}') . "#i",
		'{$post[\'user_details\']}<br />{$lang->ts3func_postbitstatus}: {$post[\'ts3func_userstatus\']}'
	);
}

function ts3func_is_installed()
{
	global $mybb;
	if(isset($mybb->settings['ts3func_host']))
	{
	    return true;
	}

	return false;
}

function ts3func_uninstall()
{
	global $db;

	$db->delete_query("settinggroups", "name=\"ts3func_sg\"");
	$db->delete_query("settings", "name LIKE \"ts3func%\"");

	rebuild_settings();

	$db->delete_query("templates", "title LIKE \"ts3func%\"");

	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";

	find_replace_templatesets(
		"postbit_classic",
		"#" . preg_quote('<br />{$lang->ts3func_postbitstatus}: {$post[\'ts3func_userstatus\']}') . "#i",
		''
	);

	find_replace_templatesets(
		"index_boardstats",
		"#" . preg_quote('{$ts3func_usersonline}') . "#i",
		''
	);
}

function ts3func_online()
{
	global $mybb, $lang, $templates, $ts3func_usersonline, $ts3func_usersonline_users;

	require_once("inc/plugins/ts3func/ts3admin.class.php");
	$lang->load('ts3func');

	$query = new ts3admin($mybb->settings['ts3func_host'], $mybb->settings['ts3func_portquery']);
	if($query->getElement('success', $query->connect())) 
	{
		$query->login($mybb->settings['ts3func_username'], $mybb->settings['ts3func_password']);
    	$query->selectServer($mybb->settings['ts3func_port']);

    	$users = $query->getElement('data',$query->clientList('-groups -voice -away -times'));

    	$ts3func_usersonline_users = 'Brak';

    	$first = true;

    	foreach ($users as $client) 
    	{
    		$isserveradmin = strpos($client['client_nickname'], 'serveradmin');
    		if($isserveradmin === false)
    		{
    			if($first == false)
    			{
	    			$uzytkownicy .= ', '.$client['client_nickname'];
	    			$first = false;
	    		}
	    		else
	    		{
	    			$uzytkownicy .= $client['client_nickname'];
	    			$first = false;
	    		}
	    		$ts3func_usersonline_users = $uzytkownicy;
    		}
    	}

		eval('$ts3func_usersonline  = "' . $templates->get('ts3func_usersonline') . '";');
	}
}

function round_down($numberddd, $precisionddd = 2)
{
	$figddd = (int) str_pad('1', $precisionddd, '0');
	return (floor($numberddd * $figddd) / $figddd);
}

function ts3func_profile()
{
	global $mybb, $db, $lang, $templates, $memprofile, $ts3func_profile;
	global $client_nickname, $client_online, $client_description, $client_icons, $client_channel, $client_firstconnect, $client_lastconnect, $client_connectiontime, $client_totalconnections, $client_servergroups;

	$lang->load('ts3func');

	require_once("inc/plugins/ts3func/ts3admin.class.php");
	$query = new ts3admin($mybb->settings['ts3func_host'], $mybb->settings['ts3func_portquery']);
	if($query->getElement('success', $query->connect())) 
	{
		$query->login($mybb->settings['ts3func_username'], $mybb->settings['ts3func_password']);
    	$query->selectServer($mybb->settings['ts3func_port']);

    	$result = $db->simple_select("userfields", "fid".$mybb->settings['ts3func_customfield'], "ufid='".$memprofile['uid']."'", array("limit" => 1));
    	$resultinfo = $db->fetch_array($result);

    	if(is_null($resultinfo["fid".$mybb->settings['ts3func_customfield']]) || strlen($resultinfo["fid".$mybb->settings['ts3func_customfield']]) == 0)
    		return 1;

    	$result = $query->clientGetIds($resultinfo["fid".$mybb->settings['ts3func_customfield']]);

    	if($result['success'] != 1)
    	{
    		eval('$ts3func_profile  = "' . $templates->get('ts3func_profile_offline') . '";');
    	}
    	else
    	{
    		$client_icons = "";

    		$profileInfo = $query->clientInfo($result['data'][0]['clid']);

    		if($profileInfo['data']['client_output_muted'] != 0) $client_icons .= "<img src=\"inc/plugins/ts3func/images/16x16_hardware_output_muted.png\" title=\"{$lang->ts3func_profile_speakernmuted}\">";
    		if($profileInfo['data']['client_input_muted'] != 0) $client_icons .= "<img src=\"inc/plugins/ts3func/images/16x16_hardware_input_muted.png\" title=\"{$lang->ts3func_profile_microfonmuted}\">";
    		if($profileInfo['data']['client_is_recording'] != 0) $client_icons .= "<img src=\"inc/plugins/ts3func/images/16x16_recording_start.png\" title=\"{$lang->ts3func_profile_recording}\">";
    		if($profileInfo['data']['client_away'] != 0) $client_icons .= "<img src=\"inc/plugins/ts3func/images/16x16_away.png\" title=\"".$profileInfo['data']['client_away_message']."\">";
    	
    		$client_nickname = $profileInfo['data']['client_nickname'];
    		$client_description = "";
    		if(!empty($profileInfo['data']['client_description']))
    			$client_description = "(".$profileInfo['data']['client_description'].")";

    		if($profileInfo['data']['client_idle_time'] > $mybb->settings['ts3func_afktime'] * 60 * 1000)
    		{
    			$afkTime = floor($profileInfo['data']['client_idle_time'] / 60 / 1000);
    			$client_online = "<font color=\"orange\"><strong>AFK</strong></font> <span class=\"smalltext\" style=\"color: grey;\">({$lang->ts3func_from} ".$afkTime." {$lang->ts3func_minutes})</span>";
    		}
    		else
    		{
    			$client_online = "<font color=\"green\"><strong>Online</strong></font>";
    		}
    		

    		$channelInfo = $query->channelInfo($profileInfo['data']['cid']);

    		if($channelInfo['data']['channel_maxclients'] == 0)
    			$channelIcon = "16x16_channel_red";
    		else if(!empty($channelInfo['data']['channel_password']))
    			$channelIcon = "16x16_channel_yellow";
    		else
    			$channelIcon = "16x16_channel_green";

    		$channelDesc = "";
    		if(!empty($channelInfo['data']['channel_topic']))
    			$channelDesc = " <span class=\"smalltext\" style=\"color: grey;\">(".$channelInfo['data']['channel_topic'].")</span>";
    		
    		$client_channel = "<img src=\"inc/plugins/ts3func/images/".$channelIcon.".png\">".$channelInfo['data']['channel_name'].$channelDesc;

    		$client_firstconnect = date('Y-m-d H:i', $profileInfo['data']['client_created']);
    		$client_lastconnect = date('Y-m-d H:i', $profileInfo['data']['client_lastconnected']);
    		$client_totalconnections = $profileInfo['data']['client_totalconnections'];

    		$client_connectiontime = floor($profileInfo['data']['connection_connected_time'] / 1000 / 60);


    		$groups = $query->serverGroupsByClientID($profileInfo['data']['client_database_id']);
    		$first = true;
    		foreach ($groups['data'] as $group) {
    			if($first == true) {
    				$client_servergroups .= $group['name'];
    				$first = false;
    			} else {
    				$client_servergroups .= ", ".$group['name'];
    			}
    		}
    		eval('$ts3func_profile  = "' . $templates->get('ts3func_profile') . '";');
    	}
    	
	}
}

function ts3func_postbit(&$post){	
	global $mybb, $db, $lang;

	$lang->load('ts3func');

	$post['ts3func_userstatus'] = '';

	require_once("inc/plugins/ts3func/ts3admin.class.php");
	$query = new ts3admin($mybb->settings['ts3func_host'], $mybb->settings['ts3func_portquery']);
	if($query->getElement('success', $query->connect())) 
	{
		$query->login($mybb->settings['ts3func_username'], $mybb->settings['ts3func_password']);
    	$query->selectServer($mybb->settings['ts3func_port']);

    	$result = $db->simple_select("userfields", "fid".$mybb->settings['ts3func_customfield'], "ufid='".$post['uid']."'", array("limit" => 1));
    	$resultinfo = $db->fetch_array($result);
    	if(!is_null($resultinfo["fid".$mybb->settings['ts3func_customfield']]) || strlen($resultinfo["fid".$mybb->settings['ts3func_customfield']]) != 0)
    	{
    		$result = $query->clientGetIds($resultinfo["fid".$mybb->settings['ts3func_customfield']]);
    		if($result['success'] != 1)
	    	{
	    		$post['ts3func_userstatus'] = '<font color="red"><strong>Offline</strong></font>';
	    	}
	    	else
	    	{
	    		$profileInfo = $query->clientInfo($result['data'][0]['clid']);
	    		if($profileInfo['data']['client_idle_time'] > $mybb->settings['ts3func_afktime'] * 60 * 1000)
	    		{
	    			$afkTime = floor($profileInfo['data']['client_idle_time'] / 60 / 1000);
	    			$post['ts3func_userstatus'] = "<font color=\"orange\"><strong>AFK</strong></font> <span class=\"smalltext\" style=\"color: grey;\">[{$lang->ts3func_from} ".$afkTime." {$lang->ts3func_minutes}]</span>";
	    		}
	    		else
	    		{
	    			$post['ts3func_userstatus'] = "<font color=\"green\"><strong>Online</strong></font>";
	    		}
	    	}
    	}
    }
}