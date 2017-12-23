	TS3 Functions [v2.0]
      (c) Copyright 2013-2017 by Piotr 'Inferno' Grencel
 
      @author    : Piotr 'Inferno' Grencel
      @website	 : http://github.com/inferno211
      @contact   : inferno.piotr@gmail.com
      @date      : 07-03-2017
      @update    : 17-12-2017
      
      
      <p align="center">
    <img src="https://rawgit.com/badges/shields/master/static/logo.svg"
        height="130">


<p align="center">
	<a href="#version" alt="Backers on Open Collective"><img src="https://img.shields.io/badge/version-2.0-red.svg" /></a>
	<a href="github.com/inferno211/ts3func/releases" alt="Go to releases"><img src="https://img.shields.io/badge/Download-releases-green.svg" /></a>
</p>

The plugin adds the ability to integrate the account from the TeamSpeak 3 server with the mybb account. In addition, it displays information about users online on the server, and in the profile of the user about his statistics.

* [MyBB.com Topic](https://community.mybb.com/thread-214879-post-1292763.html#pid1292763)
* [MyBB.com Mod site](https://community.mybb.com/mods.php?action=view&pid=956)
* [MyBBoard.pl Topic](https://mybboard.pl/thread-73099.html)
* [Inferno Site](http://inferno24.eu)
* [Facebook Fanpage](https://facebook.com/PanInferno)

[ INSTALATION ]
--------
`1. Upload files to server.
2. Install plugin in ACP.
3. Create an additional profile reference field below the scheme below:

	Name: TS3 identity
	Short description: Provide the TS3 identifier which you will find in Tools -> Identities (ctrl + i).
	Field type: textbox
	Maximum length: 50
	Required: no
	Show during registration ?: yes
	Show in user profile ?: no
	Display post in description: no
	Visible to: All groups
	Editable by: All groups

4. Copy the id of the additional field (when editing it, you will find it in the address bar).
5. Complete the data to be connected and the field ID in the plugin settings.`
