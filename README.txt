=== WP ViewWorks ===
Contributors: NovaGraphix
Donate link: https://www.nova-graphix.com/
Tags: viewer, 3D viewer, 3D model viewer, 3D model rendering, stl, ply, dae, glb, gltf, amf, 3mf, fbx
Requires at least: 1.1.0
Tested up to: 1.1.0
Stable tag: 1.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin provides a 3D model viewer to embed your 3D models on your Wordpress pages or posts.

== Description ==

This plugin uses the renowned javascript library: [three.js](https://threejs.org/). This provides a 3D model viewer for a real-time rendering of various kinds of 3D model files. It will be continuously upgraded for faster file loading and rendering, and more features will continue to be inserted for further analysis.

# Main Features
* Supported file formats: **STL**, **PLY**, **DAE**, **GLB**, **GLTF**, **AMF**, **3MF**, **FBX**
* Underlying technologies: WebGL(three.js), Physically-based rendering, GLSL Shaders using ray-marching technique
* Background: Two different textures are used as a background. They are wallpaper textures and animated textures.
* Base Floor: It is a circular glass plate that mirrors the model from below, adding to the visual representation of the loaded model.
* Lights: Hemisphere light & directional light
* Shadow: Shadow maps using the percentage-closer soft shadows technique
* Perspective Camera: Fitting the camera to the size of the loaded models
* We will build a paid version of the plugin that has more customization options. It will be available soon.

# User Interface
* Keyboard
    - The key '1' allows us to change the background so we can see six different backgrounds.
    - The key 'p' allows us to play the animation of loaded models if available, and 'o' pauses the animation action. Pressing the key 'o' again will resume the paused action.
* Mouse
    - You can rotate the camera by holding down the left mouse button and moving the mouse.
    - You can pan the camera by holding down the right mouse button and moving the mouse.
    - You can zoom in/out the camera by scrolling the mouse wheel.

# Demo
* Click on the [link](https://www.youtube.com/watch?v=J85_ZMJszNM) to watch a promotional video.
* Click on the [link](https://sangkunine.github.io/viewWorks/) to evaluate a rendering performance with your 3D models.
* Click on the [link](https://github.com/sangkunine/viewWorks) to check the source files.

== Installation ==

1. Upload the plugin files to the `your_wordpress_plugins_folder/wp-viewworks` directory or install the files as a regular WordPress plugin.
1. Activate the plugin through the `Plugins` menu in WordPress
1. Use these shortcodes to post or page.

    `[wp-viewworks models="fileURL1, fileURL2, ...." width=width_in_pixels_or_in_percentage height=height_in_pixels_or_in_percentage][/wp-viewworks]`

    where shortcode parameters are:
    * models: specify the full URL locations of your 3D model files (eg: https://www.nova-graphix.com/wp-content/uploads/2020/08/model.glb)
    * width:  width of canvas to be displayed in pixels or percentage (eg: 1120, "1120", "1120px", "33.33%", "50%", "100%")
    * height: height of canvas to be displayed in pixels (eg: 800, "800", "800px")

1. There are a few things to keep in mind to view the 3D model correctly:
    * All data files (including texture files) should be located at the same server to avoid CORS security issues. We recommend uploading the data files to the WordPress library.
    * When uploading, the texture files must be in the location specified by the 3D model file.
    * When loading 'OBJ' and 'MTL' files at the same time, both files must be in the same folder.

== Frequently Asked Questions ==

= What can I do in the wp-viewworks? =

* You can rotate the camera by holding down the left mouse button and moving the mouse.
* You can pan the camera by holding down the right mouse button and moving the mouse.
* You can zoom in/out the camera by scrolling the mouse wheel.
* You can change a background by pressing the key '1'. Six different backgrounds are supported in this version.
* You can play the animations of the loaded model by pressing the key 'p' if available. If you press the key 'o', then the animation is paused.

= Why does the loaded model look black? =

The 3D model looks black when there is no normal vector or the direction of the normal vector is not facing outward. You can use a 3D modeling software, e.g., Blender, to resolve this normal vector problem.

= Can I change the background? =

Yes, this can be done by replacing the "wallpaper.jpg" in the `your_wordpress_plugins_folder/wp-viewworks/public/images/` folder with your background image. However, the file name of your background image should be "wallpaper.jpg".

= Can I use multiple wp-viewworks in the same page? =

The current version does not support this feature. However, we will add this in the next version.

= Where can I find the full version of this plugin? =

Click on the [NovaGraphix](https://www.nova-graphix.com/) or email to `info@nova-graphix.com` for more information.

== Screenshots ==

1. [wp-viewworks models="http://www.nova-graphix.com/wp-content/uploads/2020/08/Subway_Car.glb"][/wp-viewworks]
2. [wp-viewworks models="
http://www.nova-graphix.com/wp-content/uploads/2020/08/seaFS.json,
http://www.nova-graphix.com/wp-content/uploads/2020/08/terrainFS.json,
http://www.nova-graphix.com/wp-content/uploads/2020/08/cavesFS.json"
width="33.33%" height=800][/wp-viewworks]
3. [wp-viewworks models="
http://www.nova-graphix.com/wp-content/uploads/2020/08/troll.obj,
http://www.nova-graphix.com/wp-content/uploads/2020/08/DamagedHelmet.gltf,
http://www.nova-graphix.com/wp-content/uploads/2020/08/Subway_Car.glb,
http://www.nova-graphix.com/wp-content/uploads/2020/08/stormtrooper.dae"
width="50%" height=400][/wp-viewworks]

== Changelog ==

= 1.1.0 =
* Multiple model files can be loaded simultaneously with one shortcode.
* When loading a large model file, a progress bar appears.
* The loaded models are laid out using the CSS flexible box.

= 1.0.0 =
* First release
