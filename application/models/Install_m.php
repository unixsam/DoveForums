<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Name:  Install Model
 *
 * Version: 0.0.1
 *
 * Author:  Chris Baines
 * 		    chris@doveforums.com
 *
 * Location: http://github.com/chrisbaines/DoveForums
 *
 * Description:  Installation model for Dove Forums.
 *
 * Requirements: PHP5 or above
 *
 */

class Install_M extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function create_database($database_name)
    {

        $this->load->database();
        $this->load->dbforge();

        if ($this->dbforge->create_database($database_name))
        {
            $find    = "'database' =>";
            $replace = "\t" . "'database' => '" . $database_name . "'," . "\n";

            if ($this->edit_database_config($find, $replace) === true)
            {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function create_tables()
    {

        $this->load->database();
        $this->load->dbforge();

        // Create sessions table.
        $sql = "
			CREATE TABLE IF NOT EXISTS `ci_sessions` (
			  `id` varchar(40) NOT NULL,
			  `ip_address` varchar(45) NOT NULL,
			  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
			  `data` blob NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create categories table.
        $sql = "
            CREATE TABLE IF NOT EXISTS `categories` (
              `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `discussion_count` int(11) DEFAULT '0',
              `comment_count` int(11) DEFAULT '0',
              `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `insert_user_id` int(11) DEFAULT NULL,
              `update_user_id` int(11) DEFAULT NULL,
              `date_inserted` datetime DEFAULT NULL,
              `date_updated` datetime DEFAULT NULL,
              `last_comment_date` datetime DEFAULT NULL,
              `last_comment_id` int(11) DEFAULT NULL,
              `last_discussion_id` int(11) DEFAULT NULL,
              `deletable` int(11) DEFAULT '1',
              PRIMARY KEY (`category_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create comments table.
        $sql = "
            CREATE TABLE IF NOT EXISTS `comments` (
              `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `discussion_id` int(11) DEFAULT NULL,
              `insert_user_id` int(11) DEFAULT NULL,
              `update_user_id` int(11) DEFAULT NULL,
              `delete_user_id` int(11) DEFAULT NULL,
              `body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              `insert_date` datetime DEFAULT NULL,
              `delete_date` datetime DEFAULT NULL,
              `update_date` datetime DEFAULT NULL,
              `insert_ip` varchar(39) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `update_ip` varchar(39) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `flag` tinyint(4) DEFAULT '0',
              `report_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `report_date` datetime DEFAULT NULL,
              `report_user_id` int(11) DEFAULT '0',
              PRIMARY KEY (`comment_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create discussions table.
        $sql = "
            CREATE TABLE IF NOT EXISTS `discussions` (
              `discussion_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `category_id` int(11) DEFAULT NULL,
              `insert_user_id` int(11) DEFAULT NULL,
              `update_user_id` int(11) DEFAULT NULL,
              `first_comment_id` int(11) DEFAULT '0',
              `last_comment_id` int(11) DEFAULT NULL,
              `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              `comment_count` int(11) DEFAULT '0',
              `bookmark_count` int(11) DEFAULT '0',
              `view_count` int(11) DEFAULT '0',
              `closed` tinyint(4) DEFAULT '0',
              `announce` tinyint(4) DEFAULT '0',
              `stick` tinyint(4) DEFAULT '0',
              `flag` tinyint(4) DEFAULT '0',
              `insert_date` datetime DEFAULT NULL,
              `update_date` datetime DEFAULT NULL,
              `insert_ip` varchar(39) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `update_ip` varchar(39) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `last_comment_date` datetime DEFAULT NULL,
              `last_comment_user_id` int(11) DEFAULT NULL,
              `report_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `report_date` datetime DEFAULT NULL,
              `report_user_id` int(11) DEFAULT '0',
              PRIMARY KEY (`discussion_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create groups table.
        $sql = "
            CREATE TABLE IF NOT EXISTS `groups` (
              `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(20) NOT NULL,
              `description` varchar(100) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create logging attempts table.
        $sql = "
            CREATE TABLE IF NOT EXISTS `login_attempts` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `ip_address` varchar(15) NOT NULL,
              `login` varchar(100) NOT NULL,
              `time` int(11) unsigned DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create users table.
        $sql = "
            CREATE TABLE IF NOT EXISTS `users` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `ip_address` varchar(15) NOT NULL,
              `username` varchar(100) NOT NULL,
              `password` varchar(255) NOT NULL,
              `salt` varchar(255) DEFAULT NULL,
              `email` varchar(100) NOT NULL,
              `activation_code` varchar(40) DEFAULT NULL,
              `forgotten_password_code` varchar(40) DEFAULT NULL,
              `forgotten_password_time` int(11) unsigned DEFAULT NULL,
              `remember_code` varchar(40) DEFAULT NULL,
              `created_on` int(11) unsigned NOT NULL,
              `last_login` int(11) unsigned DEFAULT NULL,
              `active` tinyint(1) unsigned DEFAULT NULL,
              `first_name` varchar(50) DEFAULT NULL,
              `last_name` varchar(50) DEFAULT NULL,
              `company` varchar(100) DEFAULT NULL,
              `phone` varchar(20) DEFAULT NULL,
              `visit_count` int(11) DEFAULT '0',
              `comments` int(11) DEFAULT NULL,
              `discussions` int(11) DEFAULT NULL,
              `language` varchar(255) DEFAULT 'english',
              `reported` int(11) NOT NULL DEFAULT '0',
              `report_reason` varchar(255) NOT NULL,
              `report_date` datetime NOT NULL,
              `report_user_id` int(11) NOT NULL,
              `points` int(11) DEFAULT 0,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create users groups table.
        $sql = "
            CREATE TABLE IF NOT EXISTS `users_groups` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` int(11) unsigned NOT NULL,
              `group_id` mediumint(8) unsigned NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
              KEY `fk_users_groups_users1_idx` (`user_id`),
              KEY `fk_users_groups_groups1_idx` (`group_id`),
              CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
              CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create settings table.
        $sql = "
            CREATE TABLE `settings` (
              `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `option_name` varchar(64) NOT NULL DEFAULT '',
              `option_value` mediumtext NOT NULL,
              `option_group` varchar(55) NOT NULL DEFAULT 'site',
              `auto_load` enum('no','yes') NOT NULL DEFAULT 'yes',
              PRIMARY KEY (`option_id`,`option_name`),
              KEY `option_name` (`option_name`),
              KEY `auto_load` (`auto_load`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create language_packs table.
        $sql = "
            CREATE TABLE `language_packs` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `language` varchar(255) DEFAULT NULL,
              `code` varchar(255) DEFAULT NULL,
              `icon` varchar(100) DEFAULT NULL,
              `deletable` int(11) DEFAULT '1',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create permissions table.
        $sql = "
            CREATE TABLE `permissions` (
              `permission_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `permission` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `key` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              `category` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
              PRIMARY KEY (`permission_id`),
              UNIQUE KEY `key` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create permission_map table.
        $sql = "
            CREATE TABLE `permission_map` (
              `group_id` int(11) NOT NULL DEFAULT '0',
              `permission_id` int(11) NOT NULL DEFAULT '0'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create achievements table.
        $sql = "
            CREATE TABLE IF NOT EXISTS `achievements` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL,
              `description` text NOT NULL,
              `points` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create achievement_triggers table.
        $sql = "
            CREATE TABLE IF NOT EXISTS `achievement_triggers` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `action` varchar(255) NOT NULL,
              `condition` varchar(255) NOT NULL,
              `achievement_id` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Create user_achievements table.
        $sql = "
            CREATE TABLE IF NOT EXISTS `user_achievements` (
              `achievement_id` int(11) NOT NULL,
              `user_id` int(11) NOT NULL,
              `date_received` datetime NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Add default groups.
        $sql = "
            INSERT INTO `groups` (`id`, `name`, `description`)
            VALUES
                (1,'guest','Guest'),
                (2,'admin','Administrator'),
                (3,'members','General User'),
                (4,'moderator','Moderators');
	    ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Add default guest user.
        $sql = "
            INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `visit_count`, `comments`, `discussions`, `language`, `reported`, `report_reason`, `report_date`, `report_user_id`, `points`)
            VALUES
                (1,'::0','Guest','',NULL,'',NULL,NULL,NULL,NULL,1433427728,NULL,0,'Guest','User',NULL,NULL,1,NULL,NULL,'english',0,'','0000-00-00 00:00:00',0, 0);
	    ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Add the default users_groups.
        $sql = "
            INSERT INTO `users_groups` (`id`, `user_id`, `group_id`)
            VALUES
                (1,1,1);
	    ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Add the default category.

        $sql = "
            INSERT INTO `categories` (`category_id`, `discussion_count`, `comment_count`, `name`, `slug`, `description`, `insert_user_id`, `update_user_id`, `date_inserted`, `date_updated`, `last_comment_date`, `last_comment_id`, `last_discussion_id`, `deletable`)
            VALUES
                (1,0,0,'General','general','This is the general category.',1,0,'2015-04-15 15:00:00',NULL,'2015-05-27 11:21:07',0,0,0);
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Add the default language_pack
        $sql = "
            INSERT INTO `language_packs` (`id`, `language`, `code`, `icon`, `deletable`)
            VALUES
                (1,'English','english','gb.png',0),
                (2, 'Italian', 'italian', 'it.png', 0);
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Add the default permissions
        $sql = "
            INSERT INTO `permissions` (`permission_id`, `permission`, `key`, `category`)
            VALUES
                (1,'Create Discussions','create_discussions','discussions'),
                (2,'Edit Discussions','edit_discussions','discussions'),
                (3,'Delete Discussions','delete_discussions','discussions'),
                (4,'Create Comments','create_comments','comments'),
                (5,'Edit Comments','edit_comments','comments'),
                (6,'Delete Comments','delete_comments','comments'),
                (7,'Report Discussions','report_discussions','discussions'),
                (8,'Report Comments','report_comments','comments'),
                (9,'View Discussions','view_discussions','discussions'),
                (10,'Change Password','change_password','users'),
                (11,'Change Settings','change_settings','settings'),
                (12,'Report Users','report_users','users'),
                (13,'Edit User Settings','edit_user_settings','users'),
                (14,'View Profile','view_profile','users'),
                (15,'Unlock Achievements', 'unlock_achievements', 'achievements');
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Add the default permission_map
        $sql = "
            INSERT INTO `permission_map` (`group_id`, `permission_id`)
            VALUES
                (1,9),
                (1,14),
                (2,3),
                (2,2),
                (2,1),
                (2,4),
                (2,5),
                (2,6),
                (2,7),
                (2,8),
                (2,9),
                (2,10),
                (2,11),
                (2,12),
                (2,13),
                (2,14),
                (2,15);
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Add some default achievements.
        $sql = "
            INSERT INTO `achievements` (`id`, `name`, `description`, `points`) VALUES
                (1, 'Create your First Comment.', 'You have created your very first comment!', 5),
                (2, 'Create your First Discussion', 'You have created your very first discussion!', 5);
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Insert some default triggers.
        $sql = "
            INSERT INTO `achievement_triggers` (`id`, `action`, `condition`, `achievement_id`) VALUES
                (1, 'create_comment', '1', 1),
                (2, 'create_discussion', '1', 2);
        ";

        if(!$this->db->query($sql))
        {
            return FALSE;
        }

        // Everything ok.
        return TRUE;
    }

    public function edit_database_config($find, $replace)
    {
        $reading = fopen(APPPATH . 'config/database.php', 'r');
        $writing = fopen(APPPATH . 'config/database.tmp', 'w');

        $replaced = false;

        while (!feof($reading))
        {
            $line = fgets($reading);

            if (stristr($line, $find))
            {
                $line = $replace;
                $replaced = true;
            }
            fputs($writing, $line);
        }

        fclose($reading); fclose($writing);

        // Do not overwrite the file if we did not replace anything.
        if ($replaced)
        {
            rename(APPPATH . 'config/database.tmp', APPPATH . 'config/database.php');
            return true;
        }
        else
        {
            unlink(APPPATH . 'config/database.tmp');
            return false;
        }
    }

    public function edit_main_config($find, $replace)
    {
        $reading = fopen(APPPATH . 'config/config.php', 'r');
        $writing = fopen(APPPATH . 'config/config.tmp', 'w');

        $replaced = false;

        while (!feof($reading))
        {
            $line = fgets($reading);

            if (stristr($line, $find))
            {
                $line = $replace;
                $replaced = true;
            }
            fputs($writing, $line);
        }

        fclose($reading); fclose($writing);

        // Do not overwrite the file if we did not replace anything.
        if ($replaced)
        {
            rename(APPPATH . 'config/config.tmp', APPPATH . 'config/config.php');
            return true;
        }
        else
        {
            unlink(APPPATH . 'config/config.tmp');
            return false;
        }
    }

    public function edit_forum_config($find, $replace)
    {
        $reading = fopen(APPPATH . 'config/forums.php', 'r');
        $writing = fopen(APPPATH . 'config/forums.tmp', 'w');

        $replaced = false;

        while (!feof($reading))
        {
            $line = fgets($reading);

            if (stristr($line, $find))
            {
                $line = $replace;
                $replaced = true;
            }
            fputs($writing, $line);
        }

        fclose($reading); fclose($writing);

        // Do not overwrite the file if we did not replace anything.
        if ($replaced)
        {
            rename(APPPATH . 'config/forums.tmp', APPPATH . 'config/forums.php');
            return true;
        }
        else
        {
            unlink(APPPATH . 'config/forums.tmp');
            return false;
        }
    }

    public function edit_routes_config($find, $replace)
    {
        $reading = fopen(APPPATH . 'config/routes.php', 'r');
        $writing = fopen(APPPATH . 'config/routes.tmp', 'w');

        $replaced = false;

        while (!feof($reading))
        {
            $line = fgets($reading);

            if (stristr($line, $find))
            {
                $line = $replace;
                $replaced = true;
            }
            fputs($writing, $line);
        }

        fclose($reading); fclose($writing);

        // Do not overwrite the file if we did not replace anything.
        if ($replaced)
        {
            rename(APPPATH . 'config/routes.tmp', APPPATH . 'config/routes.php');
            return true;
        }
        else
        {
            unlink(APPPATH . 'config/routes.tmp');
            return false;
        }
    }

    public function test_database($host, $username, $password, $database_name)
    {
        // Create a connection.
        $conn = new mysqli($host, $username, $password, $database_name);

        // Check the connection.
        if ($conn->connect_error)
        {
            return FALSE;
        }

        return TRUE;
    }

    public function delete_files()
    {
        $installation_items = array(
            APPPATH . 'controllers/Install.php',
            APPPATH . 'views/install',
            APPPATH . 'models/Install_m.php',
        );

        foreach ($installation_items as $installation_item)
        {
            $this->_delete_files($installation_item);
        }

        return TRUE;
    }

    private function _delete_files($target)
    {
        if (is_dir($target))
        {
            $files = glob($target . '*', GLOB_MARK);

            foreach($files as $file)
            {
                $this->_delete_files($file);
            }

            if(file_exists($target) && is_dir($target))
            {
                rmdir($target);
            }
        }
        elseif (is_file($target))
        {
            unlink( $target );
        }
    }

}