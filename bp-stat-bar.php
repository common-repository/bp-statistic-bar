<?php

	// Start class bpstatbar_widget //
	
	class bpstatbar_widget extends WP_Widget {

	// Constructor //

		function bpstatbar_widget() {
			$widget_ops = array( 'classname' => 'bpstatbar_widget', 'description' => 'Buddypress Statistic Bar' ); // Widget Settings
			$control_ops = array( 'id_base' => 'bpstatbar_widget' ); // Widget Control Settings
			$this->WP_Widget( 'bpstatbar_widget', 'Buddypress statistic bar', $widget_ops, $control_ops ); // Create the widget
		}

	// Extract Args //

		function widget($args, $instance) {
			// global $wpdb, $user_identity, $user_ID, $bp, $current_user;
			extract( $args );
			global $wpdb, $user_identity, $user_ID, $bp, $current_user;
			$title 				= apply_filters('widget_title', $instance['title']); // the widget title
			$aligment			= $instance['aligment_cfg']; //vertical or horizontal bar
			$showsites			= isset($instance['show_sites']) ? $instance['show_sites'] : false ; // show number of sites
			$showusers			= isset($instance['show_users']) ? $instance['show_users'] : false ; // show number of users
			$showgroups			= isset($instance['show_groups']) ? $instance['show_groups'] : false ; // show number of groups
			$showposts			= isset($instance['show_posts']) ? $instance['show_posts'] : false ; // show number of posts
			$showcomments		= isset($instance['show_comments']) ? $instance['show_comments'] : false ; // show number of comments
			$showforums			= isset($instance['show_forums']) ? $instance['show_forums'] : false ; // show number of forums
			$showloggedin		= isset($instance['show_loggedin']) ? $instance['show_loggedin'] : false ; // show greeting
			$showavatar			= isset($instance['show_avatar']) ? $instance['show_avatar'] : false ; // show logged in avatar
			$avatarsize			= $instance['avatar_size']; // logged in avatar size
			$showcredit			= isset($instance['show_credit']) ? $instance['show_credit'] : false ; // show author credits

	// Before widget //

			echo $before_widget;

	// Title of widget //

			if ( $title ) { echo $before_title . $title . $after_title; }

	// Widget output //
	$oldblog = $current_site->blog_id;
	if ($aligment == 'vertical') {
	$bar_begin='<table id="bpstatbar">';$bar_end='</table>';$delim_begin='<tr>';$delim_end='</tr>';	
	echo $bar_begin;
	if ($showloggedin == TRUE)
	{
		echo $delim_begin;
	?>
		<td style="vertical-align:middle;"><a href="<?php echo bp_loggedin_user_domain(); ?>profile">
		<?php if ($showavatar == TRUE) { bp_loggedin_user_avatar( 'type=full&width='.$avatarsize.'&height='.$avatarsize ); } ?></td><td style="vertical-align:middle;text-align:center;"><strong><?php _e('Hello','bpstatbar').' '; ?></strong>
		<a href="<?php echo bp_loggedin_user_domain(); ?>profile"><?php echo ucwords($user_identity) ?></a>!
		<?php 
		if(function_exists('cp_getPoints')) {
			$txt = '<br /><span style="font-size:x-small;font-style:italic;">'; $txt .= cp_getPoints($bp->loggedin_user->id);$txt .= ' '.__('points','bpstatbar');if(function_exists('cp_module_ranks_getRank')) $txt .= ' /'.cp_module_ranks_getRank($bp->loggedin_user->id).'/';
			$txt .= '</span>';echo $txt; 
		}
		echo ('</td>');
		echo $delim_end;
	}	
	echo $delim_begin;
	echo ('<td colspan="2" class="bpstat-bar-head">'.__('Community','bpstatbar').'</td>');
	echo $delim_end;
	if ($showsites == TRUE){
		echo $delim_begin;
		echo ('<td class="bpstatbar-label">'.__('Sites','bpstatbar').'</td>');
		echo ('<td class="bpstatbar-value">');
		echo get_blog_count();
		echo ('</td>');
		echo $delim_end;
	}
	if ($showusers == TRUE) {
		echo $delim_begin;
		echo ('<td class="bpstatbar-label">'.__('Members','bpstatbar').'</td>');
		echo ('<td class="bpstatbar-value">');
		echo bp_total_site_member_count();
		echo ('</td>');
		echo $delim_end;
	}
	if ($showgroups == TRUE) {
		echo $delim_begin;
		echo ('<td class="bpstatbar-label">'.__('Groups','bpstatbar').'</td>');
		echo ('<td class="bpstatbar-value">');		
		echo bp_total_group_count();
		echo ('</td>');
		echo $delim_end;
	}
	if ($showposts == TRUE) {
		echo $delim_begin;
		echo ('<td class="bpstatbar-label">'.__('Posts','bpstatbar').'</td>');
		echo ('<td class="bpstatbar-value">');		
		$res = $wpdb->get_results('select blog_id from wp_blogs', ARRAY_A);
		$total = 0;
		foreach ($res as $result) {
			$wpdb->set_blog_id($result['blog_id']);
			$val = (int)$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_date_gmt < '" . gmdate("Y-m-d H:i:s",time()) . "'");
			$total += $val;
		}
		echo $total;
		echo ('</td>');
		echo $delim_end;
	}
	if ($showcomments == TRUE) {
		echo $delim_begin;
		echo ('<td class="bpstatbar-label">'.__('Comments','bpstatbar').'</td>');
		echo ('<td class="bpstatbar-value">');			
		$res = $wpdb->get_results('select blog_id from wp_blogs', ARRAY_A);
		$total = 0;
		foreach ($res as $result) {
			$wpdb->set_blog_id($result['blog_id']);
			$val = (int)$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'");
			$total += $val;
		}
		echo $total;
		echo ('</td>');
		echo $delim_end;
	}
	if ($showforums == TRUE)
	{
		echo $delim_begin;
		echo ('<td colspan="2" class="bpstat-bar-head">'.__('Discussions','bpstatbar').'</td>');
		echo $delim_end;
		echo $delim_begin;
		echo ('<td class="bpstatbar-label">'.__('Forums','bpstatbar').'</td>');
		echo ('<td class="bpstatbar-value">');	
		$count = (int)$wpdb->get_var("SELECT COUNT(*) FROM wp_bb_forums");echo $count;
		echo ('</td>');
		echo $delim_end;
		echo $delim_begin;
		echo ('<td class="bpstatbar-label">'.__('Topics','bpstatbar').'</td>');
		echo ('<td class="bpstatbar-value">');	
		$count = (int)$wpdb->get_var("SELECT SUM(topics) FROM wp_bb_forums");echo $count;
		echo ('</td>');
		echo $delim_end;
		echo $delim_begin;
		echo ('<td class="bpstatbar-label">'.__('Posts','bpstatbar').'</td>');
		echo ('<td class="bpstatbar-value">');	
		$count = (int)$wpdb->get_var("SELECT SUM(posts) FROM wp_bb_forums");echo $count;
		echo ('</td>');
		echo $delim_end;
	}

	echo $bar_end;
	$wpdb->set_blog_id($oldblog);
	if ($showcredit == TRUE) { ?>
			<p class="authorcredit"><a href="http://www.bgextensions.bgvhod.com">&copy; BgExtensions 2012</a></p>
		<?php }
	}
	
	if ($aligment == 'horizontal') {
	$bar_begin='<table id="bpstatbar"><tr>';$bar_end='</tr></table>';$delim_begin='';$delim_end='';
	echo $bar_begin;
	if ($showloggedin == TRUE)
	{
		echo $delim_begin;
	?>
		<td style="vertical-align:middle;"><a href="<?php echo bp_loggedin_user_domain(); ?>profile">
		<?php if ($showavatar == TRUE) { bp_loggedin_user_avatar( 'type=full&width='.$avatarsize.'&height='.$avatarsize ); } ?></td><td style="vertical-align:middle;text-align:center;"><strong><?php _e('Hello','bpstatbar').' '; ?></strong>
		<a href="<?php echo bp_loggedin_user_domain(); ?>profile"><?php echo ucwords($user_identity) ?></a>!
		<?php 
		if(function_exists('cp_getPoints')) {
			$txt = '<br /><span style="font-size:x-small;font-style:italic;">'; $txt .= cp_getPoints($bp->loggedin_user->id);$txt .= ' '.__('points','bpstatbar');if(function_exists('cp_module_ranks_getRank')) $txt .= ' /'.cp_module_ranks_getRank($bp->loggedin_user->id).'/';
			$txt .= '</span>';echo $txt; 
		}
		echo ('</td>');
		echo $delim_end;
	}	
	echo $delim_begin;
	echo ('<td class="bpstat-bar-head">'.__('Community','bpstatbar').'</td>');
	echo $delim_end;
	if ($showsites == TRUE){
		echo $delim_begin;
		echo ('<td class="bpstatbar-h-label">'.__('Sites','bpstatbar').'<br />');
		echo get_blog_count();
		echo ('</td>');
		echo $delim_end;
	}
	if ($showusers == TRUE) {
		echo $delim_begin;
		echo ('<td class="bpstatbar-h-label">'.__('Members','bpstatbar').'<br />');
		echo bp_total_site_member_count();
		echo ('</td>');
		echo $delim_end;
	}
	if ($showgroups == TRUE) {
		echo $delim_begin;
		echo ('<td class="bpstatbar-h-label">'.__('Groups','bpstatbar').'<br />');
		echo bp_total_group_count();
		echo ('</td>');
		echo $delim_end;
	}
	if ($showposts == TRUE) {
		echo $delim_begin;
		echo ('<td class="bpstatbar-h-label">'.__('Posts','bpstatbar').'<br />');
		$res = $wpdb->get_results('select blog_id from wp_blogs', ARRAY_A);
		$total = 0;
		foreach ($res as $result) {
			$wpdb->set_blog_id($result['blog_id']);
			$val = (int)$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_date_gmt < '" . gmdate("Y-m-d H:i:s",time()) . "'");
			$total += $val;
		}
		echo $total;
		echo ('</td>');
		echo $delim_end;
	}
	if ($showcomments == TRUE) {
		echo $delim_begin;
		echo ('<td class="bpstatbar-h-label">'.__('Comments','bpstatbar').'<br />');
		$res = $wpdb->get_results('select blog_id from wp_blogs', ARRAY_A);
		$total = 0;
		foreach ($res as $result) {
			$wpdb->set_blog_id($result['blog_id']);
			$val = (int)$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'");
			$total += $val;
		}
		echo $total;
		echo ('</td>');
		echo $delim_end;
	}
	if ($showforums == TRUE)
	{
		echo $delim_begin;
		echo ('<td class="bpstat-bar-head">'.__('Discussions','bpstatbar').'</td>');
		echo $delim_end;
		echo $delim_begin;
		echo ('<td class="bpstatbar-h-label">'.__('Forums','bpstatbar').'<br />');
		$count = (int)$wpdb->get_var("SELECT COUNT(*) FROM wp_bb_forums");echo $count;
		echo ('</td>');
		echo $delim_end;
		echo $delim_begin;
		echo ('<td class="bpstatbar-h-label">'.__('Topics','bpstatbar').'<br />');
		$count = (int)$wpdb->get_var("SELECT SUM(topics) FROM wp_bb_forums");echo $count;
		echo ('</td>');
		echo $delim_end;
		echo $delim_begin;
		echo ('<td class="bpstatbar-h-label">'.__('Posts','bpstatbar').'<br />');
		$count = (int)$wpdb->get_var("SELECT SUM(posts) FROM wp_bb_forums");echo $count;
		echo ('</td>');
		echo $delim_end;
	}

	echo $bar_end;
	$wpdb->set_blog_id($oldblog);
	if ($showcredit == TRUE) { ?>
			<p class="authorcredit"><a href="http://www.bgextensions.bgvhod.com">&copy; BgExtensions 2012</a></p>
		<?php }
	}		
	// After widget //

			echo $after_widget;
	}

	// Update Settings //

		function update($new_instance, $old_instance) {
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['aligment_cfg'] = strip_tags($new_instance['aligment_cfg']);
			$instance['show_sites'] = $new_instance['show_sites'];
			$instance['show_users'] = $new_instance['show_users'];			
			$instance['show_groups'] = $new_instance['show_groups'];			
			$instance['show_posts'] = $new_instance['show_posts'];			
			$instance['show_comments'] = $new_instance['show_comments'];			
			$instance['show_forums'] = $new_instance['show_forums'];			
			$instance['show_loggedin'] = $new_instance['show_loggedin'];
			$instance['show_avatar'] = $new_instance['show_avatar'];
			$instance['avatar_size'] = strip_tags($new_instance['avatar_size']);
			$instance['show_credit'] = $new_instance['show_credit'];
			return $instance;
		}

	
	// Widget Control Panel //

		function form($instance) {

		$defaults = array( 'title' => 'BP Statistics', 'aligment_cfg' => 'horizontal', 'avatar_size' => 50);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','bpstatbar'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('aligment_cfg'); ?>"><?php _e('Aligment:','bpstatbar'); ?></label>
			<select id="<?php echo $this->get_field_id('aligment_cfg'); ?>" name="<?php echo $this->get_field_name('aligment_cfg'); ?>" class="widefat" style="width:100%;">
				<option value="horizontal" <?php selected('horizontal', $instance['aligment_cfg']); ?>><?php _e('Horizontal','bpstatbar'); ?></option>
				<option value="vertical" <?php selected('vertical', $instance['aligment_cfg']); ?>><?php _e('Vertical','bpstatbar'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_sites'); ?>"><?php _e('Show sites number?','bpstatbar'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_sites'], 'on' ); ?> id="<?php echo $this->get_field_id('show_sites'); ?>" name="<?php echo $this->get_field_name('show_sites'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_users'); ?>"><?php _e('Show users number?','bpstatbar'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_users'], 'on' ); ?> id="<?php echo $this->get_field_id('show_users'); ?>" name="<?php echo $this->get_field_name('show_users'); ?>" />
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id('show_groups'); ?>"><?php _e('Show groups number?','bpstatbar'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_groups'], 'on' ); ?> id="<?php echo $this->get_field_id('show_groups'); ?>" name="<?php echo $this->get_field_name('show_groups'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_posts'); ?>"><?php _e('Show posts number?','bpstatbar'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_posts'], 'on' ); ?> id="<?php echo $this->get_field_id('show_posts'); ?>" name="<?php echo $this->get_field_name('show_posts'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_comments'); ?>"><?php _e('Show comments number?','bpstatbar'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_comments'], 'on' ); ?> id="<?php echo $this->get_field_id('show_comments'); ?>" name="<?php echo $this->get_field_name('show_comments'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_forums'); ?>"><?php _e('Show forums number?','bpstatbar'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_forums'], 'on' ); ?> id="<?php echo $this->get_field_id('show_forums'); ?>" name="<?php echo $this->get_field_name('show_forums'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_loggedin'); ?>"><?php _e('Show greeting?','bpstatbar'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_loggedin'], 'on' ); ?> id="<?php echo $this->get_field_id('show_loggedin'); ?>" name="<?php echo $this->get_field_name('show_loggedin'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_avatar'); ?>"><?php _e('Show logged in user avatar?','bpstatbar'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_avatar'], 'on' ); ?> id="<?php echo $this->get_field_id('show_avatar'); ?>" name="<?php echo $this->get_field_name('show_avatar'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('avatar_size'); ?>"><?php _e('Logged in avatar size in px','bpstatbar'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('avatar_size'); ?>" name="<?php echo $this->get_field_name('avatar_size'); ?>" type="text" value="<?php echo $instance['avatar_size']; ?>" />
		</p>				
		<p>
			<label for="<?php echo $this->get_field_id('show_credit'); ?>"><?php _e('Give credit to plugin author?','bpstatbar'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_credit'], 'on' ); ?> id="<?php echo $this->get_field_id('show_credit'); ?>" name="<?php echo $this->get_field_name('show_credit'); ?>" />
		</p>
        <?php }

}

// End class bpstatbar_widget

add_action('widgets_init', create_function('', 'return register_widget("bpstatbar_widget");'));
?>