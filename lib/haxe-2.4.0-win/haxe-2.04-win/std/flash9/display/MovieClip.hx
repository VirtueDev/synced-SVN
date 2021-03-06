package flash.display;

extern class MovieClip extends Sprite, implements Dynamic {
	var currentFrame(default,null) : Int;
	var currentLabel(default,null) : String;
	var currentLabels(default,null) : Array<FrameLabel>;
	var currentScene(default,null) : Scene;
	var enabled : Bool;
	var framesLoaded(default,null) : Int;
	var scenes(default,null) : Array<Scene>;
	var totalFrames(default,null) : Int;
	var trackAsMenu : Bool;
	function new() : Void;
	function addFrameScript( ?p1 : Dynamic, ?p2 : Dynamic, ?p3 : Dynamic, ?p4 : Dynamic, ?p5 : Dynamic ) : Void;
	function gotoAndPlay(frame : Dynamic, ?scene : String) : Void;
	function gotoAndStop(frame : Dynamic, ?scene : String) : Void;
	function nextFrame() : Void;
	function nextScene() : Void;
	function play() : Void;
	function prevFrame() : Void;
	function prevScene() : Void;
	function stop() : Void;
	#if flash10
	var currentFrameLabel(default,null) : String;
	#end
}
