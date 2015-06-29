// Check that the toolbar is available
if ( typeof $j != 'undefined' && typeof $j.fn.wikiEditor != 'undefined' ) {
	// Execute on load
	$j( function() {
	
 
		// To add a group to an existing toolbar section:
		$j( '#wpTextbox1' ).wikiEditor( 'addToToolbar', {
			'section': 'advanced',
			'groups': {
				'highlight': {
					'label': 'Highlighter' // or use labelMsg for a localized label, see above
				}
			}
		} );
 
		// To add a button to an existing toolbar group:
		$j( '#wpTextbox1' ).wikiEditor( 'addToToolbar', {
			'section': 'advanced',
			'group': 'highlight',
			'tools': {
				'highlighter': {
					label: 'highlighter', // or use labelMsg for a localized label, see above
					type: 'select',
					list: {
						'basic' : {
								label: 'basic',
								action: {
									type: 'encapsulate',
									options: {
										pre: "<basic>",
										periMsg: 'basic level info',
										post: "</basic>"
										}
								}
						},
						'moderate' : {
								label: 'moderate',
								action: {
									type: 'encapsulate',
									options: {
										pre: "<moderate>",
										periMsg: 'moderate level info',
										post: "</moderate>"
										}
								}
						},
						'advanced' : {
								label: 'advanced',
								action: {
									type: 'encapsulate',
									options: {
										pre: "<advanced>",
										periMsg: 'advanced stuff',
										post: "</advanced>"
										}
								}
						},
						'humorous' : {
								label: 'humorous',
								action: {
									type: 'encapsulate',
									options: {
										pre: "<humorous>",
										periMsg: 'humorous stuff',
										post: "</humorous>"
										}
								}
						},
						'interesting' : {
								label: 'interesting',
								action: {
									type: 'encapsulate',
									options: {
										pre: "<intsting>",
										periMsg: 'interesting stuff',
										post: "</intsting>"
										}
								}
						},
						'important' : {
								label: 'important',
								action: {
									type: 'encapsulate',
									options: {
										pre: "<imp>",
										periMsg: 'important stuff',
										post: "</imp>"
										}
								}
						},
						'unsafe' : {
								label: 'unsafe',
								action: {
									type: 'encapsulate',
									options: {
										pre: "<unsafe>",
										periMsg: 'unsafe(adult) stuff',
										post: "</unsafe>"
										}
								}
						}
					}					
				}
			}
		} );
		
	} );
}// JavaScript Document