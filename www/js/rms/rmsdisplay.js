/**
 * The RMS Display can be used as a central 3D display for things like ineractive markers. This
 * depends on the availability of three.js. Based on the example provided with
 * https://github.com/RobotWebTools/interactivemarkersjs. Global variables will be created to help
 * the RMS API find the display.
 * 
 * @fileOverview The RMS Display can be used as a central 3D display for things like ineractive 
 * markers. This depends on the availability of three.js.Based on the example provided with
 * https://github.com/RobotWebTools/interactivemarkersjs. Global variables will be created to help
 * the RMS API find the display.
 * @name RMS Display
 * @author Russell Toris <rctoris@wpi.edu>
 * @version February, 26 2013
 */

/**
 * Create an RMSDisplay with the given options. This will add the renderer to
 * the given div.
 */
var RMSDisplay = function(options) {
  var rmsDisplay = this;
  options = options || {};
  rmsDisplay.divID = options.divID;
  rmsDisplay.ros = options.ros;
  rmsDisplay.width = options.width;
  rmsDisplay.height = options.height;
  rmsDisplay.disableGrid = options.disableGrid;
  rmsDisplay.gridColor = options.gridColor || '#cccccc';
  rmsDisplay.background = options.background || '#111111';

  // create the canvas to render to
  rmsDisplay.renderer = new THREE.WebGLRenderer({
    antialias : true
  });
  rmsDisplay.renderer.setClearColorHex(rmsDisplay.background.replace('#', '0x'), 1.0);
  rmsDisplay.renderer.sortObjects = false;
  rmsDisplay.renderer.setSize(rmsDisplay.width, rmsDisplay.height);
  rmsDisplay.renderer.shadowMapEnabled = false;
  rmsDisplay.renderer.autoClear = false;
  // add it to the page
  document.getElementById(rmsDisplay.divID).appendChild(
      rmsDisplay.renderer.domElement);

  // create the global scene
  rmsDisplay.scene = new THREE.Scene();

  // create the global camera
  rmsDisplay.camera = new THREE.PerspectiveCamera(40, rmsDisplay.width
      / rmsDisplay.height, 0.01, 1000);
  rmsDisplay.camera.position.x = 3;
  rmsDisplay.camera.position.y = 3;
  rmsDisplay.camera.position.z = 3;
  rmsDisplay.cameraControls = new THREE.RosOrbitControls(rmsDisplay.scene,
      rmsDisplay.camera);
  rmsDisplay.cameraControls.userZoomSpeed = 0.5;

  // create a grid
  if (!rmsDisplay.disableGrid) {
    // 50 cells
    var gridGeom = new THREE.PlaneGeometry(50, 50, 50, 50);
    var gridMaterial = new THREE.MeshBasicMaterial({
      color : rmsDisplay.gridColor,
      wireframe : true,
      wireframeLinewidth : 1,
      transparent : true
    });
    var gridObj = new THREE.Mesh(gridGeom, gridMaterial);
    rmsDisplay.scene.add(gridObj);
  }

  // lights
  rmsDisplay.scene.add(new THREE.AmbientLight(0x555555));
  rmsDisplay.directionalLight = new THREE.DirectionalLight(0xffffff);
  rmsDisplay.scene.add(rmsDisplay.directionalLight);

  // propagates mouse events to three.js objects
  rmsDisplay.selectableObjs = new THREE.Object3D;
  rmsDisplay.scene.add(rmsDisplay.selectableObjs);
  var mouseHandler = new ThreeInteraction.MouseHandler(rmsDisplay.renderer,
      rmsDisplay.camera, rmsDisplay.selectableObjs, rmsDisplay.cameraControls);

  // highlights the receiver of mouse events
  rmsDisplay.highlighter = new ThreeInteraction.Highlighter(mouseHandler);

  // begin the animation
  draw();

  // renders the current scene
  function draw() {
    // update the controls
    rmsDisplay.cameraControls.update();

    // put light to the top-left of the camera
    rmsDisplay.directionalLight.position = rmsDisplay.camera
        .localToWorld(new THREE.Vector3(-1, 1, 0));
    rmsDisplay.directionalLight.position.normalize();

    // set the scene
    rmsDisplay.renderer.clear(true, true, true);
    rmsDisplay.renderer.render(rmsDisplay.scene, rmsDisplay.camera);

    // reder any mouseovers
    rmsDisplay.highlighter.renderHighlight(rmsDisplay.renderer,
        rmsDisplay.scene, rmsDisplay.camera);

    // draw the frame
    requestAnimationFrame(draw);
  }
  
  // create a global pointer
  window._RMSDISPLAY = this;
};
