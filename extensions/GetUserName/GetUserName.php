
<?php
if ( !defined( 'MEDIAWIKI' ) ) {
    die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}
 
$wgExtensionCredits['parserhook'][] = array(
    'name' => 'GetUserName',
    'version' => '1.0',
    'url' => 'https://www.mediawiki.org/wiki/Extension:GetUserName',
    'author' => 'Ejcaputo',
    'description' => "Allows the current user's name to be put in a page"
);  

$wgHooks[ 'ParserFirstCallInit' ][] = "ExtGetUserName::setup";
$wgHooks[ 'LanguageGetMagic' ][]  = 'ExtGetUserName::languageGetMagic';
class ExtGetUserName {
    private static $parserFunctions = array(
        'USERNAME' => 'getUserName',
    );
 
    public static function setup( &$parser ) {
        // register each hook
        foreach( self::$parserFunctions as $hook => $function )
            $parser->setFunctionHook( $hook,
                array( __CLASS__, $function ), SFH_OBJECT_ARGS );
 
        return true;
    }
 
    public static function languageGetMagic( &$magicWords, $langCode ) {
        $magicWords[ 'USERNAME' ] = array( 0, 'USERNAME' );
        return true;
    }
 
    public static function getUserName( &$parser, $frame, $args ) {
        $parser->disableCache();
        global $wgUser;
        return trim( $wgUser->getName() );
    }
}
