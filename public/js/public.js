var config = {
    noise_file: '',     // wp-content/plugins/wp-viewworks/public/images/rgbaNoise256.png
    wallpaper_file: '', // wp-content/plugins/wp-viewworks/public/images/wallpaper.jpg
    items_loaded: 0,    // for progressbar
    items_total: 0      // for progressbar
}

function JAMIE_createRaymarchBackground( background )
// background = 'cloudsFS', 'cellsFS', 'fogFS', 'sunsetFS'
{
	// resolution
	var resolution = new THREE.Vector2();
	JAMIE.appWorks.renderer.getSize( resolution );

	// uniforms
	var shader = JAMIE.BackgroundShader;
	var uniforms = THREE.UniformsUtils.clone( shader.uniforms );
	uniforms['resolution'].value = resolution;

	// shaders
	var vertexShader = shader.vertexShader;
	var fragmentShader = shader[ background ];

	// material
	var defines = JAMIE.isWebGL1() ? { gl_FragDepth: 'gl_FragDepthEXT' } : {};
	var material = new THREE.ShaderMaterial({
		defines: defines,
		uniforms: uniforms,
		vertexShader: vertexShader,
		fragmentShader: fragmentShader
	});

	// texture (for cloudsFS)
	if( background === 'cloudsFS' )
	{
		var uniforms = material.uniforms;
		var loader = new THREE.TextureLoader();
		var texMap = loader.load( config.noise_file, function(tex){ tex.wrapS = tex.wrapT = THREE.RepeatWrapping; });
		uniforms['textureMaps'].value.push( texMap );
	}

	// material.extensions
	material.extensions = { derivatives: true, fragDepth: true, drawBuffers: true, shaderTextureLOD: true };

	// geometry
	var geometry = new THREE.PlaneBufferGeometry( 2, 2 );

	// mesh
	var background = new THREE.Mesh( geometry, material );
	background.name = 'backgroundMesh';
	background.frustumCulled = false;

	// mesh update
	background.update = function( dTime, curTime )
	{
		JAMIE.appWorks.renderer.getSize( resolution );
		var uniforms = this.material.uniforms;
		uniforms['time'].value = curTime;
		uniforms['resolution'].value = resolution;
	}

	return background;
}

function JAMIE_createShowroom( object )
{
	var showroom = new THREE.Object3D();
    showroom.name = 'showroom';

    //----------------------------
    // showroom.backgrounds
    //----------------------------

    showroom.activeBackground = -1;
    showroom.backgrounds = [];

    // texture
	var backgroundTex = new THREE.TextureLoader().load( config.wallpaper_file );
    showroom.backgrounds.push( JAMIE.createBackground( backgroundTex ) );

    // background
    JAMIE.appWorks.scene.background = backgroundTex;

    // raymarch
    backgrounds = [ 'cloudsFS', 'cellsFS', 'fogFS', 'sunsetFS' ];
    backgrounds.forEach( background => {
		// var raymarch = JAMIE.createBackground( background );
		var raymarch = JAMIE_createRaymarchBackground( background );
        raymarch.name = 'raymarch';
        showroom.backgrounds.push( raymarch );
    });

    // color
    showroom.backgrounds.push( JAMIE.createBackground( 0xc0c0c0 ) );

    //----------------------------
    // showroom.floors
    //----------------------------

    showroom.activeFloor = -1;
    showroom.floors = [];

    var box = JAMIE.computeBoundingBox( object );
    var ymin = box.min.y;
    var size = box.getSize( new THREE.Vector3() );
    var size = Math.max( size.x, size.y, size.z );
    var radius = size * 4;

    // acrylic floor
    var floor = JAMIE.createAcrylicCircle( radius );
    floor.rotation.x = -Math.PI/2;
    floor.position.y = ymin;
    floor.name = 'floor';
    showroom.floors.push( floor );

    //----------------------------
    // showroom & light ==> scene
    //----------------------------

    // showroom
    JAMIE.nextShowroom( showroom, 'background' );
    JAMIE.nextShowroom( showroom, 'floor' );

    // lights
    JAMIE.add( JAMIE.createHemisphereLight() );
    var dirLight = JAMIE.createDirectionalLight({
        shadowCameraSize: radius*0.5
    });
    dirLight.position.set( 0, radius*0.75, radius*0.5 );
    JAMIE.add( dirLight );

    return showroom;
}

function JAMIE_welcomeObject( object )
{
	object.traverse( function( child )
	{
		if( child.isMesh )
		{
			child.frustumCulled = false;
			child.castShadow = true;
			child.receiveShadow = true;
		}
	});

	JAMIE.resizeObject( object, 1.0 ); // 1.0 = newSize

	JAMIE.fitCameraToObject( object );

    JAMIE.appWorks.selectObject = object;

    if( JAMIE.appWorks.showroom === undefined )
	{
		var showroom = JAMIE_createShowroom( object );
        JAMIE.appWorks.showroom = showroom;
        JAMIE.add( showroom );
	}
}

function JAMIE_loadFiles( model_file, public_folder )
{
    return new Promise( resolve => {
        JAMIE.loadFiles({
            url: model_file,
            resourcePath: public_folder, // for raymarch.json
            acrossDomain: false,         // check if 'mtl' exists
            callback: function( object )
            {
                JAMIE.updateProgressbar( config.items_loaded++, config.items_total );
                resolve( object );
            }
        });
    });
}

var novaContainer = document.getElementsByClassName( 'nova-container' )[0];

if( novaContainer )
{
    JAMIE.createProgressbar();

    var model_files = novaContainer.getAttribute( 'data-model' );
    model_files = model_files.split( ',' );
    var model_count = model_files.length;

    config.items_loaded = 0;
    config.items_total = model_count*2 + 1;
    JAMIE.updateProgressbar( config.items_loaded++, config.items_total );

    // novaContainer(div)
    //  |-- novaRow(div)
    //       |-- novaCol(div) + novaCol(div) + ...
    //            |-- novaApp(div)
    //                 |-- appWorks.dom(div)
    //                      |-- renderer.domElement(canvas)
    //

    var novaRow = document.createElement( 'div' );
    novaRow.classList.add( 'nova-row' );

    for( var i = 0; i < model_count; i++ )
    {
        var novaCol = document.createElement( 'div' );
        novaCol.classList.add( 'nova-column' );

        var novaApp = document.createElement( 'div' );
        novaApp.id = 'nova-app' + i;

        novaCol.appendChild( novaApp );
        novaRow.appendChild( novaCol );
    }

    novaContainer.appendChild( novaRow );

    // width & height (of each appWorks)
    var width = novaContainer.getAttribute( 'data-width' );
    var height = novaContainer.getAttribute( 'data-height' );
    var rect = novaContainer.getBoundingClientRect();
    if( typeof width  === 'string' && width.slice(-1)  === '%' )
    {
        width = rect.width * (parseFloat( width ) * 0.01);
    }
    if( typeof height === 'string' && height.slice(-1) === '%' )
    {
        height = rect.height * (parseFloat( height ) * 0.01);
    }
    width  = parseInt( width );
    height = parseInt( height );

    // wp-content/plugins/wp-viewworks/public/images
    var images_folder = novaContainer.getAttribute( 'data-images' );
    config.wallpaper_file = images_folder + 'wallpaper.jpg';
    config.noise_file = images_folder + 'rgbaNoise256.png';
    var public_folder = images_folder.replace( 'images/', '' );

    var novaApps = [];
    var appWorkss = [];
    var promises = [];
    for( var i = 0; i < model_count; i++ )
    {
        // novaApp
        novaApps[ i ] = document.getElementById( 'nova-app' + i );

        // appWorks
        appWorkss[ i ] = JAMIE.createWPViewWorks( width, height );

        // loading files...
        promises.push( JAMIE_loadFiles( model_files[ i ], public_folder ) );
    }

    Promise.all( promises ).then( objects =>
    {
        objects.forEach( (object, i) =>
        {
            JAMIE.updateProgressbar( config.items_loaded++, config.items_total );

            JAMIE.appWorks = appWorkss[ i ];

            novaApps[ i ].appendChild( JAMIE.appWorks.dom );

            // user model
            JAMIE.add( object );

            // background + floor
            var fileExt = object.name.split( '.' ).pop().toLowerCase();
            if( fileExt !== 'json' )
            {
                JAMIE_welcomeObject( object );
            }

            // reset controls
            JAMIE.appWorks.controls = JAMIE.createCameraControls(
                'OrbitControls', JAMIE.appWorks.camera, JAMIE.appWorks.renderer.domElement );
        });

        JAMIE.updateProgressbar( config.items_total, config.items_total );
        setTimeout( function(){ JAMIE.hideProgressbar(); }, 2000 );
    });
}