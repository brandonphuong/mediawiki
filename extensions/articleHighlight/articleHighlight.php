<?php
$wgExtensionCredits['parserhook'][]	=	array(
		'name'			=>	'Article Highlight',
		'author'		=>	'Apekshit Sharma',
		'url'			=>	'http://www.mediawiki.org/wiki/Extension:ArticleHighlight',
		'description'	=>	'This extension allow the articles to be highlighted.'
		);

// include the articleHighlight Class
require( "articleHighlightClass.php" );

// instance of articleHighlight class
$articleHighlightObj						=		new articleHighlight();

// register the extension setup
// $wgExtensionFunctions[] 					= 		array( &$articleHighlightObj, 'setup' );

// define hooks
$wgHooks['ParserFirstCallInit'][]			=		array	( &$articleHighlightObj , 'onParserFirstCallInit' );
$wgHooks['SkinBuildSidebar'][] 				= 		array	( &$articleHighlightObj , 'onSkinBuildSidebar' );
$wgHooks['BeforePageDisplay'][] 			= 		array	( &$articleHighlightObj , 'onBeforePageDisplay' );


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// /


// ending php tag removed