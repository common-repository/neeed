<?php
/**
 * Plugin Name: Neeed
 * Plugin URI: http://neeed.com/blog/
 * Description: Il est utilisé par des milliers de collectionneurs comme liste de leurs envies... Rejoignez vous-aussi les utilisateurs de Neeed et affichez sur votre blog, dans vos articles ou vos pages la liste de vos envies ! L'utilisation du plugin officiel Neeed requiert un compte <a href="http://neeed.com" target="_blank">Neeed.com</a>
 * Version: 1.1.4
 * Author: Amaury Lesplingart 
 * Author URI: http://56k.be
 * License: GPLv2 or later
 */
 
 
function neeed_admin_actions() {
	add_menu_page("Gestion du plugin Neeed", "Neeed.com", 1, "neeed", "neeed_admin" );
}

function neeed_admin() {
    include('neeed_admin.php');
}
 
add_action('admin_menu', 'neeed_admin_actions');


function neeed_css() {
	wp_register_style('neeed_css', plugins_url('style.css',__FILE__ ));
	wp_enqueue_style('neeed_css');
	//wp_register_script( 'css_js', plugins_url('your_script.js',__FILE__ ));
	//wp_enqueue_script('css_js');
}
add_action( 'admin_init','neeed_css');

add_action( 'wp_loaded','neeed_css');


function display_my_neeed($title = '' , $h2_class = '' , $ul_class = '' , $li_class = '' , $img_class = '' , $a_class = '' ) {
 	
 	$neeed_api_key 		= get_option('neeed_api_key');
	$neeed_art_display 	= get_option('neeed_art_display');
	
	if(empty($title)){
		
		$title 	= '<a href="http://neeed.com">Neeed ♥</a>';
	}
	
 	
 	if(!$neeed_api_key){
	 	return false; 
	 	exit(); 
 	}
 	else{
 		
 		
 		$bloginfo 	= get_bloginfo( 'url' );
 		
 		$url 		= 'http://neeed.com/api/get.php?key='. trim($neeed_api_key) .'&author_url='.$bloginfo.'&mode=articles&count='.$neeed_art_display;
	 	
	 	if (ini_get('allow_url_fopen') == true) {
	 		$content 	= file_get_contents($url);
	 	}
	 	else{
		 	$curl = curl_init($url);
		 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		 	$content = curl_exec($curl);
		 	curl_close($curl);
	 	}
	 	
	 	
	 	$articles 	= json_decode($content, true);
 	
 	
 		if(isset($articles['error'])){
		
			$html = '<div id="neeed_error">';
			$html .= '<p>Une erreur semble être survenue lors de la configuration de la galerie</p>';
			$html .= '<p> Voici le message d\'erreur : '.$articles['error']['msg'].' </p>';
			$html .= '</div>';
			
			return $html; 
			exit(); 
		
		
		}
		else{
 	
		 	
		 	$html		= '<div id="neeed_widget">';	 	
		 	$html 		.= '<h2 class="'.$h2_class.'">'.$title.'</h2>'; 		 	
		 	$html 		.= '<ul class="'.$ul_class.'">';
		 		 	
		 	foreach($articles['article'] as $article){
			 	
			 	$html 	.= '
			 	<li class="'.$li_class.'"> 				
			 		<a class="'.$a_class.'" href="'. urldecode($article['url']) .'" target="_blank" > 
			 			<img class="'.$img_class.'" src="'. urldecode($article['image']) .'" > 
			 		</a>
			 	</li>
			 	';
			 	
		 	}
		 	
		 	$html 		.= '</ul>';
		 	$html 		.= '<p class="powered">Retrouvez <a href="http://neeed.com/'.$articles['user']['username'].'"> '.$articles['user']['username'].' sur Neeed.com </a></p>';
		 	
		 	$html 		.= '</div>';
		 	
		 		 	
		 	return $html; 
		 
		 }
	 	
	 	
 	}
 	 	
 
}


add_shortcode( 'neeed', 'get_neeed_articles' );

function get_neeed_articles($atts){
	
	$neeed_api_key 	= get_option('neeed_api_key');
	
	if(!$neeed_api_key){
	
		$html = '<div id="neeed_error">';
		$html .= '<p>Votre clef d\'API Neeed n\'est pas configurée</p>';
		$html .= '</div>';
		
		return $html; 
		exit(); 
		
	}
	else{
			
		$neeed_limit 	= $atts['limit'];
		
		if(!$neeed_limit){
			$neeed_limit = get_option('neeed_art_display');
		}
	
		$neeed_user 	= $atts['user'];
		$neeed_list 	= $atts['list'];
		
		$bloginfo 	= get_bloginfo( 'url' );	
		
		$url 		=  'http://neeed.com/api/get.php?key='. $neeed_api_key .'&author_url='. $bloginfo .'&mode=articles&count='. $neeed_limit .'&user='. $neeed_user .'&list='. $neeed_list ;
				
		if (ini_get('allow_url_fopen') == true) {
	 		$content 	= file_get_contents($url);
	 	}
	 	else{
		 	$curl = curl_init($url);
		 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		 	$content = curl_exec($curl);
		 	curl_close($curl);
	 	} 
		
			 		 	
		$articles 	= json_decode($content, true);
			
		
		if(isset($articles['error'])){
			
			$html = '<div id="neeed_error">';
			$html .= '<p>Une erreur semble être survenue lors de la configuration de la galerie</p>';
			$html .= '<p> Voici le message d\'erreur : '.$articles['error']['msg'].' </p>';
			$html .= '</div>';
			
			
		}
		else{
			
		
		
			if($articles AND is_array($articles)){ 
			
				$html 		= '<div id="neeed_gal"> '; 		 	
			 	
			 	$html		.= '<div class="neeed_owner"> ';
			 	
			 	$html		.= '<img src="'.$articles['user']['pic'].'" class="neeed_profile" title="'.$articles['user']['username'].'"> ';
			 	$html		.= '<h4><a href="http://neeed.com/'.$articles['user']['username'].'" target="_blank" title="Découvrez le profile de '.$articles['user']['username'].' sur Neeed.com" >'.$articles['user']['username'].'</a></h4> ';
			 	$html		.= '<p> '.$articles['user']['bio'].'  </p>';
			 	
			 	$html		.= '</div> ';  
			 	
			 	
			 	$html 		.= '<ul class="">';
			 		 	
			 	foreach($articles['article'] as $article){
				 	
				 	$html 	.= '
				 	<li class=""> 				
				 		<a class="" href="'. urldecode($article['url']) .'" target="_blank" > 
				 			<img class="" src="'. urldecode($article['image']) .'" title="'.$article['title'].'" > 
				 		</a>
				 	</li>
				 	';
				 	
			 	}
			 	
			 	$html 		.= '<div class="clearfix"></div>';
			 	$html 		.= '</ul>';
			 	$html 		.= '</div>';
			 	
			 }
			 else{
				 
				 $html = ''; 
				 
			 }
		 
		 
		 }
	 	
	 		 	
	 	return $html;
 	
 	}
	
}


class neeedwidget extends WP_Widget{
  
	function neeedwidget(){
		
		$widget_ops = array('classname' => 'neeedwidget', 'description' => 'Affichage de vos produits Neeed' );
		$this->WP_Widget('neeedwidget', 'Neeed', $widget_ops);
	
	}
 
	function form($instance){
		
		$instance 	= wp_parse_args( (array) $instance, array( 'title' => '', 'nbr' => '' ) );
		
		$title   	= $instance['title'];
		$nbr 		= $instance['nbr'];
		
		///////////////////////////////////
		
		$neeed_api_key 	= get_option('neeed_api_key');
		
		if(!$neeed_api_key){
		
			?>
			
			<p style="color:#E96E6E">Veuillez configurer votre clef d'API Neeed</p>
			
			
			<?php
			
		}
		else{
			
			?>
			
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Titre du widget: 
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
				</label>
			</p>
			
			
			<p>
				<label for="<?php echo $this->get_field_id('content'); ?>">Nombre d'articles affichés 
					<input class="widefat" id="<?php echo $this->get_field_id('nbr'); ?>" name="<?php echo $this->get_field_name('nbr'); ?>" type="text" value="<?php echo attribute_escape($nbr); ?>" />
				</label>
				
			</p>
	    
			

			
			
			<?php 
			
		}
		

	}
 
	function update($new_instance, $old_instance){
	
		$instance = $old_instance;
		
		// Retrieve Fields
		$instance['title']   = strip_tags($new_instance['title']);
		$instance['nbr'] 	= strip_tags($new_instance['nbr']);
		
		return $instance;
	
	}
	
	function widget($args, $instance){
		
		$neeed_api_key 	= get_option('neeed_api_key');
		
		if(!$neeed_api_key){
			
			echo $before_widget;
			echo '<p>Clef Neeed non configurée</p>';
			echo $after_widget;
			
			
		}
		else{
			
			
			extract($args, EXTR_SKIP);
			
			
			$title		= empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
			$limit 		= empty($instance['nbr']) ? ' ' : apply_filters('widget_title', $instance['nbr']);
			$title		= trim($title); 
			
			
			$bloginfo 	= get_bloginfo( 'url' );	
		
			
			$url 		= 'http://neeed.com/api/get.php?key='. $neeed_api_key .'&author_url='.$bloginfo.'&mode=articles&count='. $limit;
			
			if (ini_get('allow_url_fopen') == true) {
		 		$content 	= file_get_contents($url);
		 	}
		 	else{
			 	$curl = curl_init($url);
			 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			 	$content = curl_exec($curl);
			 	curl_close($curl);
		 	}
			 
			$articles 	= json_decode($content, true);
			
			
			$html		= $before_widget; 
			
			$html		.= '<div id="neeed_widget">';
			
						
			if ($title != ''){
		 	$html 		.= $before_title . $title . $after_title; 
		 	}
		 	else{
			$html		.= $before_title.'<a href="http://neeed.com">Neeed ♥</a>'.$after_title;
		 	}	 	
		 	
		 	
		 	$html 		.= '<ul>';
		 		 	
		 	foreach($articles['article'] as $article){
			 	
			 	$html 	.= '
			 	<li> 				
			 		<a href="'. urldecode($article['url']) .'" target="_blank" > 
			 			<img src="'. urldecode($article['image']) .'" > 
			 		</a>
			 	</li>
			 	';
			 	
		 	}
		 	
		 	$html 		.= '</ul>';
		 	$html 		.= '<div class="clearfix"></div>';
		 	$html 		.= '<p class="powered">Retrouvez <a href="http://neeed.com/'.$articles['user']['username'].'"> '.$articles['user']['username'].' sur Neeed.com </a></p>';
		 	$html 		.= '</div>';
		 	$html 		.= $after_widget;
		 	$html 		.= '<div class="clearfix"></div>';
						
			
			echo $html; 
			
			
		}
		
		
		
	}
	

}

add_action( 'widgets_init', create_function('', 'return register_widget("neeedwidget");') );

?>