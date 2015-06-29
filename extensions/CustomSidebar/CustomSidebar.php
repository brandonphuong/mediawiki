<?php

/***********************************************************
 * Name:     CustomSidebar
 * Desc:     Easy system for specifying custom sidebars on a per-page basis
 *
 * Version:  0.3.0
 *
 * Author:   Swiftly Tilting (contact@swiftlytilting.com)
 * Homepage: http://www.mediawiki.org/wiki/Extension:CustomSidebar
 *           http://www.swiftlytilting.com/
 *
 * License:  GNU GPL
 *
 ***********************************************************
 */

$wgExtensionCredits['parserhook'][] = array(
       'name' => 'CustomSidebar',
       'author' =>'SwiftlyTilting',
       'url' => 'http://www.mediawiki.org/wiki/Extension:CustomSidebar',
       'description' => 'Easy system for specifying custom sidebars on a per-page and/or per-user group basis',
       'descriptionmsg' => "customsidebar-desc", // Same as above but name of a message, for i18n - string, added in 1.12.0
       'version' => '0.3.0',
       'path' => __FILE__,
       );

//Avoid unstubbing $wgParser on setHook() too early on modern (1.12+) MW versions, as per r35980
if ( defined( 'MW_SUPPORTS_PARSERFIRSTCALLINIT' ) ) {
   $wgHooks['ParserFirstCallInit'][] = 'efCustomSideBarInit';
} else { // Otherwise do things the old fashioned way
   $wgExtensionFunctions[] = 'efCustomSideBarInit';
}

function efCustomSideBarInit() {
   global $wgParser;
   $wgParser->setHook( 'sidebar', 'efCustomSideBar' );
   return true;
}

function efCustomSideBar( $input, $args, $parser ) {
   // We can't count of the tag being read if the page is cached.  So instead of this code performing any task
   // we leave it here to easily remove the <sidebar> tag read in the SkinBuildSidbar hook
   // aka lazy way to clear tag
   return '';
}

$wgHooks['SkinBuildSidebar'][] = 'fnSidebarHook';

function fnSidebarHook($skin, &$bar)
{
      // this is mostly just the standard sidebar processing function with a custom loader

      global $parserMemc, $wgEnableSidebarCache, $wgSidebarCacheExpiry, $wgParser, $wgUser;
      global $wgLang, $wgTitle, $wgArticle, $wgDefaultSideBarText, $wgUser,  $wgDefaultSideBarGroupText, $wgDefaultSidebarNSText;

      $NewSideBar = false;

      if (isset($wgArticle) and $wgArticle)
      {
         $pagetext = $wgParser->preprocess( $wgArticle->getContent(), $wgArticle->getTitle(), ParserOptions::newFromUser( $wgUser ));

         if (preg_match('%\<sidebar\>(.*)\</sidebar\>%isU', $pagetext, $matches))
         {	$NewSideBar = $matches[1];
         }  
         else if (isset($wgDefaultSideBarText) and $wgDefaultSideBarText !== false)
         {	$NewSideBar = $wgDefaultSideBarText;
         }
      }
	
	
		// sidebar cache code
      wfProfileIn( __METHOD__ );

      $key = wfMemcKey( 'sidebar', $wgLang->getCode() );

      if ( $wgEnableSidebarCache ) {
         $cachedsidebar = $parserMemc->get( $key );
         if ( $cachedsidebar ) {
            wfProfileOut( __METHOD__ );
            return $cachedsidebar;
         }
      }
		// end cache code
		
      $new_bar =  fnCustomSidebarProcess($NewSideBar);

      if ((count($new_bar) === 0) && ($wgDefaultSideBarText !== false))
      {
         $new_bar = $bar;
      }
		
		// Add customs bar based on user groups
      $groups = array_reverse($wgUser->mGroups);
      foreach($groups as $n => $v)
      {  if ( is_array($wgDefaultSideBarGroupText) && array_key_exists($v, $wgDefaultSideBarGroupText))
         {
            $new_bar = array_merge($new_bar, fnCustomSidebarProcess($wgDefaultSideBarGroupText[$v]));
         }
      }
		
		// Add custom bar based on namespace
      $ns = $wgTitle->getNamespace();
      if (is_array($wgDefaultSidebarNSText) && array_key_exists($ns , $wgDefaultSidebarNSText))
      {
         $new_bar = array_merge($new_bar, fnCustomSidebarProcess($wgDefaultSidebarNSText[$ns]));
      }

      if (count($new_bar) > 0)
      {
         $bar = $new_bar;
      }
		
		// sidebar cache code
      if ( $wgEnableSidebarCache ) $parserMemc->set( $key, $bar, $wgSidebarCacheExpiry );

      wfProfileOut( __METHOD__ );
		// end sidebar cache code
		
      return true;
}

function fnCustomSidebarProcess($NewSideBar)
{     global $wgParser, $wgUser, $wgArticle, $wgTitle;
      
      $NewSideBar = fnCustomSidebarPreProcess($NewSideBar);
      
      // custom loader
      if ($NewSideBar !== false)
      {  
      	if (strpos(trim($NewSideBar), '*') === 0)
         {  $text = $NewSideBar;
         }
         else
         {  $text = $NewSideBar;
            do
            {  
            	$oldtext = $text;
               if (($titleFromText = Title::newFromText($text)))
               {  
               	$article = new Article($titleFromText,0);
                  $text = $article->getContent();
                  $text = preg_replace('%\<noinclude\s*\>(.*)\</noinclude\s*\>%isU','',$text);
                  $text = fnCustomSidebarPreProcess($text);
               }
                              
            } while ( $text !== $oldtext);
         }

         $lines = explode( "\n",  $text );
      }
      else
      {  
         return array();
      }

      $new_bar = array();

      $heading = '';
      
      // taken directly from MediaWiki source v1.14.0
      foreach ($lines as $line) {
         if (strpos($line, '*') !== 0)
            continue;
         if (strpos($line, '**') !== 0) {
            $line = trim($line, '* ');
            $heading = $line;
            if( !array_key_exists($heading, $new_bar) ) $new_bar[$heading] = array();
         } else {
            if (strpos($line, '|') !== false) { // sanity check
               $line = array_map('trim', explode( '|' , trim($line, '* '), 2 ) );
               $link = wfMsgForContent( $line[0] );
               if ($link == '-')
                  continue;

               $text = wfMsgExt($line[1], 'parsemag');
               if (wfEmptyMsg($line[1], $text))
                  $text = $line[1];
               if (wfEmptyMsg($line[0], $link))
                  $link = $line[0];

               if ( preg_match( '/^(?:' . wfUrlProtocols() . ')/', $link ) ) {
                  $href = $link;
               } else {
                  $title = Title::newFromText( $link );
                  if ( $title ) {
                     $title = $title->fixSpecialName();
                     $href = $title->getLocalURL();
                  } else {
                     $href = 'INVALID-TITLE';
                  }
               }

               $new_bar[$heading][] = array(
                  'text' => $text,
                  'href' => $href,
                  'id' => 'n-' . strtr($line[1], ' ', '-'),
                  'active' => false
               );
            } else { continue; }
         }
      }
		// End Mediawiki source

      if (count($new_bar) > 0)
      {  return $new_bar;
      }
      else
      {  return array();
      }
}

// processes templates and wiki magic words, plus any add'l custom magic words
function fnCustomSidebarPreProcess($text)
{
   global $wgTitle, $wgParser, $wgUser;
	
	$text = str_ireplace ('{{#__username}}',$wgUser->mName, $text);
   return $wgParser->preprocess( preg_replace('%\<noinclude\>(.*)\</noinclude\>%isU','',$text),  $wgTitle, ParserOptions::newFromUser( $wgUser ));

}

?>