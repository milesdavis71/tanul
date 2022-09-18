<?php

add_action( 'init' , 'bellows_pro_register_skins' , 20 );
function bellows_pro_register_skins(){
	$main = BELLOWS_URL . 'pro/assets/css/skins/';
	
	//bellows_register_skin( 'vanilla' , 				'Vanilla' , 				$main.'vanilla.css' );
	bellows_register_skin( 'red-material' , 		'Red Material' , 			$main.'red-material.css' );
	bellows_register_skin( 'pink-material' , 		'Pink Material' , 			$main.'pink-material.css' );
	bellows_register_skin( 'purple-material' , 		'Purple Material' , 		$main.'purple-material.css' );
	bellows_register_skin( 'deep-purple-material' , 'Deep Purple Material' , 	$main.'deep-purple-material.css' );
	bellows_register_skin( 'indigo-material' , 		'Indigo Material' , 		$main.'indigo-material.css' );
	bellows_register_skin( 'light-blue-material' , 	'Light Blue Material' , 	$main.'light-blue-material.css' );
	bellows_register_skin( 'cyan-material' , 		'Cyan Material' ,			$main.'cyan-material.css' );
	bellows_register_skin( 'teal-material' , 		'Teal Material' , 			$main.'teal-material.css' );
	bellows_register_skin( 'green-material' , 		'Green Material' , 			$main.'green-material.css' );
	bellows_register_skin( 'light-green-material' , 'Light Green Material' , 	$main.'light-green-material.css' );
	bellows_register_skin( 'lime-material' , 		'Lime Material' , 			$main.'lime-material.css' );
	bellows_register_skin( 'yellow-material' , 		'Yellow Material' , 		$main.'yellow-material.css' );
	bellows_register_skin( 'amber-material' , 		'Amber Material' , 			$main.'amber-material.css' );
	bellows_register_skin( 'orange-material' , 		'Orange Material' , 		$main.'orange-material.css' );
	bellows_register_skin( 'deep-orange-material' , 'Deep Orange Material' , 	$main.'deep-orange-material.css' );
	bellows_register_skin( 'brown-material' , 		'Brown Material' , 			$main.'brown-material.css' );
	//bellows_register_skin( 'grey-material' , 		'Grey Material' , 			$main.'grey-material.css' );
	bellows_register_skin( 'blue-grey-material' , 	'Blue Grey Material' , 		$main.'blue-grey-material.css' );
	bellows_register_skin( 'tree' , 				'Tree' , 					$main.'tree.css' );

}

