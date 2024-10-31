<?php

# If form is send
# ---------------

if($_POST['neeed_hidden'] == 'Y') {
	
    update_option('neeed_api_key', trim($_POST['neeed_api_key']));
    update_option('neeed_art_display', $_POST['neeed_art_display']);
    
    
    ?>
    
    <div class="updated"><p><strong>Vos options ont été sauvegardées</strong></p></div>
    
    <?php 


} 


# Normal page display options
# --------------------------- 

$neeed_api_key 		= get_option('neeed_api_key');
$neeed_art_display 	= get_option('neeed_art_display');


	
?>


<?php 

# No minimal config setted 
# ------------------------

if(!$neeed_api_key){

?>

<div id="neeed_admin" class="wrap">
	
	<div class="no-key">
		
		<img src="http://neeed.com/core/img/logo.png">
		
		<h1>Entrez votre clef d'API</h1>
		<p>Si vous ne disposez pas d'une clef d'API, connectez vous à votre <a href="http://neeed.com">compte Neeed</a> et rendez vous dans les paramètres de votre compte</p>
		
		<form name="neeed_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			
			<p>Clef d'API : <input type="text" name="neeed_api_key" value="<?php echo $neeed_api_key; ?>" size="20" placeholder="exemple : DsfI87kjd" > </p>
			
			<input type="hidden" name="neeed_hidden" value="Y">
			<p class="submit">
	        <input class="button-primary" type="submit" name="Submit" value="Mettre à jour" />
	        </p>
			
		</form>
	
	</div>

</div>


<?php 
	
}

# We got the min. config
# ------------------------

else{

?>


<div id="neeed_admin" class="wrap">
	
	<div class="intro">
		
		<div class="alignleft" style="margin-right:10px;">
			<img src="http://neeed.com/core/img/logo.png" class="logo">
		</div>
		
		<div class="alignleft">
			<h1> Panneau de configuration </h1>
			<p>Configurez à l'aide des paramètres suivant l'affichage de votre widget. <br>
				Ce plugin est maintenu par <a href="http://56k.be">56k</a> pour <a href="http://neeed.com">Neeed.com</a>
			</p>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
	<div class="col">
		
		<h2>Configuration du widget</h2>
		
		<form name="neeed_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
						
			<div class="form_group">
				<label>Clef d'API</label>
				<input type="text" name="neeed_api_key" value="<?php echo $neeed_api_key; ?>" size="20">
			</div>
			
			<div class="form_group">
				<label>Nombre d'articles affichés par défaut</label>
				<input type="text" name="neeed_art_display" value="<?php echo $neeed_art_display; ?>" size="20">
			</div>
						
			
			<input type="hidden" name="neeed_hidden" value="Y">
			
			
			<p class="submit">
				<input class="button-primary" type="submit" name="Submit" value="Mettre à jour" />
			</p>
			
		
		</form>
		
		
		<h2>Widget personnalisé</h2>
		
		<p> Utilisez la fonction php : <br>
		<pre> display_my_neeed( $title , $h2_class , $ul_class , $li_class  , $img_class  , $a_class )  </pre>
		</p>
		
	
	</div>
	
	<div class="col">
	
		<h2>Utilisation du shortag</h2>
		
		<p><b>[neeed]</b></p>
		<p>Affiche la liste de vos 6 derniers produits ajoutés dans vos listes publiques sur Neeed.com</p>
		
		<hr>
		
		<p><b>[neeed user="amaury" list="techno" limit="6" ]</b></p>
		<p>
			<ul>
				<li>User : Optionnel, permet d'afficher les produits publique d'un autre utilisateur</li>
				<li>List : Optionnel, permet d'afficher les produits d'une liste publique d'un utilisateur ( option user obligatoire ) </li>
				<li>Limit : Optionnel, nombre de produit à afficher ( max = 15, default = 6 ) </li>
			</ul>
			
		</p>
		
				
	
	</div>
	
	<div class="clearfix"></div>
	

</div>

<?php 
	
}	
	
?>
