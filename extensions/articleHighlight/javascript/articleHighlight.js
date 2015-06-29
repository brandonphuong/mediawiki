//JAVASCRIPT
//Extension	:	Article Highlight
//Author 	: 	Apekshit Sharma
//Date		:	23rd Decemnber, 2010
//Link		:	http://www.mediawiki.org/wiki/Extension:ArticleHighlight

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//VARIABLE DECLERATIONS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// declare colors

var articleHighlightColors = new Array();
articleHighlightColors['basic'] = '#66ff66' ;
articleHighlightColors['moderate'] = '#ffff66' ;
articleHighlightColors['advanced'] = '#ff3333' ;
articleHighlightColors['humorous'] = '#9999ff' ;
articleHighlightColors['intsting'] = '#ff0099' ;
articleHighlightColors['imp'] = '#cc00cc' ;
articleHighlightColors['unsafe'] = '#000000' ; 

// store the state of highlight. initially all are hidden.

var articleHighlightHidden = new Array();
articleHighlightHidden['basic'] = true ;
articleHighlightHidden['moderate'] = true ;
articleHighlightHidden['advanced'] = true ;
articleHighlightHidden['humorous'] = true ;
articleHighlightHidden['intsting'] = true ;
articleHighlightHidden['imp'] = true ;
articleHighlightHidden['unsafe'] = true ;

// this is to keep the status of hide/show of unsafe contents
articleHighlightUnsafeShown = true; 	// by default all the contents are shown.

// cookie name to store color setting
var cookieName = "articleHighlightColorSettings234";

// 'internationalization' variables


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FUNCTIONS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/*toggles the highlights (show/hide) .Also changes the state variable (articleHighlightHidden[]) of the corrosponding category.
*@param tagName tagname whose corrosponding highlights are to be toggled (shown / hidden)
*@return No return value
*/

function articleHighlightToggle( tagName )	{

	// if currently hidden, then show the highlights
	if( articleHighlightHidden[ tagName ] )	{
		// set the state to true ie highlight is shown
		articleHighlightHidden[ tagName ] = false;
		
		// set the css property to highlight the background
		$( ".articleHighlight-" + tagName ).css( "backgroundColor" , articleHighlightColors[ tagName ] );
		$( ".articleHighlight-" + tagName + " p" ).css( "backgroundColor" , articleHighlightColors[ tagName ] );
		
		// to change the button appearance		
		$( "#articleHighlight-" + tagName+"-button" ).attr( "class" , "articleHighlight-category-show" );
		$( "#articleHighlight-" + tagName+"-button" ).css( "backgroundColor" , articleHighlightColors[ tagName ] );	
		
		if( tagName == "unsafe" )	{
			$( ".articleHighlight-" + tagName + " a").css( "backgroundColor" , "#0645AD" ) ;
			$( ".articleHighlight-" + tagName + " a.new").css( "backgroundColor" , "#BA0000" ) ;
			$( ".articleHighlight-" + tagName + " a.external").css( "backgroundColor" , "#3366BB" ) ;
			$( "#articleHighlight-" + tagName+"-button" ).css( "color" , "#ffffff" );
		}
			
	}
	
	// if currently shown, then hide the highlights
	else {
		// set the state to true ie highlight is shown
		articleHighlightHidden[ tagName ] = true;
		
		// remove the highlight css property
		$( ".articleHighlight-" + tagName ).css( "backgroundColor" , "" );
		$( ".articleHighlight-" + tagName + " p" ).css( "backgroundColor" , "" );
		
		// to change the button appearance		
		$( "#articleHighlight-" + tagName+"-button" ).attr( "class" , "articleHighlight-category-hide" );
		$( "#articleHighlight-" + tagName+"-button" ).css( "backgroundColor" , "" );
		
		if( tagName == "unsafe" )	{
			$( ".articleHighlight-" + tagName + " a").css( "backgroundColor" , "" ) ;
			$( ".articleHighlight-" + tagName + " a.new").css( "backgroundColor" , "" ) ;
			$( ".articleHighlight-" + tagName + " a.external").css( "backgroundColor" , "" ) ;			
			$( "#articleHighlight-" + tagName+"-button" ).css( "color" , "" );

		}
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/*ON MOUSEOVER EVENT
*@param tagName tagname whose corrosponding highlights are to be shown during mouse over event
*/

function articleHighlightOnMouseOver( tagName )	{

		// if the highlight of this category is already "show state" , then no mouseover/mouseout events	
		if( !( articleHighlightHidden [ tagName ] ) )
			return ;
		
		// set the css property to highlight the background
		$( ".articleHighlight-" + tagName ).css( "backgroundColor" , articleHighlightColors[ tagName ] );
		$( ".articleHighlight-" + tagName + " p" ).css( "backgroundColor" , articleHighlightColors[ tagName ] );
		// to change the button appearance		
		$( "#articleHighlight-" + tagName+"-button" ).attr( "class" , "articleHighlight-category-show" );
		$( "#articleHighlight-" + tagName+"-button" ).css( "backgroundColor" , articleHighlightColors[ tagName ] );		
		
		if( tagName == "unsafe" )	{
			$( ".articleHighlight-" + tagName + " a").css( "backgroundColor" , "#0645AD" ) ;
			$( ".articleHighlight-" + tagName + " a.new").css( "backgroundColor" , "#BA0000" ) ;
			$( ".articleHighlight-" + tagName + " a.external").css( "backgroundColor" , "#3366BB" ) ;
			$( "#articleHighlight-" + tagName+"-button" ).css( "color" , "#ffffff" );
		}
	}
	
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	
/*ON MOUSEOUT EVENT
*@param tagName tagname whose corrosponding highlights are to be hidden during mouse out event (if they are not turned on )
*/

function articleHighlightOnMouseOut( tagName )	{
	
	// if the highlight of this category is already "show state" , then no mouseover/mouseout events	
	if( !( articleHighlightHidden [ tagName ] ) )
		return ;
	
	// remove the highlight css property
	$( ".articleHighlight-" + tagName ).css( "backgroundColor" , "" );
	$( ".articleHighlight-" + tagName + " p" ).css( "backgroundColor" , "" );
	
	// to change the button appearance		
	$( "#articleHighlight-" + tagName+"-button" ).attr( "class" , "articleHighlight-category-hide" );
	$( "#articleHighlight-" + tagName+"-button" ).css( "backgroundColor" , "" );
	
	if( tagName == "unsafe" )	{
		$( ".articleHighlight-" + tagName + " a").css( "backgroundColor" , "" ) ;
		$( ".articleHighlight-" + tagName + " a.new").css( "backgroundColor" , "" ) ;
		$( ".articleHighlight-" + tagName + " a.external").css( "backgroundColor" , "" ) ;		
		$( "#articleHighlight-" + tagName+"-button" ).css( "color" , "" );		
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// to remove those category buttons (in sidebar) , whose highlights are not present.
// if no highlights present at all, then display some relevant text.

$( document ).ready( articleHighlightRemoveButtons() );

function articleHighlightRemoveButtons() {
		var empty = true;
		for ( var tagName in articleHighlightColors )	{
			// if no highlights of this category are present, then disable this category button
			if( $( ".articleHighlight-" + tagName ).length == 0 )	{
				$("#articleHighlight-" + tagName + "-button" ).remove();
			}
			else {
				empty = false;
			}
			// if no highlights present in whole article
			if( empty )	{
				$("#p-Highlights div ul").html("<li>Article contains no highlighting.</li>");
			}
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// to show the highlights on mouse over

$(document).ready(
	$( "#p-Highlights div ul li" ).mouseover( 
		function () { 
			articleHighlightOnMouseOver( $(this).attr( "title" ) );
		}
	)
);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// to hide the highlights on mouse out

$(document).ready(
	$( "#p-Highlights div ul li" ).mouseout(
		function () { 
			articleHighlightOnMouseOut( $(this).attr( "title" ) ) ;
		}
	)
);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/*display(/hide) color palettes to change colors for categories
*@param tagName tagname whose corrosponding palette is to be shown/hidden
*@return No return value
*/

function articleHighlightShowPalette( tagName )	{
	$("#articleHighlight-colorPalette-" + tagName ).toggle('fast');
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/*if user selects a different color, then change the color in "articleHighlightColors" variable, and make changes as appropriate
*@param tagName The tagname for which color is to be changed
*@param color New color value '#xxxxxx'
*@return No return value
*/
function articleHighlightChangeColor( tagName , color )	{
	
	articleHighlightColors[ tagName ] = color ;
	$( "#articleHighlight-categorycolorsample-" + tagName ).css( "backgroundColor" , color );
	
	// if the highlight is currently shown, then also change color of highlights in article
	if( !( articleHighlightHidden [ tagName ] ) )	{
			articleHighlightToggle( tagName );
			articleHighlightToggle( tagName );
	}
	
	// update cookie
	articleHighlightSaveCookie();
		
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// safe the changes to a cookie so that the same colors settings and show/hide (unsafe contents) can be used on other pages.

function articleHighlightSaveCookie()	{
	
	var cookieValue = "";
	
	for ( var tagName in articleHighlightColors )	{
		cookieValue += tagName + ":" + articleHighlightColors[tagName] + "," ;
	}
	
	// this info is stored only if the unsafe contents are hidden , so storing 'false' is redundant.
	// but 'false' stored to keep the syntax common
	if ( ! ( articleHighlightUnsafeShown ) )	{
		cookieValue += "articleHighlightUnsafeShown:false," ;
	}
	
	
	var date = new Date();
	date.setTime(date.getTime()+(10*24*60*60*1000));		//cookie valid for 10 days
	var expires = "; expires="+date.toGMTString();
	document.cookie = cookieName + "=" + escape(cookieValue) + expires ;
	return true;	
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



// load the highlight color settings from cookie

$j( document ).ready( articleHighlightLoadColors() );
	
	function articleHighlightLoadColors() {
		if ( document.cookie.length > 0 )	{
			start = document.cookie.indexOf( cookieName + "=" );
			if ( start != -1 )	{  //cookie exists
				
				start = document.cookie.indexOf( "=" , start ) + 1;		//start point of cookieValue
				
				end = document.cookie.indexOf( ";" , start );
			  	if ( end == -1 ) {
			  	end = document.cookie.length;
			  	}
			  
			  	var cookieValue = unescape( document.cookie.substring( start ,  end ) );
			  	cookieValue = cookieValue.split( "," );
				
				var temp = 0;
			  	for ( temp ; temp < cookieValue.length ; temp++)	{
					tagColor = cookieValue[ temp ].split( ":" );
					if( tagColor[0] == "articleHighlightUnsafeShown" )	{
						//by default , 'articleHighlightUnsafeShown' is true, but the user has turned it off.
						//so will call the function 'articleHighlightShowHideUnsafe' once each new page is loaded.
						$( "#articleHighlightUnsafeContentToggleButton" ).triggerHandler( "click" );
					}
					else	{
				 		articleHighlightColors[ tagColor[0] ] = tagColor[1];
					}
				}		  
		  	}				
		}
		for ( var tagName in articleHighlightColors )	{
			$( "#articleHighlight-categorycolorsample-" + tagName ).css( "backgroundColor" , articleHighlightColors[ tagName ] );
		}
		
	}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/* show/hide unsafe contents
*@param button The elements which was clicked
@return No return value
*/
function articleHighlightShowHideUnsafe(button)	{
	
	// if currently the contents are shown ....then hide them
	if( articleHighlightUnsafeShown )	{
		$( ".articleHighlight-unsafe").css( "display" , "none"); 
		button.innerHTML = "show";
		articleHighlightUnsafeShown = false ;
	}
	else {	// currently the contents are hidden ....then show them
		$( ".articleHighlight-unsafe").css( "display" , "");
		button.innerHTML = "hide";
		articleHighlightUnsafeShown = true ;
	}
	
	// update cookie
	articleHighlightSaveCookie() ;
		
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////