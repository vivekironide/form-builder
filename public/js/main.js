$( document ).ready( function() {
    let sidebar = $( '.ui.sidebar' );

    $( '.accordion' ).accordion();

    $( '.dropdown' ).dropdown();

    $( '.checkbox' ).checkbox();

    sidebar.sidebar( {
        dimPage   : false,
        transition: 'push',
        exclusive : false,
        closable  : false
    } );
    sidebar.sidebar( 'attach events', '.sandwich' );


} );
