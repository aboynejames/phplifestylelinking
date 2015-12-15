

CREATE TABLE IF NOT EXISTS `dailypeers` (
  `prid` int(11) NOT NULL auto_increment,
  `rank` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `idlifestart` int(11) NOT NULL,
  `peerid` int(11) NOT NULL,
  PRIMARY KEY  (`prid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `dailyposts` (
  `drid` int(11) NOT NULL auto_increment,
  `rank` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `lifestyleid` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  PRIMARY KEY  (`drid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `definitionscorea` (
  `defoneid` int(11) NOT NULL,
  `deftwoid` int(11) NOT NULL,
  `matched1` int(11) NOT NULL,
  `matched2` int(11) NOT NULL,
  `matched3` int(11) NOT NULL,
  `matched4` int(11) NOT NULL,
  `matched5` int(11) NOT NULL,
  `matched10` int(11) NOT NULL,
  `matched20` int(11) NOT NULL,
  `matched50` int(11) NOT NULL,
  `score1` int(11) NOT NULL,
  `score2` int(11) NOT NULL,
  `score3` int(11) NOT NULL,
  `score4` int(11) NOT NULL,
  `score5` int(11) NOT NULL,
  `score10` int(11) NOT NULL,
  `score20` int(11) NOT NULL,
  `score50` int(11) NOT NULL,
  KEY `itemid` (`defoneid`),
  KEY `lifestyleid` (`deftwoid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `definwords` (
  `word` varchar(100) NOT NULL,
  `idlifestart` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `feedlinking` (
  `id` int(11) NOT NULL,
  `liveflag` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `feeds` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(250) NOT NULL,
  `title` text NOT NULL,
  `link` varchar(250) default NULL,
  `description` text,
  `date_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `tags` varchar(250) default NULL,
  `aging` int(11) NOT NULL default '1000',
  `expir` int(11) NOT NULL default '0',
  `private` tinyint(1) default '0',
  `image` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `frequency` (
  `freqid` int(11) NOT NULL auto_increment,
  `word` varchar(30) NOT NULL,
  `idlifestart` int(11) NOT NULL,
  PRIMARY KEY  (`freqid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `grouplink` (
  `groupid` int(11) NOT NULL,
  `idlifestyle` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `groups` (
  `GroupID` int(11) NOT NULL auto_increment,
  `LabelID` int(11) NOT NULL default '0',
  `Description` varchar(50) default '',
  PRIMARY KEY  (`GroupID`,`LabelID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `imagesource` (
  `idstart` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `imgurl` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sourceurl` varchar(255) NOT NULL,
  `enddate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `itemlinking` (
  `id` int(11) NOT NULL,
  `iddata` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL auto_increment,
  `feed_id` int(11) NOT NULL default '0',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `link` text,
  `title` text,
  `content` longtext,
  `dcdate` text,
  `dccreator` text,
  `dcsubject` text,
  `read` tinyint(4) default NULL,
  `publish` tinyint(1) default '0',
  `star` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `feed_id_idx` (`feed_id`),
  KEY `read_idx` (`read`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `lifestyle` (
  `idlifestyle` int(11) NOT NULL auto_increment,
  `idlifestart` int(11) NOT NULL,
  `name` varchar(100) NOT NULL default '',
  `menuurl` varchar(255) NOT NULL,
  PRIMARY KEY  (`idlifestyle`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `lifestyleaverage` (
  `date` int(11) NOT NULL,
  `idlifestart` int(11) NOT NULL,
  `postratio` decimal(20,4) NOT NULL,
  `avglife` decimal(20,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `lifestyledefinition` (
  `idlifestart` int(11) NOT NULL,
  `lifestylewords` varchar(255) NOT NULL,
  `votes` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `lifestylegroups` (
  `groupid` int(11) NOT NULL auto_increment,
  `groupname` varchar(100) NOT NULL,
  PRIMARY KEY  (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `lifestylelightscorea` (
  `itemid` int(11) NOT NULL,
  `lifestyleid` int(11) NOT NULL,
  `matched1` int(11) NOT NULL,
  `matched2` int(11) NOT NULL,
  `matched3` int(11) NOT NULL,
  `matched4` int(11) NOT NULL,
  `matched5` int(11) NOT NULL,
  `matched10` int(11) NOT NULL,
  `matched20` int(11) NOT NULL,
  `matched50` int(11) NOT NULL,
  `score1` int(11) NOT NULL,
  `score2` int(11) NOT NULL,
  `score3` int(11) NOT NULL,
  `score4` int(11) NOT NULL,
  `score5` int(11) NOT NULL,
  `score10` int(11) NOT NULL,
  `score20` int(11) NOT NULL,
  `score50` int(11) NOT NULL,
  KEY `itemid` (`itemid`),
  KEY `lifestyleid` (`lifestyleid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `lifestylelightstats` (
  `feed_id` int(11) NOT NULL,
  `idlifestart` int(11) NOT NULL,
  `noposts` int(11) NOT NULL,
  `scoposts` int(11) NOT NULL,
  `lifestylescore` int(11) NOT NULL,
  `topmatch` int(11) NOT NULL,
  `avgscore` decimal(65,2) NOT NULL,
  `scoreratio` decimal(65,2) NOT NULL,
  `scoredate` int(11) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  KEY `feed_id` (`feed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `lifestyleproduct` (
  `prodid` int(11) NOT NULL,
  `idlifestart` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `lifestylestart` (
  `idlifestart` int(11) NOT NULL auto_increment,
  `iddata` int(11) NOT NULL,
  `definition` varchar(255) NOT NULL,
  `defurl` varchar(255) NOT NULL,
  PRIMARY KEY  (`idlifestart`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `lifestylesub` (
  `subid` int(11) NOT NULL auto_increment,
  `lifestyleid` int(11) NOT NULL,
  `lifestartid` int(11) NOT NULL,
  `subname` varchar(30) NOT NULL,
  `imageurl` varchar(255) NOT NULL,
  `imageurlsel` varchar(255) NOT NULL,
  PRIMARY KEY  (`subid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `makeproduct` (
  `idmake` int(11) NOT NULL auto_increment,
  `productid` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `makedate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`idmake`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `melife` (
  `feed_id` int(11) NOT NULL,
  `idlifestart` int(11) NOT NULL,
  `topmatch` int(11) NOT NULL,
  `diffavg` decimal(11,2) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `peergroup` (
  `idlifestart` int(11) NOT NULL,
  `avgscore` decimal(65,2) NOT NULL,
  `image` text character set utf8,
  `url` varchar(250) character set utf8 default NULL,
  `scoredate` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `perpeers` (
  `ppid` int(11) NOT NULL auto_increment,
  `feedid` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `idlifestart` int(11) NOT NULL,
  `peerid` int(11) NOT NULL,
  PRIMARY KEY  (`ppid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `postwords` (
  `itemid` int(11) NOT NULL,
  `word` varchar(30) NOT NULL,
  KEY `itemid` (`itemid`),
  KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `privacy` (
  `privid` int(11) NOT NULL auto_increment,
  `ID` int(11) NOT NULL,
  `privstatusid` int(11) NOT NULL,
  PRIMARY KEY  (`privid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `privstatus` (
  `privstatusid` int(11) NOT NULL auto_increment,
  `privacy` varchar(20) NOT NULL,
  PRIMARY KEY  (`privstatusid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `products` (
  `prodid` int(11) NOT NULL auto_increment,
  `produrl` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`prodid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `rawnewwords` (
  `Word` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `rssfeeds` (
  `meurlid` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL,
  `rssxml` varchar(255) NOT NULL,
  `uploaddate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`meurlid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `rssjoin` (
  `userid` int(11) NOT NULL,
  `feedsid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `rssreader` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  `lifeid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `siteinfo` (
  `infoid` int(11) NOT NULL auto_increment,
  `type` varchar(100) NOT NULL,
  `siteinfo` varchar(255) NOT NULL,
  PRIMARY KEY  (`infoid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `toplife` (
  `feedid` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `lifestyleid` int(11) NOT NULL,
  KEY `feedid` (`feedid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `updatecontrol` (
  `updateid` int(11) NOT NULL auto_increment,
  `itemid` int(11) NOT NULL,
  `update` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`updateid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `urllist` (
  `urlid` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY  (`urlid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `userlife` (
  `userid` int(11) NOT NULL,
  `lifestyleid` int(11) NOT NULL,
  `humadd` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL auto_increment,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Temp_pass` varchar(55) default NULL,
  `Temp_pass_active` tinyint(1) NOT NULL default '0',
  `Email` varchar(255) NOT NULL,
  `Active` int(11) NOT NULL,
  `Level_access` int(11) NOT NULL,
  `Random_key` varchar(32) default NULL,
  `startdate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `userprofile` varchar(255) NOT NULL,
  `websiteurl` varchar(255) NOT NULL,
  `fb_uid` int(11) NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `usertype` (
  `usertypeid` int(11) NOT NULL auto_increment,
  `ID` int(11) NOT NULL,
  `typeid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `dateselected` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`usertypeid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `user_openids` (
  `openid_url` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`openid_url`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `webrefrssdetails` (
  `idrss` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `description` mediumtext NOT NULL,
  `link` text,
  `language` text,
  `image_title` text,
  `image_url` text,
  `image_link` text,
  `image_width` text,
  `image_height` text,
  PRIMARY KEY  (`idrss`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `websitelifestyle` (
  `webid` int(11) NOT NULL,
  `lifestyleid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `websites` (
  `webid` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `weburl` varchar(255) NOT NULL,
  `logourl` varchar(255) NOT NULL,
  `startper` varchar(255) NOT NULL,
  PRIMARY KEY  (`webid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `weeklydate` (
  `wdate` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `wikipediascore` (
  `idlifestart` int(11) NOT NULL,
  `word` varchar(30) NOT NULL,
  `frequencyscore` int(11) NOT NULL,
  KEY `idlifestart` (`idlifestart`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `resultsdate` (
  `resultdate` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
