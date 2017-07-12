<?php
/*
Plugin Name: Customize Youtube videos
Description: This plugin lets you customize the Youtube videos you are going to embed in your posts and pages. In the plugin option page you'll be able to see a preview of your customized video and you'll get the new embed code which you have to copy and paste in the Wordpress text editor (Text/HTML mode). The plugin doesn't work with the old embed code. Have fun!
Version: 0.2
Author: Marco Foggia
Author URI: http://marcofoggia.com
*/

 /* Add options */
 
function cyv_add_options() {
	add_option('code');
}
 
register_activation_hook(__FILE__,'cyv_add_options');
 
/* Register options group */
 
function cyv_register_options_group() {
	register_setting('cyv_group','code');
}
 
add_action('admin_init','cyv_register_options_group');

/* Customize Youtube videos */

function customize_youtube_videos_page(){ ?>
	<link rel="stylesheet" href="<?php echo plugins_url('customize-youtube-videos/style.css'); ?>"/>
	<h1>Customize Youtube videos</h1>
	<?php
	/* Here is the serious code */
	if(isset($_POST['code'])){
		preg_match("/\/\/.*\/embed\/[a-zA-Z0-9_-]+/",$_POST['code'],$match);
		$new_code = $match[0];
		$new_code.="?";
		$start;
		$end;
		if(trim($_POST['start'])!=""){
			$start = "&start=".$_POST['start']."";
		}
		else{
			$start="";
		}
		if(trim($_POST['end'])!=""){
			$end = "&end=".$_POST['end']."";
		} 
		else{
		$end="";
		}
		$new_code.=$start.$end.$_POST['rel'].$_POST['autoplay'].$_POST['loop'].$_POST['showinfo'].$_POST['cc_load_policy'].$_POST['autohide'].$_POST['controls'];

		if(preg_match("/\?&/",$new_code)){
			$new_code = preg_replace("/\?&/","?",$new_code);
		}

		$new_code_real_size = preg_replace("/src=.*\s+/","src=\"".$new_code."\" frameborder=\"0\" ",$_POST['code']);
	}
	
	/* Here goes the customization result */

	if(isset($_POST['code'])){ ?>
		<div id='wrapper-video-code'>
			<h2>Your customized video</h2>
			<?php echo "<iframe width='560' height='315' src='".str_replace("\\","",$new_code)."' frameborder='0' allowfullscreen></iframe>"; ?>
			<p><small>*Video preview size is always 560px x 315px</small></p>
			<h2>Your new embed code</h2>
			<p>Copy and paste this code in the Wordpress text editor (<u>Text/HTML mode, not Visual mode</u>)</p>
			<textarea class="new-code"><?php echo str_replace("\\","",$new_code_real_size); ?></textarea>
			<p>If you are not satisfied, copy the code above and repeat your customization!</p> 
		</div>
	<?php 
	} ?>
	<!-- Here is the form for the customization -->
	<form method="post" action="<?php admin_url('admin.php?page=customize-youtube-videos'); ?>">
		<p class="labels">Paste the Youtube video's embed-code* here:</p>
		<textarea name="code"></textarea>
		<p class="example">*The code must be similar to this one: <br/><?php echo htmlentities('<iframe width="560" height="315" src="//www.youtube.com/embed/AbcDefghiJK?rel=0" frameborder="0" allowfullscreen></iframe>'); ?></p>
		<h2>Let's customize the video*</h2>
		<p class="labels">Write the number of seconds at which you want the video to start:</p>
		<input type="text" name="start"/>
		<p class="labels">Write the number of seconds <u>from the beginning of the video</u> at which you want the video to end:</p>
		<input type="text" name="end"/>
		<p class="labels">Check the box if you don't want to show related videos at the end of the playback:</p>
		<input type="checkbox" name="rel" value="&rel=0"> don't show related videos</input>
		<p class="labels">Check the box if you want the video to autoplay when the player loads:</p>
		<input type="checkbox" name="autoplay" value="&autoplay=1"> autoplay</input>
		<p class="labels">Check the box if you want the player to play the video again and again:</p>
		<input type="checkbox" name="loop" value="&loop=1"> loop</input>
		<p class="labels">Check the box if you want the player not to display video's information (like the title):</p>
		<input type="checkbox" name="showinfo" value="&showinfo=0"> don't show info</input>
		<p class="labels">Check the box if you want to show subtitles by default:</p>
		<input type="checkbox" name="cc_load_policy" value="&cc_load_policy=1"> show subtitles</input>
		<p class="labels">Video progress bar and player controls' options:</p>
		<input type="radio" name="autohide" value="&autohide=2"> the video progress bar will fade out and the player controls will remain visible after the video starts playing (default)</input><br/>
		<input type="radio" name="autohide" value="&autohide=1"> the video progress bar and the player controls will slide out of view a couple of seconds after the video starts playing</input><br/>
		<input type="radio" name="autohide" value="&autohide=0"> the video progress bar and the player controls will be visible throughout the video</input>
		<p class="labels">Player controls' options:</p>
		<input type="radio" name="controls" value="&controls=0"> don't display player controls</input><br/>
		<input type="radio" name="controls" value="&controls=1"> display player controls and load them immediately (default)</input><br/>
		<input type="radio" name="controls" value="&controls=2"> display player controls, but load them after the video starts</input>
		<br/><br/>
		<input type="submit" value="Get the new embed code for your customized Youtube video">
	<form>
	<br/><br/>
	<p><b>*If you don't set an option, the default Youtube value will be used.</b></p>
<?php
}

function cyv_add_page() {
	add_menu_page('Customize Youtube videos','Customize Youtube videos','edit_posts','customize-youtube-videos','customize_youtube_videos_page');
}

add_action('admin_menu','cyv_add_page');

?>