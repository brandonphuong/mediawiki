<?php

// CLASS DEFINITION STARTS

class articleHighlight {

	// VARIABLES

	// categories as an array of: tag => category name(will be displayed as button, so keep it meaningful)
	// 'tag' will also be the class name in the span tag.
	private $categories	=	array(
			'basic'			=>	'basic',
			'moderate'		=>	'moderate',
			'advanced'		=>	'advanced',
			'humorous'		=>	'humorous',
			'intsting'		=>	'interesting',
			'imp'			=>	'important',
			'unsafe'		=>	'unsafe'
		);

	// not used anywhere in this script. to change default colors, make change in javascript file
	private $defaultHighlightColor	=	array(
			'basic'			=>	'#66ff66',
			'moderate'		=>	'#ffff66',
			'advanced'		=>	'#ff6666',
			'humorous'		=>	'#9999ff',
			'intsting'		=>	'#996600',
			'imp'			=>	'#cc00cc',
			'unsafe'		=>	'#000000'
		);

	// Color palette (majority colors above) taken from  : http://tango.freedesktop.org/Tango_Icon_Theme_Guidelines
	// Thanks to creator.
	private $colorPalette	=	array(
			"#66ff66" , "#8ae234" , "#4e9a06" , "#ffff66" , "#fcaf3e" , "#f57900" ,
			"#ff6666" , "#ef2929" , "#cc0000" , "#729fcf" , "#9999ff" , "#3465a4" ,
			"#ff99ff" , "#cc00cc" , "#ad7fa8" , "#75507b" , "#d3d7cf" , "#888a85" ,
			"#e9b96e" , "#c17d11" , "#8f5902" , "#ce5c00" , "#ff9966" , "#429a06"
		);

	// no of colors to be shown in one row in color palette
	private $widthPalette	=	6;


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// /


	// CONSTRUCTOR

	public function __construct() {
		// nothing to do
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// /


	// associate custom tags with the parser hook.

	public function onParserFirstCallInit( &$parser ) {

		// get an array of all tags that will be used
		$tagList	=	array_keys( $this->categories );

		foreach ( $tagList as $tag )	{

			// add parser hooks.
			$parser	->	setHook( $tag , array ( &$this , $tag ) ) ;
		}

		// will add internationalization later
		// $wgExtensionMessagesFiles['articleHighlight'] = dirname( __FILE__ ) . '/articleHighlight.i18n.php';

		return	true;
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// /


	// functions to deal with corrosponding tags

	function basic( $input , $args , $parser , $frame )	{
		$input	=	$parser	->	recursiveTagParse( $input , $frame );
		return	"<span class=\"articleHighlight-basic\" >" . $input . "</span>";
	}

	function moderate( $input , $args , $parser , $frame )	{
		$input	=	$parser-> recursiveTagParse( $input , $frame );
		return	"<span class=\"articleHighlight-moderate\" >" . $input . "</span>";
	}

	function advanced( $input , $args , $parser , $frame )	{
		$input	=	$parser-> recursiveTagParse( $input , $frame );
		return	"<span class=\"articleHighlight-advanced\" >" . $input . "</span>";
	}

	function humorous( $input , $args , $parser , $frame )	{
		$input	=	$parser-> recursiveTagParse( $input , $frame );
		return	"<span class=\"articleHighlight-humorous\" >" . $input . "</span>";
	}

	function intsting( $input , $args , $parser , $frame )	{
		$input	=	$parser-> recursiveTagParse( $input , $frame );
		return	"<span class=\"articleHighlight-intsting\" >" . $input . "</span>";
	}

	function imp( $input , $args , $parser , $frame )	{
		$input	=	$parser-> recursiveTagParse( $input , $frame );
		return	"<span class=\"articleHighlight-imp\" >" . $input . "</span>";
	}

	function unsafe( $input , $args , $parser , $frame )	{
		$input	=	$parser-> recursiveTagParse( $input , $frame );
		return	"<span class=\"articleHighlight-unsafe\" >" . $input . "</span>";
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// /


	/*function to generate color palette in sidebar.color palette lies inside a div element and is initially hidden.
	*@param $tagName tagname for which palette is to be created.
	*@return returns the html code of the palette.
	*/

	function generateColorPalette( $tagName )	{
		$output		=	"";
		$output		.=	"<div id = \"articleHighlight-colorPalette-$tagName\" style=\"display:none;\" > \n";
		$temp		=	0;
		foreach ( $this->colorPalette as $color )	{
			if (	$temp	==	$this->widthPalette	)	{
				$output		.=	"<br/> \n";
				$temp		=	0;
			}
			$output .= "<div class = \"articleHighlight-colorPalette-color\" "
								. "style=\"background-color:$color;\" "
								. "onClick = \" articleHighlightChangeColor( '$tagName' , '$color' ) \"></div> \n";
			$temp++;
		}

		$output		.=	"<br/></div> \n";
		return	$output;
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// /


	// function to add menu in left sidebar. will add a slider box, from where highlights can be hidden/shown.

	public function onSkinBuildSidebar( $skin, &$bar ) {
		$menu	=	"";
		$menu	.=	"<ul id=\"articleHighlight-category-list\" class=\"articleHighlight-category-list\"> \n";
		foreach ( $this->categories as $tagName => $category )	{
			$menu	.=	"<li id=\"articleHighlight-$tagName-button\" class=\"articleHighlight-category-hide\" onClick=\"articleHighlightToggle('$tagName' )\" title=\"$tagName\" > &nbsp;" ;
			$menu	.=	$category ;

			// don't allow color change for the "unsafe" category.that will defeat the purpose of it.
			if ( $tagName != "unsafe" )	{
				$menu	.=	"&nbsp;&nbsp;<a class = \"articleHighlight-showPalette-button\" onclick = \"articleHighlightShowPalette( '$tagName' ); event.cancelBubble=true; \" onmouseover=\"event.cancelBubble=true;\" onmouseout=\"event.cancelBubble=true;\" >color</a>";
				$menu	.=	"&nbsp;<div 	id=\"articleHighlight-categorycolorsample-$tagName\""
										. "class=\"articleHighlight-colorPalette-color\""
										. "style=\"margin:1px; border:0px;\" ></div>";
			}
			if ( $tagName	==	"unsafe" )	{
				$menu	.=	"&nbsp;&nbsp;<a class = \"articleHighlight-showPalette-button\" id=\"articleHighlightUnsafeContentToggleButton\" onclick = \"articleHighlightShowHideUnsafe(this); event.cancelBubble=true; \" >hide</a>";
			}
			$menu	.=	"</li> \n";
			$menu	.=	$this->generateColorPalette( $tagName );

		}
		$menu	.=	"</ul>";
		$bar[ 'Highlights' ] = $menu ;

		return true;
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// /


	// function to add javascript and stylesheet

	public function onBeforePageDisplay( &$out )	{
		global	$wgExtensionAssetsPath;
		// add script to page
		$out	->	addScript(
			"<script 	src=\"$wgExtensionAssetsPath/articleHighlight/javascript/articleHighlight.js\"
						type=\"text/javascript\"></script> "	);
		$out	->	addScript(
			"<script 	src=\"$wgExtensionAssetsPath/articleHighlight/javascript/articleHighlightEditor.js\"
						type=\"text/javascript\"></script> "	);
		$out	->	addStyle( "$wgExtensionAssetsPath/articleHighlight/css/articleHighlight.css" );

		return	true;
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// /


// end of class
}