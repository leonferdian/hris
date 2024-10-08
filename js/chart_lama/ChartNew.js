/*              
 * ChartNew.js  
 *                                                                                   
 * Vancoppenolle Francois - January 2014                                                                               
 * francois.vancoppenolle@favomo.be                                     
 *
 * GitHub community : https://github.com/FVANCOP/ChartNew.js
 *
 * This file is originally an adaptation of the chart.js source developped by Nick Downie (2013)
 * https://github.com/nnnick/Chart.js. But since june 2014, Nick puts a new version with a
 * refunded code. Current code of ChartNew.js is no more comparable to the code of Chart.js 
 *
 * new charts compared to Chart.js
 *
 *     horizontalBar
 *     horizontalStackedBar
 *
 * Added items compared to Chart.js:
 *
 *     Title, Subtitle, footnotes, axis labels, unit label
 *     Y Axis on the right and/or the left
 *     canvas Border
 *     Legend
 *     crossText, crossImage
 *     graphMin, graphMax
 *     logarithmic y-axis (for line and bar)
 *     rotateLabels
 *     and lot of others...
 *
 */

// ctx.firstPass=0 or "undefined" : Chart has never been drawn (because dynamicDisplay = true and ctx has never been displayed in current screen)
// ctx.firstPass=1 : Chart has to be drawn with animation (if config.animation = true);
// ctx.firstPass=2 : chart has to be drawn without animation;
// ctx.firstPass=9 : chart is completely drawn;
// If chartJsResize called : increment the value of ctx.firstPass with a value of 10.


// non standard functions;

var chartJSLineStyle=[];
chartJSLineStyle["solid"]=[];
chartJSLineStyle["dotted"]=[1,4];
chartJSLineStyle["shortDash"]=[2,1];
chartJSLineStyle["dashed"]=[4,2];
chartJSLineStyle["dashSpace"]=[4,6];
chartJSLineStyle["longDashDot"]=[7,2,1,2];
chartJSLineStyle["longDashShortDash"]=[10,4,4,4];
chartJSLineStyle["gradient"]=[1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10,9,9,8,8,7,7,6,6,5,5,4,4,3,3,2,2,1];

function lineStyleFn(data)
{
if ((typeof chartJSLineStyle[data]) === "object")return chartJSLineStyle[data];
else return chartJSLineStyle["solid"];
};

if (typeof String.prototype.trim !== 'function') {
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, '');
	}
};
if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(searchElement /*, fromIndex */ ) {
		"use strict";
		if (this == null) {
			throw new TypeError();
		}
		var t = Object(this);
		var len = t.length >>> 0;
		if (len === 0) {
			return -1;
		}
		var n = 0;
		if (arguments.length > 0) {
			n = Number(arguments[1]);
			if (n != n) { // shortcut for verifying if it's NaN
				n = 0;
			} else if (n != 0 && n != Infinity && n != -Infinity) {
				n = (n > 0 || -1) * Math.floor(Math.abs(n));
			}
		}
		if (n >= len) {
			return -1;
		}
		var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
		for (; k < len; k++) {
			if (k in t && t[k] === searchElement) {
				return k;
			}
		}
		return -1;
	}
};
var charJSPersonalDefaultOptions = {};
var charJSPersonalDefaultOptionsLine = {} ;
var charJSPersonalDefaultOptionsRadar = {} ;
var charJSPersonalDefaultOptionsPolarArea = {} ;
var charJSPersonalDefaultOptionsPie = {};
var charJSPersonalDefaultOptionsDoughnut = {};
var charJSPersonalDefaultOptionsBar = {};
var charJSPersonalDefaultOptionsStackedBar = {};
var charJSPersonalDefaultOptionsHorizontalBar = {};
var charJSPersonalDefaultOptionsHorizontalStackedBar = {};
	///////// FUNCTIONS THAN CAN BE USED IN THE TEMPLATES ///////////////////////////////////////////

function roundToWithThousands(config, num, place) {
	var newval = 1 * unFormat(config, num);
	if (typeof(newval) == "number" && place != "none") {
		var roundVal;
		if (place <= 0) {
			roundVal = -place;
			newval = +(Math.round(newval + "e+" + roundVal) + "e-" + roundVal);
		} else {
			roundVal = place;
			var divval = "1e+" + roundVal;
			newval = +(Math.round(newval / divval)) * divval;
		}
	}
	newval = fmtChartJS(config, newval, "none");
	return (newval);
};

function unFormat(config, num) {
	if ((config.decimalSeparator != "." || config.thousandSeparator != "") && typeof(num) == "string") {
		var v1 = "" + num;
		if (config.thousandSeparator != "") {
			while (v1.indexOf(config.thousandSeparator) >= 0) v1 = "" + v1.replace(config.thousandSeparator, "");
		}
		if (config.decimalSeparator != ".") v1 = "" + v1.replace(config.decimalSeparator, ".")
		return 1 * v1;
	} else {
		return num;
	}
};
///////// ANNOTATE PART OF THE SCRIPT ///////////////////////////////////////////
/********************************************************************************
Copyright (C) 1999 Thomas Brattli
This script is made by and copyrighted to Thomas Brattli
Visit for more great scripts. This may be used freely as long as this msg is intact!
I will also appriciate any links you could give me.
Distributed by Hypergurl
********************************************************************************/
var cachebis = {};

function fmtChartJSPerso(config, value, fmt) {
	switch (fmt) {
		case "SampleJS_Format":
			if (typeof(value) == "number") return_value = "My Format : " + value.toString() + " $";
			else return_value = value + "XX";
			break;
		case "Change_Month":
			if (typeof(value) == "string") return_value = value.toString() + " 2014";
			else return_value = value.toString() + "YY";
			break;
		default:
			return_value = value;
			break;
	}
	return (return_value);
};

function fmtChartJS(config, value, fmt) {
	var return_value;
	if (fmt == "notformatted") {
		return_value = value;
	} else if ((fmt == "none" || fmt=="money") && typeof(value) == "number") {
		if (config.roundNumber != "none") {
			var roundVal;
			if (config.roundNumber <= 0) {
				roundVal = -config.roundNumber;
				value = +(Math.round(value + "e+" + roundVal) + "e-" + roundVal);
			} else {
				roundVal = config.roundNumber;
				var divval = "1e+" + roundVal;
				value = +(Math.round(value / divval)) * divval;
			}
		}
		if (config.decimalSeparator != "." || config.thousandSeparator != "") {
			return_value = value.toString().replace(/\./g, config.decimalSeparator);
			if (config.thousandSeparator != "") {
				var part1 = return_value;
				var part2 = "";
				var posdec = part1.indexOf(config.decimalSeparator);
				if (posdec >= 0) {
					part2 = part1.substring(posdec + 1, part1.length);
					part2 = part2.split('').reverse().join(''); // reverse string
					part1 = part1.substring(0, posdec);
				}
				part1 = part1.toString().replace(/\B(?=(\d{3})+(?!\d))/g, config.thousandSeparator);
				part2 = part2.split('').reverse().join(''); // reverse string
				return_value = part1
				if (part2 != "") return_value = return_value + config.decimalSeparator + part2;
			}
		} else return_value = value;
		if(fmt=="money") {
			if(config.currencyPosition=="before")return_value=config.currency+return_value;
			else return_value=return_value+config.currency;
		}
	} else if (fmt != "none" && fmt != "notformatted") {
		return_value = fmtChartJSPerso(config, value, fmt);
	} else {
		return_value = value;
	}
	return (return_value);
};

function addParameters2Function(data, fctName, fctList) {
	var mathFunctions = {
		mean: {
			data: data.data,
			datasetNr: data.v11
		},
		varianz: {
			data: data.data,
			datasetNr: data.v11
		},
		stddev: {
			data: data.data,
			datasetNr: data.v11
		},
		cv: {
			data: data.data,
			datasetNr: data.v11
		},
		median: {
			data: data.data,
			datasetNr: data.v11
		}
	};
	// difference to current value (v3)
	dif = false;
	if (fctName.substr(-3) == "Dif") {
		fctName = fctName.substr(0, fctName.length - 3);
		dif = true;
	}
	if (typeof eval(fctName) == "function") {
		var parameter = eval(fctList + "." + fctName);
		if (dif) {
			// difference between v3 (current value) and math function
			return data.v3 - window[fctName](parameter);
		}
		return window[fctName](parameter);
	}
	return null;
};

function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
};

function tmplbis(str, data,config) {
	newstr=str;
	if(newstr.substr(0,config.templatesOpenTag.length)==config.templatesOpenTag)newstr="<%="+newstr.substr(config.templatesOpenTag.length,newstr.length-config.templatesOpenTag.length);
	if(newstr.substr(newstr.length-config.templatesCloseTag.length,config.templatesCloseTag.length)==config.templatesCloseTag)newstr=newstr.substr(0,newstr.length-config.templatesCloseTag.length)+"%>";
	return tmplter(newstr,data);
}

function tmplter(str, data) {
	var mathFunctionList = ["mean", "varianz", "stddev", "cv", "median"];
	var regexMath = new RegExp('<%=((?:(?:.*?)\\W)??)((?:' + mathFunctionList.join('|') + ')(?:Dif)?)\\(([0-9]*?)\\)(.*?)%>', 'g');
	while (regexMath.test(str)) {
		str = str.replace(regexMath, function($0, $1, $2, $3, $4) {
			var rndFac;
			if ($3) rndFac = $3;
			else rndFac = 2;
			var value = addParameters2Function(data, $2, "mathFunctions");
			if (isNumber(value)) 
				return '<%=' + $1 + '' + Math.round(Math.pow(10, rndFac) * value) / Math.pow(10, rndFac) + '' + $4 + '%>';
			return '<%= %>';
		});
	}
	// Figure out if we're getting a template, or if we need to
	// load the template - and be sure to cache the result.
	// first check if it's can be an id
	var fn = /^[A-Za-z][-A-Za-z0-9_:.]*$/.test(str) ? cachebis[str] = cachebis[str] ||
		tmplter(document.getElementById(str).innerHTML) :
		// Generate a reusable function that will serve as a template
		// generator (and which will be cached).
		new Function("obj",
			"var p=[],print=function(){p.push.apply(p,arguments);};" +
			// Introduce the data as local variables using with(){}
			"with(obj){p.push('" +
			// Convert the template into pure JavaScript
			str
			.replace(/[\r\n]/g, "\\n")
			.replace(/[\t]/g, " ")
			.split("<%").join("\t")
			.replace(/((^|%>)[^\t]*)'/g, "$1\r")
			.replace(/\t=(.*?)%>/g, "',$1,'")
			.split("\t").join("');")
			.split("%>").join("p.push('")
			.split("\r").join("\\'") + "');}return p.join('');");
	// Provide some basic currying to the user
	return data ? fn(data) : fn;
};
if (typeof CanvasRenderingContext2D !== 'undefined') {
	/**
	 * ctx.prototype
	 * fillText option for canvas Multiline Support
	 * @param text string \n for newline
	 * @param x x position
	 * @param y y position
	 * @param yLevel = "bottom" => last line has this y-Pos [default], = "middle" => the middle line has this y-Pos)
	 * @param lineHeight lineHeight
	 * @param horizontal horizontal
	 */
	CanvasRenderingContext2D.prototype.fillTextMultiLine = function(text, x, y, yLevel, lineHeight,horizontal,detectMouseOnText,ctx,idText,rotate,x_decal,y_decal,posi,posj) {
		var lines = ("" + text).split("\n");
		// if its one line => in the middle 
		// two lines one above the mid one below etc.	
		if (yLevel == "middle") {
			if(horizontal)y -= ((lines.length - 1) / 2) * lineHeight;
		} else if (yLevel == "bottom") { // default
			if(horizontal)y -= (lines.length - 1) * lineHeight;
		}

		var y_pos=y-lineHeight;

		for (var i = 0; i < lines.length; i++) {
			this.fillText(lines[i], x, y);
			y += lineHeight;
		}
		if(detectMouseOnText) {
			var txtSize=ctx.measureTextMultiLine(text,lineHeight);
			var x_pos=[];
			var y_pos=[];
			x_pos.p1=x_decal+x;
			y_pos.p1=y_decal+y-lineHeight;
			var rotateRV=(Math.PI/2)+rotate;
			       if(ctx.textAlign=="left" && yLevel=="top"){
				x_pos.p1+=lineHeight*Math.cos(rotateRV);
				y_pos.p1+=lineHeight*Math.sin(rotateRV);
			} else if(ctx.textAlign=="left" && yLevel=="middle"){
				x_pos.p1+=(lineHeight/2)*Math.cos(rotateRV);
				y_pos.p1+=(lineHeight/2)*Math.sin(rotateRV);
			} else if(ctx.textAlign=="left" && yLevel=="bottom"){
			       // nothing to adapt;
			} else if(ctx.textAlign=="center" && yLevel=="top"){
				x_pos.p1+=lineHeight*Math.cos(rotateRV)-(txtSize.textWidth/2)*Math.cos(rotate);
				y_pos.p1+=lineHeight*Math.sin(rotateRV)-(txtSize.textWidth/2)*Math.sin(rotate);
			} else if(ctx.textAlign=="center" && yLevel=="middle"){
				x_pos.p1+=(lineHeight/2)*Math.cos(rotateRV)-(txtSize.textWidth/2)*Math.cos(rotate);
				y_pos.p1+=(lineHeight/2)*Math.sin(rotateRV)-(txtSize.textWidth/2)*Math.sin(rotate);
			} else if(ctx.textAlign=="center" && yLevel=="bottom"){
				x_pos.p1-=(txtSize.textWidth/2)*Math.cos(rotate);
				y_pos.p1-=(txtSize.textWidth/2)*Math.sin(rotate);
			} else if(ctx.textAlign=="right" && yLevel=="top"){
				x_pos.p1+=(lineHeight*Math.cos(rotateRV)-txtSize.textWidth*Math.cos(rotate));
				y_pos.p1+=(lineHeight*Math.sin(rotateRV)-txtSize.textWidth*Math.sin(rotate));
			} else if(ctx.textAlign=="right" && yLevel=="middle"){
				x_pos.p1+=(lineHeight/2)*Math.cos(rotateRV)-txtSize.textWidth*Math.cos(rotate);
				y_pos.p1+=(lineHeight/2)*Math.sin(rotateRV)-txtSize.textWidth*Math.sin(rotate);
			} else if(ctx.textAlign=="right" && yLevel=="bottom"){
				x_pos.p1-=txtSize.textWidth*Math.cos(rotate);
				y_pos.p1-=txtSize.textWidth*Math.sin(rotate);
			} 

			// Other corners of the rectangle;
			
			x_pos.p2=x_pos.p1+txtSize.textWidth*Math.cos(rotate);
			y_pos.p2=y_pos.p1+txtSize.textWidth*Math.sin(rotate);
			
			x_pos.p3=x_pos.p1-lineHeight*Math.cos(rotateRV);
			y_pos.p3=y_pos.p1-lineHeight*Math.sin(rotateRV);
			
			x_pos.p4=x_pos.p3+txtSize.textWidth*Math.cos(rotate);
			y_pos.p4=y_pos.p3+txtSize.textWidth*Math.sin(rotate);

			jsTextMousePos[ctx.ChartNewId][jsTextMousePos[ctx.ChartNewId].length] = [idText,text,x_pos,y_pos,rotate,txtSize.textWidth,txtSize.textHeight,posi,posj];
						
		}	
	};
	CanvasRenderingContext2D.prototype.measureTextMultiLine = function(text, lineHeight) {
		var textWidth = 0;
		var lg;
		var lines = ("" + text).replace(/<BR>/ig, "\n").split("\n");
		var textHeight = lines.length * lineHeight;
		// if its one line => in the middle 
		// two lines one above the mid one below etc.	
		for (var i = 0; i < lines.length; i++) {
			lg = this.measureText(lines[i]).width;
			if (lg > textWidth) textWidth = lg;
		}
		return {
			textWidth: textWidth,
			textHeight: 1.5*textHeight
		};
	};
	if (typeof CanvasRenderingContext2D.prototype.setLineDash !== 'function') {
		CanvasRenderingContext2D.prototype.setLineDash = function( listdash) {
			return 0;
		};
	};
	
	CanvasRenderingContext2D.prototype.drawRectangle = function(TheRectangle){
		//this.shadowColor = '#999';
     		// this.shadowBlur = 5;
	    	// this.shadowOffsetX = 15;
     		//this.shadowOffsetY = 15;
		originalfillStyle =this.fillStyle;
    		if(typeof TheRectangle.x=='undefined'){TheRectangle.x=0}
      		if(TheRectangle.width<0){TheRectangle.x+=TheRectangle.width;TheRectangle.width*=-1}
        	if(TheRectangle.height<0){TheRectangle.y+=TheRectangle.height;TheRectangle.height*=-1}
    		if(typeof TheRectangle.y=='undefined'){TheRectangle.y=0}
  		if(typeof TheRectangle.y=='undefined'){TheRectangle.y=0}
  		if(typeof TheRectangle.backgroundColor!='undefined'){this.fillStyle=TheRectangle.backgroundColor}
  		if(typeof TheRectangle.borderRadius=='undefined'){TheRectangle.borderRadius=0}
  
  		if(TheRectangle.borderRadius==0 && TheRectangle.fill==true){this.fillRect(TheRectangle.x,TheRectangle.y,TheRectangle.width,TheRectangle.height);}
    		if(TheRectangle.borderRadius==0 && TheRectangle.stroke==true){this.strokeRect(TheRectangle.x,TheRectangle.y,TheRectangle.width,TheRectangle.height);}
    		if(TheRectangle.borderRadius!=0){
      			rectangleRadius=parseInt(TheRectangle.borderRadius);
      			if(rectangleRadius>TheRectangle.width/2 ){rectangleRadius=TheRectangle.width/2}
      			if(TheRectangle.height<TheRectangle.width &&rectangleRadius>TheRectangle.height/2 ){rectangleRadius=TheRectangle.height/2}
      			this.beginPath();
      			this.moveTo(TheRectangle.x+rectangleRadius,TheRectangle.y);
      			this.lineTo(TheRectangle.x+TheRectangle.width-rectangleRadius,TheRectangle.y);
     			this.arc(TheRectangle.x+TheRectangle.width-rectangleRadius,TheRectangle.y+rectangleRadius,rectangleRadius,1.5*Math.PI,0); 
      			this.lineTo(TheRectangle.x+TheRectangle.width,TheRectangle.y+TheRectangle.height-rectangleRadius);
    			this.arc(TheRectangle.x+TheRectangle.width-rectangleRadius,TheRectangle.y+TheRectangle.height-rectangleRadius,rectangleRadius,0,0.5*Math.PI); 
     			this.lineTo(TheRectangle.x+rectangleRadius,TheRectangle.y+TheRectangle.height);
       			this.arc(TheRectangle.x+rectangleRadius,TheRectangle.y+TheRectangle.height-rectangleRadius,rectangleRadius,0.5*Math.PI,1*Math.PI); 
       			this.lineTo(TheRectangle.x,TheRectangle.y+rectangleRadius);
       			this.arc(TheRectangle.x+rectangleRadius,TheRectangle.y+rectangleRadius,rectangleRadius,1*Math.PI,1.5*Math.PI); 
	   		this.closePath();
    			if(TheRectangle.fill==true) {this.fill()};
      			if(TheRectangle.stroke==true) {this.stroke()};
	    	} 
 		this.fillStyle= originalfillStyle;
		return this;
	};
};

cursorDivCreated = false;

function createCursorDiv() {
	if (cursorDivCreated == false) {
		var div = document.createElement('divCursor');
		div.id = 'divCursor';
		div.style.position = 'absolute';
		document.body.appendChild(div);
		cursorDivCreated = true;
	}
};

initChartJsResize = false;
var jsGraphResize = new Array();

function addResponsiveChart(id,ctx,data,config) {
	if(initChartJsResize==false) {
		if (window.addEventListener) {
			window.addEventListener("resize", chartJsResize);
		} else {
			window.attachEvent("resize", chartJsResize);
		}
	}
	jsGraphResize[jsGraphResize.length]= [id,ctx.tpchart,ctx,data,config];
};


var container;
function getMaximumWidth(domNode){
    if(domNode.parentNode!=null)
        if(domNode.parentNode!=undefined)
            container = domNode.parentNode;
    return container.clientWidth;
};

function getMaximumHeight(domNode){
	if(domNode.parentNode!=null)
            if(domNode.parentNode!=undefined)
                container = domNode.parentNode;
	return container.clientHeight;
};

function resizeCtx(ctx,config)
{
	if (isIE() < 9 && isIE() != false) return(true);

	if(config.responsive) {	
		if(typeof config.maintainAspectRatio == "undefined")config.maintainAspectRatio=true;
		if(typeof config.responsiveMinWidth == "undefined")config.responsiveMinWidth=0;
		if(typeof config.responsiveMinHeight  == "undefined")config.responsiveMinHeight=0;
		if(typeof config.responsiveMaxWidth  == "undefined")config.responsiveMaxWidth=9999999;
		if(typeof config.responsiveMaxHeight  == "undefined")config.responsiveMaxHeight=9999999;
		var canvas = ctx.canvas;
		if(typeof ctx.aspectRatio == "undefined") {
			ctx.aspectRatio = canvas.width / canvas.height;
		}
  		var newWidth = getMaximumWidth(canvas);
		var newHeight = config.maintainAspectRatio ? newWidth / ctx.aspectRatio : getMaximumHeight(canvas);
		newWidth=Math.min(config.responsiveMaxWidth,Math.max(config.responsiveMinWidth,newWidth));
		newHeight=Math.min(config.responsiveMaxHeight,Math.max(config.responsiveMinHeight,newHeight));

		if(typeof ctx.DefaultchartTextScale=="undefined")ctx.DefaultchartTextScale=config.chartTextScale;
		if(typeof ctx.DefaultchartLineScale=="undefined")ctx.DefaultchartLineScale=config.chartLineScale;
		if(typeof ctx.DefaultchartSpaceScale=="undefined")ctx.DefaultchartSpaceScale=config.chartSpaceScale;
		/* new ratio */
		if(typeof ctx.chartTextScale != "undefined" && config.responsiveScaleContent) {
			ctx.chartTextScale=ctx.DefaultchartTextScale*(newWidth/ctx.initialWidth);
			ctx.chartLineScale=ctx.DefaultchartLineScale*(newWidth/ctx.initialWidth);
			ctx.chartSpaceScale=ctx.DefaultchartSpaceScale*(newWidth/ctx.initialWidth);
		}
		
		if (window.devicePixelRatio>1) {
			ctx.canvas.style.width = newWidth + "px";
			ctx.canvas.style.height = newHeight + "px";
		}
		ctx.canvas.height = newHeight * Math.max(1,window.devicePixelRatio);
		ctx.canvas.width = newWidth * Math.max(1,window.devicePixelRatio);
		ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
	} else if (window.devicePixelRatio>1) {
		if(typeof ctx.original_width=="undefined") {
			ctx.original_width=ctx.canvas.width;
			ctx.original_height=ctx.canvas.height;
		}
		ctx.canvas.style.width = ctx.original_width + "px";
		ctx.canvas.style.height = ctx.original_height + "px";
		ctx.canvas.height = ctx.original_height * window.devicePixelRatio;
		ctx.canvas.width = ctx.original_width * window.devicePixelRatio;
		ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
	}
};




function chartJsResize() {
	for (var i=0;i<jsGraphResize.length;i++)  {
		if(jsGraphResize[i][2].firstPass ==0)  {
		} else if(jsGraphResize[i][2].firstPass ==9) {
			jsGraphResize[i][2].firstPass=2;
			redrawGraph(jsGraphResize[i][2],jsGraphResize[i][3],jsGraphResize[i][4]);
		} else if(jsGraphResize[i][2].firstPass < 10 ) {
			jsGraphResize[i][2].firstPass+=10;
		} 
	}
};

function testRedraw(ctx,data,config) {
	if (ctx.firstPass>10) {
		ctx.firstPass=2;
		redrawGraph(ctx,data,config) ;
		return true;
	} else {
		return false;
	}		
};

function updateChart(ctx,data,config,animation,runanimationcompletefunction) {

	if (ctx.firstPass==9)
	{
		
		ctx.runanimationcompletefunction=runanimationcompletefunction;

		if(animation)ctx.firstPass=1;
		else         ctx.firstPass=2;

		if(config.responsive) {
			// update jsGraphResize;
			for (var i=0;i<jsGraphResize.length;i++)  {
				if(jsGraphResize[i][2].ChartNewId== ctx.ChartNewId) {
					jsGraphResize[i][3]=data;
					jsGraphResize[i][4]=config;
				}
			}
		}
		redrawGraph(ctx,data,config);
	}
};


function redrawGraph(ctx,data,config) {
	var myGraph = new Chart(ctx);	
        eval("myGraph."+ctx.tpchart+"(data,config);");
};


//Default browsercheck, added to all scripts!                                   
function checkBrowser() {
	this.ver = navigator.appVersion
	this.dom = document.getElementById ? 1 : 0
	this.ie5 = (this.ver.indexOf("MSIE 5") > -1 && this.dom) ? 1 : 0;
	this.ie4 = (document.all && !this.dom) ? 1 : 0;
	this.ns5 = (this.dom && parseInt(this.ver) >= 5) ? 1 : 0;
	this.ns4 = (document.layers && !this.dom) ? 1 : 0;
	this.bw = (this.ie5 || this.ie4 || this.ns4 || this.ns5)
	return this
};
bw = new checkBrowser();
//Set these variables:
fromLeft = 10; // How much from the left of the cursor should the div be?
fromTop = 10; // How much from the top of the cursor should the div be?
/********************************************************************
Initilizes the objects
*********************************************************************/
function cursorInit() {
	scrolled = bw.ns4 || bw.ns5 ? "window.pageYOffset" : "document.body.scrollTop"
	if (bw.ns4) document.captureEvents(Event.MOUSEMOVE)
};
/********************************************************************
Contructs the cursorobjects
*********************************************************************/
function makeCursorObj(obj, nest) {
	createCursorDiv();
	nest = (!nest) ? '' : 'document.' + nest + '.'
	this.css = bw.dom ? document.getElementById(obj).style : bw.ie4 ? document.all[obj].style : bw.ns4 ? eval(nest + "document.layers." + obj) : 0;
	this.moveIt = b_moveIt;
	cursorInit();
	return this
};

function b_moveIt(x, y) {
	this.x = x;
	this.y = y;
	this.css.left = this.x + "px";
	this.css.top = this.y + "px";
};

function isIE() {
	var myNav = navigator.userAgent.toLowerCase();
	return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
};

function mergeChartConfig(defaults, userDefined) {
	var returnObj = {};
	for (var attrname in defaults) {
		returnObj[attrname] = defaults[attrname];
	}
	for (var attrnameBis in userDefined) {
		returnObj[attrnameBis] = userDefined[attrnameBis];
	}
	return returnObj;
};

function sleep(ms) {
	var dt = new Date();
	dt.setTime(dt.getTime() + ms);
	while (new Date().getTime() < dt.getTime()) {};
};

function saveCanvas(ctx, data, config) {
	cvSave = ctx.getImageData(0, 0, ctx.canvas.width, ctx.canvas.height);
	var saveCanvasConfig = {
		savePng: false,
		annotateDisplay: false,
		animation: false,
		dynamicDisplay: false
	};
	var savePngConfig = mergeChartConfig(config, saveCanvasConfig);
	savePngConfig.clearRect = false;
	/* And ink them */

	redrawGraph(ctx,data,savePngConfig);
	var image;
	if (config.savePngOutput == "NewWindow") {
		image = ctx.canvas.toDataURL();
		ctx.putImageData(cvSave, 0, 0);
		window.open(image, '_blank');
	}
	if (config.savePngOutput == "CurrentWindow") {
		image = ctx.canvas.toDataURL();
		ctx.putImageData(cvSave, 0, 0);
		window.location.href = image;
	}
	if (config.savePngOutput == "Save") {
		image = ctx.canvas.toDataURL();
		var downloadLink = document.createElement("a");
		downloadLink.href = image;
		downloadLink.download = config.savePngName + ".png";
		document.body.appendChild(downloadLink);
		downloadLink.click();
		document.body.removeChild(downloadLink);
	}
};
if (typeof String.prototype.trim !== 'function') {
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, '');
	}
};
var dynamicDisplay = new Array();
var dynamicDisplayList = new Array();

function dynamicFunction(data, config, ctx) {
	if (isIE() < 9 && isIE() != false) return(true);

	if (config.dynamicDisplay && ctx.firstPass==0) {
		if (ctx.canvas.id == "") {
			var cvdate = new Date();
			var cvmillsec = cvdate.getTime();
			ctx.canvas.id = "Canvas_" + cvmillsec;
		}
		if (typeof(dynamicDisplay[ctx.canvas.id]) == "undefined") {
			dynamicDisplayList[dynamicDisplayList["length"]] = ctx.canvas.id;
			dynamicDisplay[ctx.canvas.id] = [ctx, data, config];
			window.onscroll = scrollFunction;
		} 
		if (!isScrolledIntoView(ctx.canvas,config)) return false;
	}
	return true;
};

function isScrolledIntoView(element,config) {
	var xPosition = 0;
	var yPosition = 0;
	var eltWidth, eltHeight;
	if(typeof element.recomputedHeight=="undefined") {
		if (window.devicePixelRatio) {
			// 31/12/2015 - On retina display, the size of the canvas changes after the canvas is displayed.
			//              before it is displayd, the size on the screen is the size on non retina display;
                        //              If we do not divide the height & width by the devicePixelRatio, and if the
                        //              value of config.dynamicDisplayYPartOfChart and if there is a chart on to bottom of the
                        //              web page, this chart will never be displayed....
                        //              If the Size of the canvas was directly the real size displayed on the web page, we should not
                        //              divide the height/width by the devicePixelRatio.... (Bug in Brosers ?)
			element.recomputedHeight=element.height/window.devicePixelRatio;
			element.recomputedWidth=element.width/window.devicePixelRatio;
		} else {
			element.recomputedHeight=element.height;
			element.recomputedWidth=element.width;
		}
	}
	eltWidth=element.recomputedWidth;
	eltHeight=element.recomputedHeight;
	elem = element;
	while (elem) {
		xPosition += (elem.offsetLeft + elem.clientLeft);
		yPosition += (elem.offsetTop + elem.clientTop);
		elem = elem.offsetParent;
	}

	if (xPosition + (eltWidth * config.dynamicDisplayXPartOfChart) >= window.pageXOffset &&
		xPosition + (eltWidth * config.dynamicDisplayXPartOfChart) <= window.pageXOffset + window.innerWidth &&
		yPosition + (eltHeight * config.dynamicDisplayYPartOfChart) >= window.pageYOffset &&
		yPosition + (eltHeight * config.dynamicDisplayYPartOfChart) <= window.pageYOffset + window.innerHeight
	) {
		return (true);
	}
	else {
		return false;
	}
};

function scrollFunction() {
	for (var i = 0; i < dynamicDisplayList["length"]; i++) {
		if ((dynamicDisplay[dynamicDisplayList[i]][0]).firstPass==0) {
			redrawGraph(dynamicDisplay[dynamicDisplayList[i]][0],dynamicDisplay[dynamicDisplayList[i]][1], dynamicDisplay[dynamicDisplayList[i]][2]);
		}
	}
};

var jsGraphAnnotate = new Array();
var jsTextMousePos = new Array();
var mouseActionData=new Array();

function clearAnnotate(ctxid) {
	jsGraphAnnotate[ctxid] = [];
	jsTextMousePos[ctxid] = [];
};

function getMousePos(canvas, evt) {
	var rect = canvas.getBoundingClientRect();
	return {
		x: evt.clientX - rect.left,
		y: evt.clientY - rect.top
	};
};

function isHighLighted(reference,ctx,data,statData,posi,posj,othervars){
	var i;
	if(ctx.tpdata==0) {
		if(typeof data.special=="object") {
			for(i=data.special.length-1;i>=0;i--){
				if(data.special[i].typespecial=="highLight" && data.special[i].posi==posi && data.special[i].posj==posj) return true;
			}
		}
	} else {
		if(typeof data[0].special=="object") {
			for(i=data[0].special.length-1;i>=0;i--){
				if(data[0].special[i].typespecial=="highLight" && data[0].special[i].posi==posi) return true;
			}
		}
	}
	return false;
};

function isNotHighLighted(reference,ctx,data,statData,posi,posj,othervars) {
	return(!isHighLighted(reference,ctx,data,statData,posi,posj,othervars));
}
function deleteHighLight(ctx,data) {
	var i;
	if(ctx.tpdata==0) {
		if(typeof data.special=="object") {
			for(i=data.special.length-1;i>=0;i--){
				if(data.special[i].typespecial=="highLight") data.special.splice(i,1);
			}
		}
	} else {
		if(typeof data[0].special=="object") {
			for(i=data[0].special.length-1;i>=0;i--){
				if(data[0].special[i].typespecial=="highLight") data[0].special.splice(i,1);
			}
		}
	}

};

function highLightAction(action,ctx,data,config,v1,v2) {
	if (ctx.firstPass!=9)return;

	var currentlyDisplayed=[-1,-1];
	var redisplay=false;
	var i,j;


	if(ctx.tpdata==0) {
		if(typeof data.special=="object") {
			for(i=0;i<data.special.length && currentlyDisplayed[0]==-1;i++){
				if(data.special[i].typespecial=="highLight") currentlyDisplayed=[data.special[i].posi,data.special[i].posj];
			}
		}
	} else {
		if(typeof data[0].special=="object") {
			for(i=0;i<data[0].special.length && currentlyDisplayed[0]==-1;i++){
				if(data[0].special[i].typespecial=="highLight") currentlyDisplayed=[data[0].special[i].posi,-1];
			}
		}
	}
	var deleteOld=false;
	var addNew=false;
	var property;
	var output;

	if(action=="HIDE" && currentlyDisplayed[0]!=-1)deleteOld=true;
	else if(ctx.tpdata==0 && action!="HIDE" && config.highLightFullLine== "group" && (currentlyDisplayed[1]!=v2 )) { if(currentlyDisplayed[0]!=-1)deleteOld=true; addNew=true;}
	else if(ctx.tpdata==0 && action!="HIDE" && config.highLightFullLine== false && (currentlyDisplayed[0]!=v1 || currentlyDisplayed[1]!=v2)) { if(currentlyDisplayed[0]!=-1)deleteOld=true; addNew=true;}
	else if(ctx.tpdata==0 && action!="HIDE" && config.highLightFullLine== true && (currentlyDisplayed[0]!=v1 )) { if(currentlyDisplayed[0]!=-1)deleteOld=true; addNew=true;}
	else if(ctx.tpdata==1 && action!="HIDE" && currentlyDisplayed[0]!=v1) { if(currentlyDisplayed[0]!=-1)deleteOld=true; addNew=true;}
	
	if(deleteOld) {
		redisplay=true;
		if(ctx.tpdata==0) {
			for(i=data.special.length-1;i>=0;i--){
				if(data.special[i].typespecial=="highLight") data.special.splice(i,1);
			}
		} else {
			for(i=data[0].special.length-1;i>=0;i--){
				if(data[0].special[i].typespecial=="highLight") data[0].special.splice(i,1);
			}
		}
	}
	if(addNew) {
		redisplay=true;
		if(ctx.tpdata==0) {
			if(typeof data.special == "undefined") data.special=[];
			if(config.highLightFullLine == "group") {
				for(j=0;j<data.datasets.length;j++) {
					if(!(data.datasets[j].mouseDetection==false)) {
						output="";
						for (property in config.highLightSet) {
  							if (output !="") output=output+",";  
  							if(typeof config.highLightSet[property]=="string")output += property + ': "' + config.highLightSet[property]+'"';
  							else output += property + ': ' + config.highLightSet[property];
						}
        					eval("data.special[data.special.length]={"+output+"};");
						data.special[data.special.length-1].posi=j;
						data.special[data.special.length-1].posj=v2;
						data.special[data.special.length-1].typespecial="highLight";
					}
				}
			
			} else if(config.highLightFullLine == true) {
				if(!(data.datasets[v1].mouseDetection==false)) {
					for(j=0;j<data.datasets[v1].data.length;j++) {
						output="";
						for (property in config.highLightSet) {
  							if (output !="") output=output+",";  
  							if(typeof config.highLightSet[property]=="string")output += property + ': "' + config.highLightSet[property]+'"';
  							else output += property + ': ' + config.highLightSet[property];
						}
        					eval("data.special[data.special.length]={"+output+"};");
						data.special[data.special.length-1].posi=v1;
						data.special[data.special.length-1].posj=j;
						data.special[data.special.length-1].typespecial="highLight";
					}
				}
			} else if(!(data.datasets[v1].mouseDetection==false)) {
				output="";
				for (property in config.highLightSet) {
  					if (output !="") output=output+",";  
  					if(typeof config.highLightSet[property]=="string")output += property + ': "' + config.highLightSet[property]+'"';
  					else output += property + ': ' + config.highLightSet[property];
				}
        			eval("data.special[data.special.length]={"+output+"};");
				data.special[data.special.length-1].posi=v1;
				data.special[data.special.length-1].posj=v2;
				data.special[data.special.length-1].typespecial="highLight";
			}
		} else {
			if(typeof data[0].special == "undefined") data[0].special=[];
			output="";
			for (property in config.highLightSet) {
				if (output !="") output=output+",";  
				if(typeof config.highLightSet[property]=="string")output += property + ': "' + config.highLightSet[property]+'"';
				else output += property + ': ' + config.highLightSet[property];
			}
			eval("data[0].special[data[0].special.length]={"+output+"};");
			data[0].special[data[0].special.length-1].posi=v1;
			data[0].special[data[0].special.length-1].posj=v2;
			data[0].special[data[0].special.length-1].typespecial="highLight";
		}
	}
	if(redisplay==true) {
		updateChart(ctx,data,config,false,config.highLightRerunEndFunction);
	}
};

var inMouseAction=new Array();

function doMouseAction(event, ctx, action) {
	if (ctx.firstPass != 9)return;
	if(action=="mousedown") action=action+" "+event.which;
	if(ctx.mouseAction.indexOf(action)<0){return;}
	var config=mouseActionData[ctx.ChartNewId].config;
	var data=mouseActionData[ctx.ChartNewId].data;
	var i,prevShown,prevShowSaved;
	var inRect,P12,D12,D34,P13,D13,D24, y1,y2,y3,y4;
	var pieceOfChartFound=[];
	var textOnChartFound=[];
	var distance, angle,topY, bottomY,leftX, rightX;
	var mousewheelevt=(/Firefox/i.test(navigator.userAgent))? "DOMMouseScroll" : "mousewheel" 
	var canvas_pos = getMousePos(ctx.canvas, event);
	var realAction;
	var annotateDIV,showDiv;

	     if(action=="mousedown 1")realAction="mousedown left";
	else if(action=="mousedown 2")realAction="mousedown middle";
	else if(action=="mousedown 3")realAction="mousedown right";
	else if(action==mousewheelevt)realAction="mousewheel";
	else realAction=action;

	// search if mouse over one or more pieces of chart;

	for (i = 0; i < jsGraphAnnotate[ctx.ChartNewId]["length"]; i++) {
		if (jsGraphAnnotate[ctx.ChartNewId][i][0] == "ARC") {
			myStatData=jsGraphAnnotate[ctx.ChartNewId][i][3][jsGraphAnnotate[ctx.ChartNewId][i][1]];
			distance = Math.sqrt((canvas_pos.x - myStatData.midPosX) * (canvas_pos.x - myStatData.midPosX) + (canvas_pos.y - myStatData.midPosY) * (canvas_pos.y - myStatData.midPosY));
			if (distance > myStatData.int_radius && distance < myStatData.radiusOffset) {
				angle = (Math.acos((canvas_pos.x - myStatData.midPosX) / distance) % (2* Math.PI) + 2*Math.PI) % (2*Math.PI);
				if (canvas_pos.y < myStatData.midPosY) angle = -angle;
				angle = (((angle  + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI) ; 
				myStatData.startAngle=(((myStatData.startAngle  + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI);
				myStatData.endAngle=(((myStatData.endAngle  + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI);
				if(myStatData.endAngle<myStatData.startAngle)myStatData.endAngle+=2 * Math.PI;
				if ((angle > myStatData.startAngle && angle < myStatData.endAngle) || (angle > myStatData.startAngle - 2 * Math.PI && angle < myStatData.endAngle - 2 * Math.PI) || (angle > myStatData.startAngle + 2 * Math.PI && angle < myStatData.endAngle + 2 * Math.PI)) {
					myStatData.graphPosX = canvas_pos.x;
					myStatData.graphPosY = canvas_pos.y;
					pieceOfChartFound[pieceOfChartFound.length]={
						piece : i,
						myStatData: myStatData
					};
				}
			}
		} else if (jsGraphAnnotate[ctx.ChartNewId][i][0] == "RECT") {
			myStatData=jsGraphAnnotate[ctx.ChartNewId][i][3][jsGraphAnnotate[ctx.ChartNewId][i][1]][jsGraphAnnotate[ctx.ChartNewId][i][2]];
			topY=Math.max(myStatData.yPosBottom,myStatData.yPosTop);
			bottomY=Math.min(myStatData.yPosBottom,myStatData.yPosTop);
			if (topY-bottomY <config.annotateBarMinimumDetectionHeight) {
				topY=(topY+bottomY+config.annotateBarMinimumDetectionHeight)/2;
			        bottomY=topY-config.annotateBarMinimumDetectionHeight;
			}
			leftX=Math.min(myStatData.xPosLeft,myStatData.xPosRight);
			rightX=Math.max(myStatData.xPosLeft,myStatData.xPosRight);
			if (rightX-leftX <config.annotateBarMinimumDetectionHeight) {
				rightX=(rightX+leftX+config.annotateBarMinimumDetectionHeight)/2;
			        leftX=rightX-config.annotateBarMinimumDetectionHeight;
			}
			if (canvas_pos.x > leftX && canvas_pos.x < rightX && canvas_pos.y < topY && canvas_pos.y > bottomY) {
				myStatData.graphPosX = canvas_pos.x;
				myStatData.graphPosY = canvas_pos.y;
				pieceOfChartFound[pieceOfChartFound.length]={
					piece : i,
					myStatData: myStatData
				};
			}
		} else if (jsGraphAnnotate[ctx.ChartNewId][i][0] == "POINT") {
			myStatData=jsGraphAnnotate[ctx.ChartNewId][i][3][jsGraphAnnotate[ctx.ChartNewId][i][1]][jsGraphAnnotate[ctx.ChartNewId][i][2]];
			if(config.detectAnnotateOnFullLine) {
				if(canvas_pos.x < Math.min(myStatData.annotateStartPosX,myStatData.annotateEndPosX)-Math.ceil(ctx.chartSpaceScale*config.pointHitDetectionRadius) || canvas_pos.x > Math.max(myStatData.annotateStartPosX,myStatData.annotateEndPosX)+Math.ceil(ctx.chartSpaceScale*config.pointHitDetectionRadius) || canvas_pos.y < Math.min(myStatData.annotateStartPosY,myStatData.annotateEndPosY)-Math.ceil(ctx.chartSpaceScale*config.pointHitDetectionRadius) || canvas_pos.y > Math.max(myStatData.annotateStartPosY,myStatData.annotateEndPosY)+Math.ceil(ctx.chartSpaceScale*config.pointHitDetectionRadius)) {
					distance=Math.ceil(ctx.chartSpaceScale*config.pointHitDetectionRadius)+1;
				} else { 
					if(typeof myStatData.D1A=="undefined") {
						distance=Math.abs(canvas_pos.x-myStatData.posX);
					} else if(typeof myStatData.D2A=="undefined") {
						distance=Math.abs(canvas_pos.y-myStatData.posY);
					} else {
						var D2B=-myStatData.D2A*canvas_pos.x+canvas_pos.y;
						var g=-(myStatData.D1B-D2B)/(myStatData.D1A-myStatData.D2A);
						var h=myStatData.D2A*g+D2B;
						distance=Math.sqrt((canvas_pos.x - g) * (canvas_pos.x - g) + (canvas_pos.y - h) * (canvas_pos.y - h));
					}
				}
			} else {
				distance = Math.sqrt((canvas_pos.x - myStatData.posX) * (canvas_pos.x - myStatData.posX) + (canvas_pos.y - myStatData.posY) * (canvas_pos.y - myStatData.posY));
			}
			if (distance < Math.ceil(ctx.chartSpaceScale*config.pointHitDetectionRadius)) {
				myStatData.graphPosX = canvas_pos.x;
				myStatData.graphPosY = canvas_pos.y;
				pieceOfChartFound[pieceOfChartFound.length]={
					piece : i,
					myStatData: myStatData
				};
			}
		}
	}

	// search if mouse over one or more text;
	if(config.detectMouseOnText) {
		for(i=0;i<jsTextMousePos[ctx.ChartNewId]["length"];i++){
		        inRect=true;
			if(Math.abs(jsTextMousePos[ctx.ChartNewId][i][3].p1 - jsTextMousePos[ctx.ChartNewId][i][3].p2) < config.zeroValue) {
				// Horizontal;
				if(canvas_pos.x < Math.min(jsTextMousePos[ctx.ChartNewId][i][2].p1,jsTextMousePos[ctx.ChartNewId][i][2].p2))inRect=false; 
				if(canvas_pos.x > Math.max(jsTextMousePos[ctx.ChartNewId][i][2].p1,jsTextMousePos[ctx.ChartNewId][i][2].p2))inRect=false; 
				if(canvas_pos.y < Math.min(jsTextMousePos[ctx.ChartNewId][i][3].p1,jsTextMousePos[ctx.ChartNewId][i][3].p3))inRect=false; 
				if(canvas_pos.y > Math.max(jsTextMousePos[ctx.ChartNewId][i][3].p1,jsTextMousePos[ctx.ChartNewId][i][3].p3))inRect=false; 
			} else if(Math.abs(jsTextMousePos[ctx.ChartNewId][i][2].p1 - jsTextMousePos[ctx.ChartNewId][i][2].p2)<config.zeroValue) {
				// Vertical;
				if(canvas_pos.x < Math.min(jsTextMousePos[ctx.ChartNewId][i][2].p1,jsTextMousePos[ctx.ChartNewId][i][2].p3))inRect=false; 
				if(canvas_pos.x > Math.max(jsTextMousePos[ctx.ChartNewId][i][2].p1,jsTextMousePos[ctx.ChartNewId][i][2].p3))inRect=false; 
				if(canvas_pos.y < Math.min(jsTextMousePos[ctx.ChartNewId][i][3].p1,jsTextMousePos[ctx.ChartNewId][i][3].p2))inRect=false; 
				if(canvas_pos.y > Math.max(jsTextMousePos[ctx.ChartNewId][i][3].p1,jsTextMousePos[ctx.ChartNewId][i][3].p2))inRect=false; 
			} else {
				// D12 & D34;

				P12=Math.tan(jsTextMousePos[ctx.ChartNewId][i][4]);
				D12=jsTextMousePos[ctx.ChartNewId][i][3].p1-P12*jsTextMousePos[ctx.ChartNewId][i][2].p1;
				D34=jsTextMousePos[ctx.ChartNewId][i][3].p3-P12*jsTextMousePos[ctx.ChartNewId][i][2].p3;
				// D13 & D24;
				P13=-1/P12;
				D13=jsTextMousePos[ctx.ChartNewId][i][3].p1-P13*jsTextMousePos[ctx.ChartNewId][i][2].p1;
				D24=jsTextMousePos[ctx.ChartNewId][i][3].p4-P13*jsTextMousePos[ctx.ChartNewId][i][2].p4;
				// Check if in rectangle;
					
				y1=P12*canvas_pos.x+D12;
				y2=P12*canvas_pos.x+D34;
				y3=P13*canvas_pos.x+D13;
				y4=P13*canvas_pos.x+D24;
				
				if(canvas_pos.y < Math.min(y1,y2))inRect=false;
				if(canvas_pos.y > Math.max(y1,y2))inRect=false;
				if(canvas_pos.y < Math.min(y3,y4))inRect=false;
				if(canvas_pos.y > Math.max(y3,y4))inRect=false;
			}
			if(inRect)textOnChartFound[textOnChartFound.length]=i;
		}
	}

	function isAction(option,action) {
		return(option==action || (option=="mousemove" && (action=="mousewheel" || action=="mouseout")));
	}

	if(config.savePng && isAction(config.savePngFunction,realAction)) {
		// call savePng function;
		saveCanvas(ctx, data, config);
	}
	
	var whoToReferAnnotate=-1;
	var whoToReferHighLight=-1;
	var referAnnotateIsPoint=false;
	var referHighLightIsPoint=false;
	for(i=pieceOfChartFound.length-1;i>=0;i--) {
		if (jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[i].piece][4]) {
			if(referAnnotateIsPoint==false) {
				if(whoToReferAnnotate==-1)whoToReferAnnotate=i;
				if(jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[i].piece][0] == "POINT") {
					whoToReferAnnotate=i;
					referAnnotateIsPoint=true;
				}
			}
		}
		if(referHighLightIsPoint==false) {
			if(whoToReferHighLight==-1)whoToReferHighLight=i;
			if(jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[i].piece][0] == "POINT") {
				whoToReferHighLight=i;
				referHighLightIsPoint=true;
			}
		}
		
	}
	
	if(config.annotateDisplay && isAction(config.annotateFunction,realAction)) {
		// annotate display functionality;
		annotateDIV = document.getElementById('divCursor');
		annotateDIV.style.display = false ? '' : 'none';
		if(pieceOfChartFound.length>0) {
			annotateDIV.className = (config.annotateClassName) ? config.annotateClassName : '';
			annotateDIV.style.border = (config.annotateClassName) ? '' : config.annotateBorder;
			annotateDIV.style.padding = (config.annotateClassName) ? '' : config.annotatePadding;
			annotateDIV.style.borderRadius = (config.annotateClassName) ? '' : config.annotateBorderRadius;
			annotateDIV.style.backgroundColor = (config.annotateClassName) ? '' : config.annotateBackgroundColor;
			annotateDIV.style.color = (config.annotateClassName) ? '' : config.annotateFontColor;
			annotateDIV.style.fontFamily = (config.annotateClassName) ? '' : config.annotateFontFamily;
			annotateDIV.style.fontSize = (config.annotateClassName) ? '' : (Math.ceil(ctx.chartTextScale*config.annotateFontSize)).toString() + "pt";
			annotateDIV.style.fontStyle = (config.annotateClassName) ? '' : config.annotateFontStyle;
			annotateDIV.style.zIndex = 999;
			ctx.save();
			ctx.font= annotateDIV.style.fontStyle+" "+ annotateDIV.style.fontSize+" "+annotateDIV.style.fontFamily;
			var rect = ctx.canvas.getBoundingClientRect();
			showDiv=false;
			if(whoToReferAnnotate!=-1) {
				if (jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferAnnotate].piece][4]) {
					if (jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferAnnotate].piece][0] == "ARC") dispString = tmplbis(setOptionValue(true,1,"ANNOTATELABEL",ctx,data,jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferAnnotate].piece][3],undefined,config.annotateLabel,"annotateLabel",jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferAnnotate].piece][1],-1,{otherVal:true}), pieceOfChartFound[whoToReferAnnotate].myStatData,config);
					else dispString = tmplbis(setOptionValue(true,1,"ANNOTATELABEL",ctx,data,jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferAnnotate].piece][3],undefined,config.annotateLabel,"annotateLabel",jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferAnnotate].piece][1],jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferAnnotate].piece][2],{otherVal:true}), pieceOfChartFound[whoToReferAnnotate].myStatData,config);
					textMsr=ctx.measureTextMultiLine(dispString,1*annotateDIV.style.fontSize.replace("pt",""));
					ctx.restore();
					annotateDIV.innerHTML = dispString;
					x = bw.ns4 || bw.ns5 ? event.pageX : event.x;
					y = bw.ns4 || bw.ns5 ? event.pageY : event.y;
					if (bw.ie4 || bw.ie5) y = y + eval(scrolled);
					if(config.annotateRelocate===true) {
						var relocateX, relocateY;
						relocateX=0;relocateY=0;
				 		if(x+fromLeft+textMsr.textWidth > window.innerWidth-rect.left-fromLeft)relocateX=-textMsr.textWidth;
		 				if(y+fromTop+textMsr.textHeight > 1*window.innerHeight-1*rect.top+fromTop)relocateY-=(textMsr.textHeight+2*fromTop);
						oCursor.moveIt(Math.max(8-rect.left,x + fromLeft+relocateX), Math.max(8-rect.top,y + fromTop + relocateY));
					} else oCursor.moveIt(x + fromLeft, y + fromTop);
					annotateDIV.style.display = true ? '' : 'none'; 
					showDiv=true;
				}
			}
		}		
		

	}

	if(inMouseAction[ctx.ChartNewId]==false && mouseActionData[ctx.ChartNewId].prevShow>=0 && isAction("mousemove",realAction) && (pieceOfChartFound.length==0 || mouseActionData[ctx.ChartNewId].prevShow!=pieceOfChartFound[pieceOfChartFound.length-1].piece)) {
                inMouseAction[ctx.ChartNewId]=true;
		prevShow=mouseActionData[ctx.ChartNewId].prevShow;
		mouseActionData[ctx.ChartNewId].prevShow=-1;

	       	if(config.highLight && isAction(config.highLightMouseFunction,realAction) && pieceOfChartFound.length==0) {
			highLightAction("HIDE",ctx,data,config,null,null);
		}	
                	
		if(typeof config.annotateFunctionOut=="function") {
			if(jsGraphAnnotate[ctx.ChartNewId][prevShow][0] == "ARC")config.annotateFunctionOut("OUTANNOTATE",ctx,data,jsGraphAnnotate[ctx.ChartNewId][prevShow][3],jsGraphAnnotate[ctx.ChartNewId][prevShow][1],-1,null);
			else  config.annotateFunctionOut("OUTANNOTATE",ctx,data,jsGraphAnnotate[ctx.ChartNewId][prevShow][3],jsGraphAnnotate[ctx.ChartNewId][prevShow][1],jsGraphAnnotate[ctx.ChartNewId][prevShow][2],null);
		}
		inMouseAction[ctx.ChartNewId]=false;
	}
	

	if(pieceOfChartFound.length>0 && inMouseAction[ctx.ChartNewId]==false && mouseActionData[ctx.ChartNewId].prevShow!=pieceOfChartFound[whoToReferHighLight].piece && isAction("mousemove",realAction)) {
		inMouseAction[ctx.ChartNewId]=true;
		prevShow=mouseActionData[ctx.ChartNewId].prevShow;
	       	if(config.highLight && isAction(config.highLightMouseFunction,realAction)) {
			if(whoToReferHighLight!=-1) {
				if(jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferHighLight].piece][0] == "ARC") highLightAction("ARC",ctx,data,config,jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferHighLight].piece][1],-1);
				else highLightAction(jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferHighLight].piece][0],ctx,data,config,jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferHighLight].piece][1],jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferHighLight].piece][2]);
			}
		}	

		if(typeof config.annotateFunctionIn=="function") {
			if(jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[pieceOfChartFound.length-1].piece][0] == "ARC")config.annotateFunctionIn("INANNOTATE",ctx,data,jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[pieceOfChartFound.length-1].piece][3],jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[pieceOfChartFound.length-1].piece][1],-1,null);
			else  config.annotateFunctionIn("INANNOTATE",ctx,data,jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[pieceOfChartFound.length-1].piece][3],jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[pieceOfChartFound.length-1].piece][1],jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[pieceOfChartFound.length-1].piece][2],null);
		}
		inMouseAction[ctx.ChartNewId]=false;
		mouseActionData[ctx.ChartNewId].prevShow=pieceOfChartFound[whoToReferHighLight].piece;
	}

       	if (config.highLight && isAction(config.highLightMouseFunction,realAction) && isAction("mousemove",realAction)==false) {
		if(pieceOfChartFound.length==0)highLightAction("HIDE",ctx,data,config,null,null);
		else {
			if(whoToReferHighLight!=-1) {
				if(jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferHighLight].piece][0] == "ARC") highLightAction("ARC",ctx,data,config,jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferHighLight].piece][1],-1);
				else highLightAction(jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferHighLight].piece][0],ctx,data,config,jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferHighLight].piece][1],jsGraphAnnotate[ctx.ChartNewId][pieceOfChartFound[whoToReferHighLight].piece][2]);
			}
		}
	}


	function runFunction(toBeRun){
		var i;
		for(i=0;i<pieceOfChartFound.length;i++) {
			toBeRun(event, ctx, config, data, pieceOfChartFound[i].myStatData);
		}
		for(i=0;i<textOnChartFound.length;i++) {
			toBeRun(event, ctx, config, data, {type:"CLICKONTEXT",values:jsTextMousePos[ctx.ChartNewId][textOnChartFound[i]]});
		}
		if(pieceOfChartFound.length==0 && textOnChartFound.length==0) {
			toBeRun(event, ctx, config, data, null);
		}
	};

	if(typeof config.mouseMove=="function" && isAction("mousemove",realAction)) runFunction(config.mouseMove);
	if(typeof config.mouseDownLeft=="function" && isAction("mousedown left",realAction))runFunction(config.mouseDownLeft);
	if(typeof config.mouseDownRight=="function" && isAction("mousedown right",realAction)) runFunction(config.mouseDownRight);
	if(typeof config.mouseDownMiddle=="function" && isAction("mousedown middle",realAction)) runFunction(config.mouseDownMiddle);
	if(typeof config.mouseWheel=="function" && isAction("mousewheel",realAction)) runFunction(config.mouseWheel);
	if(typeof config.mouseOut=="function" && isAction("mouseout",realAction)) runFunction(config.mouseOut);
	if(typeof config.mouseDblClick=="function" && isAction("dblclick",realAction)) runFunction(config.mouseDblClick);
};
                         
///////// GRAPHICAL PART OF THE SCRIPT ///////////////////////////////////////////
//Define the global Chart Variable as a class.
window.Chart2 = function(context) {
	var chart = this;
	//Easing functions adapted from Robert Penner's easing equations
	//http://www.robertpenner.com/easing/
	var animationOptions = {
		linear: function(t) {
			return t;
		},
		easeInQuad: function(t) {
			return t * t;
		},
		easeOutQuad: function(t) {
			return -1 * t * (t - 2);
		},
		easeInOutQuad: function(t) {
			if ((t /= 1 / 2) < 1) return 1 / 2 * t * t;
			return -1 / 2 * ((--t) * (t - 2) - 1);
		},
		easeInCubic: function(t) {
			return t * t * t;
		},
		easeOutCubic: function(t) {
			return 1 * ((t = t / 1 - 1) * t * t + 1);
		},
		easeInOutCubic: function(t) {
			if ((t /= 1 / 2) < 1) return 1 / 2 * t * t * t;
			return 1 / 2 * ((t -= 2) * t * t + 2);
		},
		easeInQuart: function(t) {
			return t * t * t * t;
		},
		easeOutQuart: function(t) {
			return -1 * ((t = t / 1 - 1) * t * t * t - 1);
		},
		easeInOutQuart: function(t) {
			if ((t /= 1 / 2) < 1) return 1 / 2 * t * t * t * t;
			return -1 / 2 * ((t -= 2) * t * t * t - 2);
		},
		easeInQuint: function(t) {
			return 1 * (t /= 1) * t * t * t * t;
		},
		easeOutQuint: function(t) {
			return 1 * ((t = t / 1 - 1) * t * t * t * t + 1);
		},
		easeInOutQuint: function(t) {
			if ((t /= 1 / 2) < 1) return 1 / 2 * t * t * t * t * t;
			return 1 / 2 * ((t -= 2) * t * t * t * t + 2);
		},
		easeInSine: function(t) {
			return -1 * Math.cos(t / 1 * (Math.PI / 2)) + 1;
		},
		easeOutSine: function(t) {
			return 1 * Math.sin(t / 1 * (Math.PI / 2));
		},
		easeInOutSine: function(t) {
			return -1 / 2 * (Math.cos(Math.PI * t / 1) - 1);
		},
		easeInExpo: function(t) {
			return (t == 0) ? 1 : 1 * Math.pow(2, 10 * (t / 1 - 1));
		},
		easeOutExpo: function(t) {
			return (t == 1) ? 1 : 1 * (-Math.pow(2, -10 * t / 1) + 1);
		},
		easeInOutExpo: function(t) {
			if (t == 0) return 0;
			if (t == 1) return 1;
			if ((t /= 1 / 2) < 1) return 1 / 2 * Math.pow(2, 10 * (t - 1));
			return 1 / 2 * (-Math.pow(2, -10 * --t) + 2);
		},
		easeInCirc: function(t) {
			if (t >= 1) return t;
			return -1 * (Math.sqrt(1 - (t /= 1) * t) - 1);
		},
		easeOutCirc: function(t) {
			return 1 * Math.sqrt(1 - (t = t / 1 - 1) * t);
		},
		easeInOutCirc: function(t) {
			if ((t /= 1 / 2) < 1) return -1 / 2 * (Math.sqrt(1 - t * t) - 1);
			return 1 / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1);
		},
		easeInElastic: function(t) {
			var s = 1.70158;
			var p = 0;
			var a = 1;
			if (t == 0) return 0;
			if ((t /= 1) == 1) return 1;
			if (!p) p = 1 * .3;
			if (a < Math.abs(1)) {
				a = 1;
				s = p / 4;
			} else s = p / (2 * Math.PI) * Math.asin(1 / a);
			return -(a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * 1 - s) * (2 * Math.PI) / p));
		},
		easeOutElastic: function(t) {
			var s = 1.70158;
			var p = 0;
			var a = 1;
			if (t == 0) return 0;
			if ((t /= 1) == 1) return 1;
			if (!p) p = 1 * .3;
			if (a < Math.abs(1)) {
				a = 1;
				s = p / 4;
			} else s = p / (2 * Math.PI) * Math.asin(1 / a);
			return a * Math.pow(2, -10 * t) * Math.sin((t * 1 - s) * (2 * Math.PI) / p) + 1;
		},
		easeInOutElastic: function(t) {
			var s = 1.70158;
			var p = 0;
			var a = 1;
			if (t == 0) return 0;
			if ((t /= 1 / 2) == 2) return 1;
			if (!p) p = 1 * (.3 * 1.5);
			if (a < Math.abs(1)) {
				a = 1;
				s = p / 4;
			} else s = p / (2 * Math.PI) * Math.asin(1 / a);
			if (t < 1) return -.5 * (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * 1 - s) * (2 * Math.PI) / p));
			return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * 1 - s) * (2 * Math.PI) / p) * .5 + 1;
		},
		easeInBack: function(t) {
			var s = 1.70158;
			return 1 * (t /= 1) * t * ((s + 1) * t - s);
		},
		easeOutBack: function(t) {
			var s = 1.70158;
			return 1 * ((t = t / 1 - 1) * t * ((s + 1) * t + s) + 1);
		},
		easeInOutBack: function(t) {
			var s = 1.70158;
			if ((t /= 1 / 2) < 1) return 1 / 2 * (t * t * (((s *= (1.525)) + 1) * t - s));
			return 1 / 2 * ((t -= 2) * t * (((s *= (1.525)) + 1) * t + s) + 2);
		},
		easeInBounce: function(t) {
			return 1 - animationOptions.easeOutBounce(1 - t);
		},
		easeOutBounce: function(t) {
			if ((t /= 1) < (1 / 2.75)) {
				return 1 * (7.5625 * t * t);
			} else if (t < (2 / 2.75)) {
				return 1 * (7.5625 * (t -= (1.5 / 2.75)) * t + .75);
			} else if (t < (2.5 / 2.75)) {
				return 1 * (7.5625 * (t -= (2.25 / 2.75)) * t + .9375);
			} else {
				return 1 * (7.5625 * (t -= (2.625 / 2.75)) * t + .984375);
			}
		},
		easeInOutBounce: function(t) {
			if (t < 1 / 2) return animationOptions.easeInBounce(t * 2) * .5;
			return animationOptions.easeOutBounce(t * 2 - 1) * .5 + 1 * .5;
		}
	};
	//Variables global to the chart
	
	var width = context.canvas.width;
	var height = context.canvas.height;
    
	this.PolarArea = function(data, options) {
		chart.PolarArea.defaults = {
			highLightSet : { color : "red" },
			inGraphDataShow: false,
			inGraphDataPaddingRadius: 5,
			inGraphDataPaddingAngle: 0,
			inGraphDataTmpl: "<%=(v1 == ''? '' : v1+':')+ v2 + ' (' + v6 + ' %)'%>",
			inGraphDataAlign: "off-center", // "right", "center", "left", "off-center" or "to-center"
			inGraphDataVAlign: "off-center", // "bottom", "center", "top", "off-center" or "to-center"
			inGraphDataRotate: 0, // rotateAngle value (0->360) , "inRadiusAxis" or "inRadiusAxisRotateLabels"
			inGraphDataFontFamily: "'Arial'",
			inGraphDataFontSize: 12,
			inGraphDataFontStyle: "normal",
			inGraphDataFontColor: "#666",
			inGraphDataRadiusPosition: 3,
			inGraphDataAnglePosition: 2,
			scaleOverlay: true,
			scaleOverride: false,
			scaleOverride2: false,
			scaleGridLinesStep : 1,
			scaleSteps: null,
			scaleStepWidth: null,
			scaleStartValue: null,
			scaleShowLine: true,
			scaleLineColor: "rgba(0,0,0,.1)",
			scaleLineWidth: 1,
			scaleLineStyle: "solid",
			scaleShowLabels: true,
			scaleShowLabels2: true,
			scaleLabel: "<%=value%>",
			scaleFontFamily: "'Arial'",
			scaleFontSize: 12,
			scaleFontStyle: "normal",
			scaleFontColor: "#666",
			scaleShowLabelBackdrop: true,
			scaleBackdropColor: "rgba(255,255,255,0.75)",
			scaleBackdropPaddingY: 2,
			scaleBackdropPaddingX: 2,
			segmentShowStroke: true,
			segmentStrokeColor: "#fff",
			segmentStrokeStyle: "solid",
			segmentStrokeWidth: 2,
			animation: true,
			animationByData : "ByArc",
			animationSteps: 100,
			animationEasing: "easeOutBounce",
			animateRotate: true,
			animateScale: false,
			onAnimationComplete: null,
			annotateLabel: "<%=(v1 == ''? '' : v1+':')+ v2 + ' (' + v6 + ' %)'%>",
			startAngle: 90,
			totalAmplitude : 360,
			radiusScale : 1
		};
		if(isIE()<9 && isIE() != false)chart.PolarArea.defaults = mergeChartConfig(chart.defaults.IExplorer8, chart.PolarArea.defaults);
		chart.PolarArea.defaults = mergeChartConfig(chart.defaults.commonOptions, chart.PolarArea.defaults);
		chart.PolarArea.defaults = mergeChartConfig(chart.PolarArea.defaults, charJSPersonalDefaultOptions);
		chart.PolarArea.defaults = mergeChartConfig(chart.PolarArea.defaults, charJSPersonalDefaultOptionsPolarArea);
		var config = (options) ? mergeChartConfig(chart.PolarArea.defaults, options) : chart.PolarArea.defaults;
		return new PolarArea(data, config, context);
	};
	this.Radar = function(data, options) {
		chart.Radar.defaults = {
			highLightSet : { pointDotRadius: 15, pointDot : true },
			inGraphDataShow: false,
			inGraphDataPaddingRadius: 5,
			inGraphDataTmpl: "<%=v3%>",
			inGraphDataAlign: "off-center", // "right", "center", "left", "off-center" or "to-center"
			inGraphDataVAlign: "off-center", // "right", "center", "left", "off-center" or "to-center"
			inGraphDataRotate: 0, // rotateAngle value (0->360) , "inRadiusAxis" or "inRadiusAxisRotateLabels"
			inGraphDataFontFamily: "'Arial'",
			inGraphDataFontSize: 12,
			inGraphDataFontStyle: "normal",
			inGraphDataFontColor: "#666",
			inGraphDataRadiusPosition: 3,
			yAxisMinimumInterval: "none",
			scaleGridLinesStep : 1,
			scaleOverlay: false,
			scaleOverride: false,
			scaleOverride2: false,
			scaleSteps: null,
			scaleStepWidth: null,
			scaleStartValue: null,
			scaleShowLine: true,
			scaleLineColor: "rgba(0,0,0,.1)",
			scaleLineStyle: "solid",
			scaleLineWidth: 1,
			scaleShowLabels: false,
			scaleShowLabels2: true,
			scaleLabel: "<%=value%>",
			scaleFontFamily: "'Arial'",
			scaleFontSize: 12,
			scaleFontStyle: "normal",
			scaleFontColor: "#666",
			scaleShowLabelBackdrop: true,
			scaleBackdropColor: "rgba(255,255,255,0.75)",
			scaleBackdropPaddingY: 2,
			scaleBackdropPaddingX: 2,
			angleShowLineOut: true,
			angleLineColor: "rgba(0,0,0,.1)",
			angleLineStyle: "solid",
			angleLineWidth: 1,
			pointLabelFontFamily: "'Arial'",
			pointLabelFontStyle: "normal",
			pointLabelFontSize: 12,
			pointLabelFontColor: "#666",
			pointDot: true,
			pointDotRadius: 3,
			pointDotStrokeWidth: 1,
			pointDotStrokeStyle:"solid",
			datasetFill: true,
			datasetStrokeWidth: 2,
			datasetStrokeStyle:"solid",
			animation: true,
			animationSteps: 60,
			animationEasing: "easeOutQuart",
			onAnimationComplete: null,
			annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3%>",
			pointHitDetectionRadius : 10,
			startAngle: 90
		};
		// merge annotate defaults
		if(isIE()<9 && isIE() != false)chart.Radar.defaults = mergeChartConfig(chart.defaults.IExplorer8, chart.Radar.defaults);
		chart.Radar.defaults = mergeChartConfig(chart.defaults.commonOptions, chart.Radar.defaults);
		chart.Radar.defaults = mergeChartConfig(chart.Radar.defaults, charJSPersonalDefaultOptions);
		chart.Radar.defaults = mergeChartConfig(chart.Radar.defaults, charJSPersonalDefaultOptionsRadar);
		var config = (options) ? mergeChartConfig(chart.Radar.defaults, options) : chart.Radar.defaults;
		return new Radar(data, config, context);
	};
	this.Pie = function(data, options) {
		chart.Pie.defaults = chart.defaults.PieAndDoughnut;
		// merge annotate defaults
		if(isIE()<9 && isIE() != false)chart.Pie.defaults = mergeChartConfig(chart.defaults.IExplorer8, chart.Pie.defaults);
		chart.Pie.defaults = mergeChartConfig(chart.defaults.commonOptions, chart.Pie.defaults);
		chart.Pie.defaults = mergeChartConfig(chart.Pie.defaults, charJSPersonalDefaultOptions);
		chart.Pie.defaults = mergeChartConfig(chart.Pie.defaults, charJSPersonalDefaultOptionsPie);
		var config = (options) ? mergeChartConfig(chart.Pie.defaults, options) : chart.Pie.defaults;
		return new Pie(data, config, context);
	};
	this.Doughnut = function(data, options) {
		chart.Doughnut.defaults = chart.defaults.PieAndDoughnut;
		// merge annotate defaults
		if(isIE()<9 && isIE() != false)chart.Doughnut.defaults = mergeChartConfig(chart.defaults.IExplorer8, chart.Doughnut.defaults);
		chart.Doughnut.defaults = mergeChartConfig(chart.defaults.commonOptions, chart.Doughnut.defaults);
		chart.Doughnut.defaults = mergeChartConfig(chart.Doughnut.defaults, charJSPersonalDefaultOptions);
		chart.Doughnut.defaults = mergeChartConfig(chart.Doughnut.defaults, charJSPersonalDefaultOptionsDoughnut);
		var config = (options) ? mergeChartConfig(chart.Doughnut.defaults, options) : chart.Doughnut.defaults;
		return new Doughnut(data, config, context);
	};
	this.Line = function(data, options) {
		chart.Line.defaults = {
			inGraphDataShow: false,
			highLightSet : { pointDotRadius: 15, pointDot : true },
			inGraphDataPaddingX: 3,
			inGraphDataPaddingY: 3,
			inGraphDataTmpl: "<%=v3%>",
			inGraphDataAlign: "left",
			inGraphDataVAlign: "bottom",
			inGraphDataRotate: 0,
			inGraphDataFontFamily: "'Arial'",
			inGraphDataFontSize: 12,
			inGraphDataFontStyle: "normal",
			inGraphDataFontColor: "#666",
			drawXScaleLine: [{
				position: "bottom"
			}],
			scaleOverlay: false,
			scaleOverride: false,
			scaleOverride2: false,
			scaleSteps: null,
			scaleStepWidth: null,
			scaleStartValue: null,
			scaleSteps2: null,
			scaleStepWidth2: null,
			scaleStartValue2: null,
			scaleLabel2 : "<%=value%>",
			scaleLineColor: "rgba(0,0,0,.1)",
			scaleLineStyle: "solid",
			scaleLineWidth: 1,
			scaleShowLabels: true,
			scaleShowLabels2: true,
			scaleLabel: "<%=value%>",
			scaleFontFamily: "'Arial'",
			scaleFontSize: 12,
			scaleFontStyle: "normal",
			scaleFontColor: "#666",
			scaleShowGridLines: true,
			scaleXGridLinesStep: 1,
			scaleYGridLinesStep: 1,
			scaleGridLineColor: "rgba(0,0,0,.05)",
			scaleGridLineStyle: "solid",
			scaleGridLineWidth: 1,
			showYAxisMin: true, // Show the minimum value on Y axis (in original version, this minimum is not displayed - it can overlap the X labels)
			rotateLabels: "smart", // smart <=> 0 degre if space enough; otherwise 45 degres if space enough otherwise90 degre; 
			// you can force an integer value between 0 and 180 degres
			logarithmic: false, // can be 'fuzzy',true and false ('fuzzy' => if the gap between min and maximum is big it's using a logarithmic y-Axis scale
			logarithmic2: false, // can be 'fuzzy',true and false ('fuzzy' => if the gap between min and maximum is big it's using a logarithmic y-Axis scale
			scaleTickSizeLeft: 5,
			scaleTickSizeRight: 5,
			scaleTickSizeBottom: 5,
			scaleTickSizeTop: 5,
			bezierCurve: true,
			linkType : 0,   //0 : direct point to point; 1 = vertical lines; 2=angular link;
			bezierCurveTension : 0.4,
			pointDot: true,
			pointDotRadius: 4,
			pointDotStrokeStyle: "solid",
			pointDotStrokeWidth: 2,
			datasetStrokeStyle: "solid",
			datasetStrokeWidth: 2,
			datasetFill: true,
			animation: true,
			animationSteps: 60,
			animationEasing: "easeOutQuart",
			extrapolateMissingData: true,
			onAnimationComplete: null,
			annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3%>",
			pointHitDetectionRadius : 10
		};
		// merge annotate defaults
		if(isIE()<9 && isIE() != false)chart.Line.defaults = mergeChartConfig(chart.defaults.IExplorer8, chart.Line.defaults);
		chart.Line.defaults = mergeChartConfig(chart.defaults.commonOptions, chart.Line.defaults);
		chart.Line.defaults = mergeChartConfig(chart.defaults.xyAxisCommonOptions, chart.Line.defaults);
		chart.Line.defaults = mergeChartConfig(chart.Line.defaults, charJSPersonalDefaultOptions);
		chart.Line.defaults = mergeChartConfig(chart.Line.defaults, charJSPersonalDefaultOptionsLine);
		var config = (options) ? mergeChartConfig(chart.Line.defaults, options) : chart.Line.defaults;
		return new Line(data, config, context);
	};
	this.StackedBar = function(data, options) {
		chart.StackedBar.defaults = {
			annotateBarMinimumDetectionHeight : 0,
			highLightSet : { pointDotRadius: 15, barStrokeWidth : 5, pointDot : true },
			inGraphDataShow: false,
			inGraphDataPaddingX: 0,
			inGraphDataPaddingY: -3,
			inGraphDataTmpl: "<%=v3%>",
			inGraphDataAlign: "center",
			inGraphDataVAlign: "top",
			inGraphDataRotate: 0,
			inGraphDataFontFamily: "'Arial'",
			inGraphDataFontSize: 12,
			inGraphDataFontStyle: "normal",
			inGraphDataFontColor: "#666",
			inGraphDataXPosition: 2,
			inGraphDataYPosition: 3,
			scaleOverlay: false,
			scaleOverride: false,
			scaleOverride2: false,
			scaleSteps: null,
			scaleStepWidth: null,
			scaleStartValue: null,
			scaleLineColor: "rgba(0,0,0,.1)",
			scaleLineStyle: "solid",
			scaleLineWidth: 1,
			scaleShowLabels: true,
			scaleShowLabels2: true,
			scaleLabel: "<%=value%>",
			scaleFontFamily: "'Arial'",
			scaleFontSize: 12,
			scaleFontStyle: "normal",
			scaleFontColor: "#666",
			scaleShowGridLines: true,
			scaleXGridLinesStep: 1,
			scaleYGridLinesStep: 1,
			scaleGridLineColor: "rgba(0,0,0,.05)",
			scaleGridLineStyle: "solid",
			scaleGridLineWidth: 1,
			showYAxisMin: true, // Show the minimum value on Y axis (in original version, this minimum is not displayed - it can overlap the X labels)
			rotateLabels: "smart", // smart <=> 0 degre if space enough; otherwise 45 degres if space enough otherwise90 degre; 
			// you can force an integer value between 0 and 180 degres
			scaleTickSizeLeft: 5,
			scaleTickSizeRight: 5,
			scaleTickSizeBottom: 5,
			scaleTickSizeTop: 5,
			pointDot: true,
			pointDotRadius: 4,
			pointDotStrokeStyle: "solid",
			pointDotStrokeWidth: 2,
			barShowStroke: true,
//			barStrokeStyle: "solid",
			barStrokeWidth: 2,
			barValueSpacing: 5,
			barDatasetSpacing: 1,
			spaceBetweenBar : 0,
			animation: true,
			animationSteps: 60,
			animationEasing: "easeOutQuart",
			onAnimationComplete: null,
			bezierCurve: true,
			linkType : 0,   //0 : direct point to point; 1 = vertical lines; 2=angular link;
			bezierCurveTension : 0.4,
			annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3 + ' (' + v6 + ' %)'%>",
			pointHitDetectionRadius : 10
		};
		// merge annotate defaults
		if(isIE()<9 && isIE() != false)chart.StackedBar.defaults = mergeChartConfig(chart.defaults.IExplorer8, chart.StackedBar.defaults);
		chart.StackedBar.defaults = mergeChartConfig(chart.defaults.commonOptions, chart.StackedBar.defaults);
		chart.StackedBar.defaults = mergeChartConfig(chart.defaults.xyAxisCommonOptions, chart.StackedBar.defaults);
		chart.StackedBar.defaults = mergeChartConfig(chart.StackedBar.defaults, charJSPersonalDefaultOptions);
		chart.StackedBar.defaults = mergeChartConfig(chart.StackedBar.defaults, charJSPersonalDefaultOptionsStackedBar);
		var config = (options) ? mergeChartConfig(chart.StackedBar.defaults, options) : chart.StackedBar.defaults;
		return new StackedBar(data, config, context);
	};
	this.HorizontalStackedBar = function(data, options) {
		chart.HorizontalStackedBar.defaults = {
			annotateBarMinimumDetectionHeight : 0,
			highLightSet : { pointDotRadius: 15, barStrokeWidth : 5, pointDot : true },
			inGraphDataShow: false,
			inGraphDataPaddingX: -3,
			inGraphDataPaddingY: 0,
			inGraphDataTmpl: "<%=v3%>",
			inGraphDataAlign: "right",
			inGraphDataVAlign: "middle",
			inGraphDataRotate: 0,
			inGraphDataFontFamily: "'Arial'",
			inGraphDataFontSize: 12,
			inGraphDataFontStyle: "normal",
			inGraphDataFontColor: "#666",
			inGraphDataXPosition: 3,
			inGraphDataYPosition: 2,
			scaleOverlay: false,
			scaleOverride: false,
			scaleOverride2: false,
			scaleSteps: null,
			scaleStepWidth: null,
			scaleStartValue: null,
			scaleLineColor: "rgba(0,0,0,.1)",
			scaleLineStyle: "solid",
			scaleLineWidth: 1,
			scaleShowLabels: true,
			scaleShowLabels2: true,
			scaleLabel: "<%=value%>",
			scaleFontFamily: "'Arial'",
			scaleFontSize: 12,
			scaleFontStyle: "normal",
			scaleFontColor: "#666",
			scaleShowGridLines: true,
			scaleXGridLinesStep: 1,
			scaleYGridLinesStep: 1,
			scaleGridLineColor: "rgba(0,0,0,.05)",
			scaleGridLineStyle: "solid",
			scaleGridLineWidth: 1,
			scaleTickSizeLeft: 5,
			scaleTickSizeRight: 5,
			scaleTickSizeBottom: 5,
			scaleTickSizeTop: 5,
			showYAxisMin: true, // Show the minimum value on Y axis (in original version, this minimum is not displayed - it can overlap the X labels)
			rotateLabels: "smart", // smart <=> 0 degre if space enough; otherwise 45 degres if space enough otherwise90 degre; 
			barShowStroke: true,
//			barStrokeStyle: "solid",
			barStrokeWidth: 2,
			barValueSpacing: 5,
			barDatasetSpacing: 1,
			spaceBetweenBar : 0,
			animation: true,
			animationSteps: 60,
			animationEasing: "easeOutQuart",
			onAnimationComplete: null,
			annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3 + ' (' + v6 + ' %)'%>",
			reverseOrder: false
		};
		// merge annotate defaults
		if(isIE()<9 && isIE() != false)chart.HorizontalStackedBar.defaults = mergeChartConfig(chart.defaults.IExplorer8, chart.HorizontalStackedBar.defaults);
		chart.HorizontalStackedBar.defaults = mergeChartConfig(chart.defaults.commonOptions, chart.HorizontalStackedBar.defaults);
		chart.HorizontalStackedBar.defaults = mergeChartConfig(chart.defaults.xyAxisCommonOptions, chart.HorizontalStackedBar.defaults);
		chart.HorizontalStackedBar.defaults = mergeChartConfig(chart.HorizontalStackedBar.defaults, charJSPersonalDefaultOptions);
		chart.HorizontalStackedBar.defaults = mergeChartConfig(chart.HorizontalStackedBar.defaults, charJSPersonalDefaultOptionsHorizontalStackedBar);
		var config = (options) ? mergeChartConfig(chart.HorizontalStackedBar.defaults, options) : chart.HorizontalStackedBar.defaults;
		return new HorizontalStackedBar(data, config, context);
	};
	this.Bar = function(data, options) {
		chart.Bar.defaults = {
			annotateBarMinimumDetectionHeight : 0,
			highLightSet : { pointDotRadius: 15, barStrokeWidth : 5, pointDot : true },
			inGraphDataShow: false,
			inGraphDataPaddingX: 0,
			inGraphDataPaddingY: 3,
			inGraphDataTmpl: "<%=v3%>",
			inGraphDataAlign: "center",
			inGraphDataVAlign: "bottom",
			inGraphDataRotate: 0,
			inGraphDataFontFamily: "'Arial'",
			inGraphDataFontSize: 12,
			inGraphDataFontStyle: "normal",
			inGraphDataFontColor: "#666",
			inGraphDataXPosition: 2,
			inGraphDataYPosition: 3,
			scaleOverlay: false,
			scaleOverride: false,
			scaleOverride2: false,
			scaleSteps: null,
			scaleStepWidth: null,
			scaleStartValue: null,
			scaleSteps2: null,
			scaleStepWidth2: null,
			scaleStartValue2: null,
			scaleLineColor: "rgba(0,0,0,.1)",
			scaleLineStyle: "solid",
			scaleLineWidth: 1,
			scaleShowLabels: true,
			scaleShowLabels2: true,
			scaleLabel: "<%=value%>",
			scaleLabel2: "<%=value%>",
			scaleFontFamily: "'Arial'",
			scaleFontSize: 12,
			scaleFontStyle: "normal",
			scaleFontColor: "#666",
			scaleShowGridLines: true,
			scaleXGridLinesStep: 1,
			scaleYGridLinesStep: 1,
			scaleGridLineColor: "rgba(0,0,0,.05)",
			scaleGridLineWidth: 1,
			scaleGridLineStyle: "solid",
			showYAxisMin: true, // Show the minimum value on Y axis (in original version, this minimum is not displayed - it can overlap the X labels)
			rotateLabels: "smart", // smart <=> 0 degre if space enough; otherwise 45 degres if space enough otherwise90 degre; 
			// you can force an integer value between 0 and 180 degres
			logarithmic: false, // can be 'fuzzy',true and false ('fuzzy' => if the gap between min and maximum is big it's using a logarithmic y-Axis scale
			logarithmic2: false, // can be 'fuzzy',true and false ('fuzzy' => if the gap between min and maximum is big it's using a logarithmic y-Axis scale
			scaleTickSizeLeft: 5,
			scaleTickSizeRight: 5,
			scaleTickSizeBottom: 5,
			scaleTickSizeTop: 5,
			barShowStroke: true,
//			barStrokeStyle: "solid",
			barStrokeWidth: 2,
			barValueSpacing: 5,
			barDatasetSpacing: 1,
			barBorderRadius: 0,
			pointDot: true,
			pointDotRadius: 4,
			pointDotStrokeStyle: "solid",
			pointDotStrokeWidth: 2,
			extrapolateMissingData: true,
			animation: true,
			animationSteps: 60,
			animationEasing: "easeOutQuart",
			onAnimationComplete: null,
			bezierCurve: true,
			linkType : 0,   //0 : direct point to point; 1 = vertical lines; 2=angular link;
			bezierCurveTension : 0.4,
			annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3 + ' (' + v6 + ' %)'%>",
			pointHitDetectionRadius : 10
		};
		// merge annotate defaults
		if(isIE()<9 && isIE() != false)chart.Bar.defaults = mergeChartConfig(chart.defaults.IExplorer8, chart.Bar.defaults);
		chart.Bar.defaults = mergeChartConfig(chart.defaults.commonOptions, chart.Bar.defaults);
		chart.Bar.defaults = mergeChartConfig(chart.defaults.xyAxisCommonOptions, chart.Bar.defaults);
		chart.Bar.defaults = mergeChartConfig(chart.Bar.defaults, charJSPersonalDefaultOptions);
		chart.Bar.defaults = mergeChartConfig(chart.Bar.defaults, charJSPersonalDefaultOptionsBar);
		var config = (options) ? mergeChartConfig(chart.Bar.defaults, options) : chart.Bar.defaults;
		return new Bar(data, config, context);
	};
	this.HorizontalBar = function(data, options) {
		chart.HorizontalBar.defaults = {
			annotateBarMinimumDetectionHeight : 0,
			highLightSet : { pointDotRadius: 15, barStrokeWidth : 5, pointDot : true },
			inGraphDataShow: false,
			inGraphDataPaddingX: 3,
			inGraphDataPaddingY: 0,
			inGraphDataTmpl: "<%=v3%>",
			inGraphDataAlign: "left",
			inGraphDataVAlign: "middle",
			inGraphDataRotate: 0,
			inGraphDataFontFamily: "'Arial'",
			inGraphDataFontSize: 12,
			inGraphDataFontStyle: "normal",
			inGraphDataFontColor: "#666",
			inGraphDataXPosition: 3,
			inGraphDataYPosition: 2,
			scaleOverlay: false,
			scaleOverride: false,
			scaleOverride2: false,
			scaleSteps: null,
			scaleStepWidth: null,
			scaleStartValue: null,
			scaleLineColor: "rgba(0,0,0,.1)",
			scaleLineStyle: "solid",
			scaleLineWidth: 1,
			scaleShowLabels: true,
			scaleShowLabels2: true,
			scaleLabel: "<%=value%>",
			scaleFontFamily: "'Arial'",
			scaleFontSize: 12,
			scaleFontStyle: "normal",
			scaleFontColor: "#666",
			scaleShowGridLines: true,
			scaleXGridLinesStep: 1,
			scaleYGridLinesStep: 1,
			scaleGridLineColor: "rgba(0,0,0,.05)",
			scaleGridLineStyle: "solid",
			scaleGridLineWidth: 1,
			scaleTickSizeLeft: 5,
			scaleTickSizeRight: 5,
			scaleTickSizeBottom: 5,
			scaleTickSizeTop: 5,
			showYAxisMin: true, // Show the minimum value on Y axis (in original version, this minimum is not displayed - it can overlap the X labels)
			rotateLabels: "smart", // smart <=> 0 degre if space enough; otherwise 45 degres if space enough otherwise90 degre; 
			barShowStroke: true,
//			barStrokeStyle: "solid",
			barStrokeWidth: 2,
			barValueSpacing: 5,
			barDatasetSpacing: 1,
			barBorderRadius: 0,
			animation: true,
			animationSteps: 60,
			animationEasing: "easeOutQuart",
			onAnimationComplete: null,
			annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3 + ' (' + v6 + ' %)'%>",
			reverseOrder: false
		};
		// merge annotate defaults
		if(isIE()<9 && isIE() != false)chart.HorizontalBar.defaults = mergeChartConfig(chart.defaults.IExplorer8, chart.HorizontalBar.defaults);
		chart.HorizontalBar.defaults = mergeChartConfig(chart.defaults.commonOptions, chart.HorizontalBar.defaults);
		chart.HorizontalBar.defaults = mergeChartConfig(chart.defaults.xyAxisCommonOptions, chart.HorizontalBar.defaults);
		chart.HorizontalBar.defaults = mergeChartConfig(chart.HorizontalBar.defaults, charJSPersonalDefaultOptions);
		chart.HorizontalBar.defaults = mergeChartConfig(chart.HorizontalBar.defaults, charJSPersonalDefaultOptionsHorizontalBar);
		var config = (options) ? mergeChartConfig(chart.HorizontalBar.defaults, options) : chart.HorizontalBar.defaults;
		return new HorizontalBar(data, config, context);
	};
	chart.defaults = {};
	
	chart.defaults.IExplorer8 ={
		annotateBackgroundColor : "black",
		annotateFontColor: "white"
	};
	chart.defaults.commonOptions = {
		chartTextScale : 1,
		chartLineScale : 1,
		chartSpaceScale : 1,
		multiGraph: false,
		clearRect: true, // do not change clearRect options; for internal use only
		dynamicDisplay: false,
		dynamicDisplayXPartOfChart : 0.5,
		dynamicDisplayYPartOfChart : 0.5,
		animationForceSetTimeOut : false,
		graphSpaceBefore: 5,
		graphSpaceAfter: 5,
		canvasBorders: false,
		canvasBackgroundColor: "none",
		canvasBordersRadius : 0,
		canvasBordersWidth: 3,
		canvasBordersStyle: "solid",
		canvasBordersColor: "black",
		zeroValue : 0.0000000001,
		graphTitle: "",
		graphTitleFontFamily: "'Arial'",
		graphTitleFontSize: 24,
		graphTitleFontStyle: "bold",
		graphTitleFontColor: "#666",
		graphTitleSpaceBefore: 5,
		graphTitleSpaceAfter: 5,
		graphTitleBorders : false,
		graphTitleBordersRadius : 0,
		graphTitleBordersColor : "black",
		graphTitleBordersXSpace : 3,
		graphTitleBordersYSpace : 3,
		graphTitleBordersWidth : 1,
		graphTitleBordersStyle : "solid",
		graphTitleBackgroundColor : "none",
   		graphSubTitle: "",
		graphSubTitleFontFamily: "'Arial'",
		graphSubTitleFontSize: 18,
		graphSubTitleFontStyle: "normal",
		graphSubTitleFontColor: "#666",
		graphSubTitleSpaceBefore: 5,
		graphSubTitleSpaceAfter: 5,
		graphSubTitleBorders : false,
		graphSubTitleBordersColor : "black",
                graphSubTitleBordersRadius : 0,
		graphSubTitleBordersXSpace : 3,
		graphSubTitleBordersYSpace : 3,
		graphSubTitleBordersWidth : 1,
		graphSubTitleBordersStyle : "solid",
		graphSubTitleBackgroundColor : "none",
		footNote: "",
		footNoteFontFamily: "'Arial'",
		footNoteFontSize: 8,
		footNoteFontStyle: "bold",
		footNoteFontColor: "#666",
		footNoteSpaceBefore: 5,
		footNoteSpaceAfter: 5,
		footNoteBorders : false,
		footNoteBordersColor : "black",
                footNoteBordersRadius : 0,
		footNoteBordersXSpace : 3,
		footNoteBordersYSpace : 3,
		footNoteBordersWidth : 1,
		footNoteBordersStyle : "solid",
		footNoteBackgroundColor : "none",
		legend : false,
		showSingleLegend: false,
		maxLegendCols : 999,
		legendPosY :4,
		legendPosX : -2, 
		legendFontFamily: "'Arial'",
		legendFontSize: 12,
		legendFontStyle: "normal",
		legendFontColor: "#666",
		legendBlockSize: 15,
                legendBlockRadius:0,
		legendBorders: true,
		legendBordersStyle: "solid",
		legendBordersWidth: 1,
		legendBordersColors: "#666",
                legendBordersRadius: 0,
		legendBordersSpaceBefore: 5,
		legendBordersSpaceAfter: 5,
		legendBordersSpaceLeft: 5,
		legendBordersSpaceRight: 5,
		legendSpaceBeforeText: 5,
		legendSpaceAfterText: 5,
		legendSpaceLeftText: 5,
		legendSpaceRightText: 5,
		legendSpaceBetweenTextVertical: 5,
		legendSpaceBetweenTextHorizontal: 5,
		legendSpaceBetweenBoxAndText: 5,
		legendFillColor : "rgba(0,0,0,0)",
		legendXPadding : 0,
		legendYPadding : 0,
		inGraphDataBorders : false,
                inGraphDataBordersRadius : 0,
		inGraphDataBordersColor : "black",
		inGraphDataBordersXSpace : 3,
		inGraphDataBordersYSpace : 3,
		inGraphDataBordersWidth : 1,
		inGraphDataBordersStyle : "solid",
		inGraphDataBackgroundColor : "none",
		annotateDisplay: false,
		annotateRelocate: false,
		savePng: false,
		savePngOutput: "NewWindow", // Allowed values : "NewWindow", "CurrentWindow", "Save"
		savePngFunction: "mousedown right",
		savePngBackgroundColor: 'WHITE',
		annotateFunction: "mousemove",
		annotateFontFamily: "'Arial'",
		annotateBorder: 'none',
		annotateBorderRadius: '2px',
		annotateBackgroundColor: 'rgba(0,0,0,0.8)',
		annotateFontSize: 12,
		annotateFontColor: 'white',
		annotateFontStyle: "normal",
		annotatePadding: "3px",
		annotateClassName: "",
		annotateFunctionIn: null,
		annotateFunctionOut : null,
		detectMouseOnText: false,
		crossText: [""],
		crossTextIter: ["all"],
		crossTextOverlay: [true],
		crossTextFontFamily: ["'Arial'"],
		crossTextFontSize: [12],
		crossTextFontStyle: ["normal"],
		crossTextFontColor: ["rgba(220,220,220,1)"],
		crossTextRelativePosX: [2],
		crossTextRelativePosY: [2],
		crossTextBaseline: ["middle"],
		crossTextAlign: ["center"],
		crossTextPosX: [0],
		crossTextPosY: [0],
		crossTextAngle: [0],
		crossTextFunction: null,
		crossTextBorders : [false],
		crossTextBordersColor : ["black"],
                crossTextBordersRadius : [0],
		crossTextBordersXSpace : [3],
		crossTextBordersYSpace : [3],
		crossTextBordersWidth : [1],
		crossTextBordersStyle : ["solid"],
		crossTextBackgroundColor : ["none"],
		crossImage: [undefined],
		crossImageIter: ["all"],
		crossImageOverlay: [true],
		crossImageRelativePosX: [2],
		crossImageRelativePosY: [2],
		crossImageBaseline: ["middle"],
		crossImageAlign: ["center"],
		crossImagePosX: [0],
		crossImagePosY: [0],
		crossImageAngle: [0],
		spaceTop: 0,
		spaceBottom: 0,
		spaceRight: 0,
		spaceLeft: 0,
		decimalSeparator: ".",
		thousandSeparator: "",
		currency : "\u20AC",    // 20AC= Euro
		currencyPosition : "after",
		roundNumber: "none",
		roundPct: -1,
		templatesOpenTag : "<%=",
		templatesCloseTag : "%>",
		fmtV1: "none",
		fmtV2: "none",
		fmtV3: "none",
		fmtV4: "none",
		fmtV5: "none",
		fmtV6: "none",
		fmtV6T: "none",
		fmtV7: "none",
		fmtV8: "none",
		fmtV8T: "none",
		fmtV9: "none",
		fmtV10: "none",
		fmtV11: "none",
		fmtV12: "none",
		fmtV13: "none",
		fmtXLabel: "none",
		fmtYLabel: "none",
		fmtYLabel2: "none",
		fmtLegend: "none",
		animationStartValue: 0,
		animationStopValue: 1,
		animationCount: 1,
		animationPauseTime: 5,
		animationBackward: false,
		animationStartWithDataset: 1,
		animationStartWithData: 1,
		animationLeftToRight: false,
		animationByDataset: false,
		defaultStrokeColor: "rgba(220,220,220,1)",
		defaultFillColor: "rgba(220,220,220,0.5)",
		defaultLineWidth : 2,
		graphMaximized: false,
		contextMenu: true,
		mouseDownRight: null,
		mouseDownLeft: null,
		mouseDownMiddle: null,
		mouseMove: null,
		mouseOut: null,
		mouseDblClick: null,
		mouseWheel : null,
		highLight : false,
		highLightMouseFunction : "mousemove",
		highLightFullLine : false,   // true, false or "group"
		highLightRerunEndFunction : false,
		savePngName: "canvas",
		responsive : false,
		responsiveMinWidth : 0,
		responsiveMinHeight : 0,
		responsiveMaxWidth : 9999999,
		responsiveMaxHeight : 9999999,
		maintainAspectRatio: true,
		responsiveScaleContent : false,
		responsiveWindowInitialWidth : false,
		pointMarker : "circle",    // "circle","cross","plus","diamond","triangle","square"
		initFunction : null,
		beforeDrawFunction : null,
		endDrawDataFunction : null,
		endDrawScaleFunction : null
	};


	chart.defaults.PieAndDoughnut = {
			highLightSet : { expandOutRadius : 0.10, expandInRadius : -0.10 },
			inGraphDataShow: false,
			inGraphDataPaddingRadius: 5,
			inGraphDataPaddingAngle: 0,
			inGraphDataTmpl: "<%=(v1 == ''? '' : v1+':')+ v2 + ' (' + v6 + ' %)'%>",
			inGraphDataAlign: "off-center", // "right", "center", "left", "off-center" or "to-center"
			inGraphDataVAlign: "off-center", // "bottom", "middle", "top", "off-center" or "to-center"
			inGraphDataRotate: 0, // rotateAngle value (0->360) , "inRadiusAxis" or "inRadiusAxisRotateLabels"
			inGraphDataFontFamily: "'Arial'",
			inGraphDataFontSize: 12,
			inGraphDataFontStyle: "normal",
			inGraphDataFontColor: "#666",
			inGraphDataRadiusPosition: 3,
			inGraphDataAnglePosition: 2,
		        inGraphDataMinimumAngle : 0,
			segmentShowStroke: true,
			segmentStrokeColor: "#fff",
			segmentStrokeStyle: "solid",
			segmentStrokeWidth: 2,
			percentageInnerCutout: 50,
			animation: true,
			animationByData : false,
			animationSteps: 100,
			animationEasing: "easeOutBounce",
			animateRotate: true,
			animateScale: false,
			onAnimationComplete: null,
			annotateLabel: "<%=(v1 == ''? '' : v1+':')+ v2 + ' (' + v6 + ' %)'%>",
			startAngle: 90,
			totalAmplitude : 360,
			radiusScale: 1
	};

	chart.defaults.xyAxisCommonOptions = {
		maxBarWidth : -1,
		yAxisMinimumInterval: "none",
		yAxisMinimumInterval2: "none",
		yScaleLabelsMinimumWidth: 0,
		xScaleLabelsMinimumWidth: 0,
		yAxisLeft: true,
		yAxisRight: false,
		xAxisBottom: true,
		xAxisTop: false,
		xAxisSpaceBetweenLabels: 5,
		fullWidthGraph: false,
		yAxisLabel: "",
		yAxisLabel2: "",
		yAxisFontFamily: "'Arial'",
		yAxisFontSize: 16,
		yAxisFontStyle: "normal",
		yAxisFontColor: "#666",
		yAxisLabelSpaceRight: 5,
		yAxisLabelSpaceLeft: 5,
		yAxisSpaceRight: 5,
		yAxisSpaceLeft: 5,
		yAxisLabelBorders : false,
		yAxisLabelBordersColor : "black",
                yAxisLabelBordersRadius : 0,
		yAxisLabelBordersXSpace : 3,
		yAxisLabelBordersYSpace : 3,
		yAxisLabelBordersWidth : 1,
		yAxisLabelBordersStyle : "solid",
		yAxisLabelBackgroundColor : "none",
		xAxisLabel: "",
		xAxisFontFamily: "'Arial'",
		xAxisFontSize: 16,
		xAxisFontStyle: "normal",
		xAxisFontColor: "#666",
		xAxisLabelSpaceBefore: 5,
		xAxisLabelSpaceAfter: 5,
		xAxisSpaceBefore: 5,
		xAxisSpaceAfter: 5,
		xAxisLabelBorders : false,
		xAxisLabelBordersColor : "black",
                xAxisLabelBordersRadius : 0,
		xAxisLabelBordersXSpace : 3,
		xAxisLabelBordersYSpace : 3,
		xAxisLabelBordersWidth : 1,
		xAxisLabelBordersStyle : "solid",
		xAxisLabelBackgroundColor : "none",
		showXLabels : 1,
		firstLabelToShow : 1,
		showYLabels : 1,
		firstYLabelToShow : 1,
		yAxisUnit: "",
		yAxisUnit2: "",
		yAxisUnitFontFamily: "'Arial'",
		yAxisUnitFontSize: 8,
		yAxisUnitFontStyle: "normal",
		yAxisUnitFontColor: "#666",
		yAxisUnitSpaceBefore: 5,
		yAxisUnitSpaceAfter: 5,
		yAxisUnitBorders : false,
		yAxisUnitBordersColor : "black",
                yAxisUnitBordersradius : 0,
		yAxisUnitBordersXSpace : 3,
		yAxisUnitBordersYSpace : 3,
		yAxisUnitBordersWidth : 1,
		yAxisUnitBordersStyle : "solid",
		yAxisUnitBackgroundColor : "none"
	};
	var clear = function(c) {
		c.clearRect(0, 0, width, height);
	};
	


	function init_and_start(ctx,data,config) {

		var i;

		if (typeof ctx.initialWidth == "undefined") ctx.initialWidth =ctx.canvas.width;
		if (typeof ctx.chartTextScale == "undefined") ctx.chartTextScale=config.chartTextScale;
		if (typeof ctx.chartLineScale == "undefined") ctx.chartLineScale=config.chartLineScale;
		if (typeof ctx.chartSpaceScale == "undefined") 	ctx.chartSpaceScale=config.chartSpaceScale;
	
		if(typeof ctx.firstPass=="undefined"){
			ctx.firstPass=0;
        		if(config.responsive && !config.multiGraph) {
				addResponsiveChart(ctx.ChartNewId,ctx,data,config);
			}
		} 

		if (typeof ctx.ChartNewId == "undefined") {
			ctx.runanimationcompletefunction=true;
			var cvdate = new Date();
			var cvmillsec = cvdate.getTime();
			ctx.ChartNewId = ctx.tpchart + '_' + cvmillsec;
			ctx._eventListeners = {};
		}

		resizeCtx(ctx,config);

		if (!dynamicFunction(data, config, ctx)) return false;   // if config.dynamicDisplay=true, chart has to be displayed only if in current screen;  

		if (!config.multiGraph && ctx.firstPass!=0) {	
			clearAnnotate(ctx.ChartNewId);
		}

		if (typeof jsGraphAnnotate[ctx.ChartNewId] == "undefined") {
			jsGraphAnnotate[ctx.ChartNewId] = new Array();
			jsTextMousePos[ctx.ChartNewId] = new Array();
		};
		
		// convert label to title - for compatibility reasons with Chart.js;
		switch(ctx.tpdata) {
			case 1:
				for(i=0;i<data.length;i++){
					if(typeof data[i].title == "undefined" && typeof data[i].label != "undefined") {
						data[i].title=data[i].label;
					}
				}
				break;
			case 0:
			default:
				for(i=0;i<data.datasets.length;i++){
					if(typeof data.datasets[i].title == "undefined" && typeof data.datasets[i].label != "undefined") {
						data.datasets[i].title=data.datasets[i].label;
					}
				}
				break;
		}

		defMouse(ctx, data, config);
		
		if(ctx.firstPass==0) {
			if(config.animation)ctx.firstPass=1;
			else ctx.firstPass=2;
		}

		setRect(ctx, config);
		
		return true;
	} ;

	var PolarArea = function(data, config, ctx) {
		var maxSize, scaleHop, calculatedScale, labelHeight, scaleHeight, valueBounds, labelTemplateString, msr, midPosX, midPosY;

		ctx.tpchart="PolarArea";
		ctx.tpdata=1;
		
	        if (!init_and_start(ctx,data,config)) return;

		var realCumulativeAngle = (((config.startAngle * (Math.PI / 180) + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI) ; 
		var realAmplitude = (((config.totalAmplitude * (Math.PI / 180) + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI) ; 
		if(realAmplitude <= config.zeroValue)realAmplitude=2*Math.PI;
			
		var debAngle=((realCumulativeAngle-realAmplitude)+4*Math.PI)%(2*Math.PI);
		var finAngle=debAngle+realAmplitude;

		var statData=initPassVariableData_part1(data,config,ctx);

		valueBounds = getValueBounds();
		config.logarithmic = false;
		config.logarithmic2 = false;

		//Check and set the scale
		labelTemplateString = (config.scaleShowLabels) ? config.scaleLabel : "";
		if (!config.scaleOverride) {
			calculatedScale = calculateScale(1, config, valueBounds.maxSteps, valueBounds.minSteps, valueBounds.maxValue, valueBounds.minValue, labelTemplateString);
			msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, null, true, false, false, false, true, "PolarArea");
		} else {
			var scaleStartValue= setOptionValue(true,1,"SCALESTARTVALUE",ctx,data,statData,undefined,config.scaleStartValue,"scaleStartValue",-1,-1,{nullValue : true} );
			var scaleSteps =setOptionValue(true,1,"SCALESTEPS",ctx,data,statData,undefined,config.scaleSteps,"scaleSteps",-1,-1,{nullValue : true} );
			var scaleStepWidth = setOptionValue(true,1,"SCALESTEPWIDTH",ctx,data,statData,undefined,config.scaleStepWidth,"scaleStepWidth",-1,-1,{nullValue : true} );

			calculatedScale = {
				steps: scaleSteps,
				stepValue: scaleStepWidth,
				graphMin: scaleStartValue,
				graphMax: scaleStartValue + scaleSteps * scaleStepWidth,
				labels: []
			}
			populateLabels(1, config, labelTemplateString, calculatedScale.labels, calculatedScale.steps, scaleStartValue, calculatedScale.graphMax, scaleStepWidth);
			msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, null, true, false, false, false, true, "PolarArea");
		}

		var outerVal=calculatedScale.graphMin+calculatedScale.steps*calculatedScale.stepValue;
		var drwSize=calculatePieDrawingSize(ctx,msr,config,data,statData);
		midPosX=drwSize.midPieX;
		midPosY=drwSize.midPieY;

		scaleHop = Math.floor(drwSize.radius / calculatedScale.steps);
		//Wrap in an animation loop wrapper
 		if(scaleHop > 0) {
			initPassVariableData_part2(statData,data,config,ctx,{midPosX : midPosX,midPosY : midPosY,int_radius : 0,ext_radius : scaleHop*calculatedScale.steps, calculatedScale : calculatedScale, scaleHop : scaleHop,outerVal : outerVal});
			animationLoop(config, drawScale, drawAllSegments, ctx, msr.clrx, msr.clry, msr.clrwidth, msr.clrheight, midPosX, midPosY, midPosX - ((Min([msr.availableHeight, msr.availableWidth]) / 2) - 5), midPosY + ((Min([msr.availableHeight, msr.availableWidth]) / 2) - 5), data, statData);
		} else {
			testRedraw(ctx,data,config);
			ctx.firstPass=9;
		}

		function drawAllSegments(animationDecimal) {

			var prevAngle=statData[0].firstAngle;

			var fixAngle=0;
			var firstAngle=statData[0].firstAngle;
			if(1*config.animationStartWithData>1 && 1*config.animationStartWithData-1 < data.length) {
				fixAngle=(statData[config.animationStartWithData-1].startAngle-statData[0].firstAngle);
				firstAngle=statData[config.animationStartWithData-1].startAngle;
			}

			for (var i = 0; i < data.length; i++) {
				var	scaleAnimation = 1,
					rotateAnimation = 1;
                	
				if (config.animation) {
					if (config.animateScale) {
						scaleAnimation = animationDecimal;
					}
					if (config.animateRotate) {
						rotateAnimation = animationDecimal;
					}
				}
				correctedRotateAnimation = animationCorrection(rotateAnimation, data, config, i, -1,false).mainVal;
				if (!(typeof(data[i].value) == 'undefined')) {
					ctx.beginPath();
					if(config.animationByData == "ByArc") {
						endAngle=statData[i].startAngle+correctedRotateAnimation*statData[i].segmentAngle;
						ctx.arc(midPosX, midPosY, scaleAnimation * statData[i].radiusOffset, statData[i].startAngle, endAngle, false);
					} else if(config.animationByData) {
					        if (i<1*config.animationStartWithData-1) {
							ctx.arc(midPieX, midPieY, scaleAnimation * statData[i].radiusOffset, statData[i].startAngle, statData[i].endAngle,false);
						} else if(statData[i].startAngle <= firstAngle+correctedRotateAnimation*(2*Math.PI-fixAngle) ) {
							endAngle=statData[i].endAngle;
							if(statData[i].endAngle > firstAngle+correctedRotateAnimation*(2*Math.PI-fixAngle)) {
								endAngle=firstAngle+correctedRotateAnimation*(2*Math.PI-fixAngle);
							} 
							ctx.arc(midPieX, midPieY, scaleAnimation * statData[i].radiusOffset, statData[i].startAngle, endAngle,false);
						} else {
							continue; 
						}
					} else {
						ctx.arc(midPieX, midPieY, scaleAnimation * statData[i].radiusOffset, prevAngle, prevAngle+correctedRotateAnimation * (statData[i].endAngle-statData[i].startAngle),false);
						prevAngle=prevAngle+correctedRotateAnimation * (statData[i].endAngle-statData[i].startAngle);
					}
					ctx.lineTo(midPosX, midPosY);
					ctx.closePath();
					ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data[i].color,config.defaultFillColor,"color",i,-1,{animationDecimal: animationDecimal, scaleAnimation : scaleAnimation} );
					ctx.fill();

					if(config.segmentShowStroke=="merge") { /* avoid blank stripes between piece of chart */
						ctx.lineWidth =0;
						ctx.strokeStyle =setOptionValue(true,1,"COLOR",ctx,data,statData,data[i].color,config.defaultFillColor,"color",i,-1,{animationDecimal: animationDecimal, scaleAnimation : scaleAnimation} );
						ctx.setLineDash([]);
						ctx.stroke();
					}
					else if (config.segmentShowStroke) {
						ctx.strokeStyle = config.segmentStrokeColor;
						ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.segmentStrokeWidth);
						ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"SEGMENTSTROKESTYLE",ctx,data,statData,data[i].segmentStrokeStyle,config.segmentStrokeStyle,"segmentStrokeStyle",i,-1,{animationDecimal: animationDecimal, scaleAnimation : scaleAnimation} )));
						ctx.stroke();
						ctx.setLineDash([]);
					}
				}
			}


			if (animationDecimal >= config.animationStopValue) {
				for (i = 0; i < data.length; i++) {
					if (typeof(data[i].value) == 'undefined') continue;
					jsGraphAnnotate[ctx.ChartNewId][jsGraphAnnotate[ctx.ChartNewId].length] = ["ARC", i, -1,statData,setOptionValue(true,1,"ANNOTATEDISPLAY",ctx,data,statData,data[i].annotateDisplay,config.annotateDisplay,"annotateDisplay",i,-1,{nullValue : true})];
					if (setOptionValue(true,1,"INGRAPHDATASHOW",ctx,data,statData,data[i].inGraphDataShow,config.inGraphDataShow,"inGraphDataShow",i,-1,{nullValue : true})) {
						if (setOptionValue(true,1,"INGRAPHDATAANGLEPOSITION",ctx,data,statData,undefined,config.inGraphDataAnglePosition,"inGraphDataAnglePosition",i,-1,{nullValue : true} ) == 1) posAngle = statData[i].realStartAngle + setOptionValue(true,1,"INGRAPHDATAPADDINANGLE",ctx,data,statData,undefined,config.inGraphDataPaddingAngle,"inGraphDataPaddingAngle",i,-1,{nullValue: true  }) * (Math.PI / 180);
						else if (setOptionValue(true,1,"INGRAPHDATAANGLEPOSITION",ctx,data,statData,undefined,config.inGraphDataAnglePosition,"inGraphDataAnglePosition",i,-1,{nullValue : true} ) == 2) posAngle = (2*statData[i].realStartAngle - statData[i].segmentAngle) / 2 + setOptionValue(true,1,"INGRAPHDATAPADDINANGLE",ctx,data,statData,undefined,config.inGraphDataPaddingAngle,"inGraphDataPaddingAngle",i,-1,{nullValue: true  }) * (Math.PI / 180);
						else if (setOptionValue(true,1,"INGRAPHDATAANGLEPOSITION",ctx,data,statData,undefined,config.inGraphDataAnglePosition,"inGraphDataAnglePosition",i,-1,{nullValue : true} ) == 3) posAngle = statData[i].realStartAngle - statData[i].segmentAngle + setOptionValue(true,1,"INGRAPHDATAPADDINANGLE",ctx,data,statData,undefined,config.inGraphDataPaddingAngle,"inGraphDataPaddingAngle",i,-1,{nullValue: true  }) * (Math.PI / 180);
						if (setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",i,-1,{nullValue : true} ) == 1) labelRadius = 0 + setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,-1,{nullValue: true} );
						else if (setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",i,-1,{nullValue : true} ) == 2) labelRadius = statData[i].radiusOffset / 2 + setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,-1,{nullValue: true} );
						else if (setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",i,-1,{nullValue : true} ) == 3) labelRadius = statData[i].radiusOffset + setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,-1,{nullValue: true} );
						else if (setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",i,-1,{nullValue : true} ) == 4) labelRadius = scaleHop * calculatedScale.steps + setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,-1,{nullValue: true} );
						ctx.save()
						if (setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,-1,{nullValue: true  }) == "off-center") {
							if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,-1,{nullValue : true} ) == "inRadiusAxis" || (posAngle + 2 * Math.PI) % (2 * Math.PI) >= 3 * Math.PI / 2 || (posAngle + 2 * Math.PI) % (2 * Math.PI) <= Math.PI / 2) ctx.textAlign = "left";
					                                                                                                                                                                                                                                                   
							else ctx.textAlign = "right";
						} else if (setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,-1,{nullValue: true  }) == "to-center") {
							if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,-1,{nullValue : true} ) == "inRadiusAxis" || (posAngle + 2 * Math.PI) % (2 * Math.PI) >= 3 * Math.PI / 2 || (posAngle + 2 * Math.PI) % (2 * Math.PI) <= Math.PI / 2) ctx.textAlign = "right";
							else ctx.textAlign = "left";
						} else ctx.textAlign = setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,-1,{nullValue: true  });
						if (setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,-1,{nullValue : true} ) == "off-center") {
							if ((posAngle + 2 * Math.PI) % (2 * Math.PI) > Math.PI) ctx.textBaseline = "top";
							else ctx.textBaseline = "bottom";
						} else if (setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,-1,{nullValue : true} ) == "to-center") {
							if ((posAngle + 2 * Math.PI) % (2 * Math.PI) > Math.PI) ctx.textBaseline = "bottom";
							else ctx.textBaseline = "top";
						} else ctx.textBaseline = setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,-1,{nullValue : true} );
						ctx.font = setOptionValue(true,1,"INGRAPHDATAFONTSTYLE",ctx,data,statData,undefined,config.inGraphDataFontStyle,"inGraphDataFontStyle",i,-1,{nullValue : true} ) + ' ' + setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,-1,{nullValue : true} ) + 'px ' + setOptionValue(true,1,"INGRAPHDATAFONTFAMILY",ctx,data,statData,undefined,config.inGraphDataFontFamily,"inGraphDataFontFamily",i,-1,{nullValue : true} );
						ctx.fillStyle = setOptionValue(true,1,"INGRAPHDATAFONTCOLOR",ctx,data,statData,undefined,config.inGraphDataFontColor,"inGraphDataFontColor",i,-1,{nullValue : true} );
						var dispString = tmplbis(setOptionValue(true,1,"INGRAPHDATATMPL",ctx,data,statData,undefined,config.inGraphDataTmpl,"inGraphDataTmpl",i,-1,{nullValue : true} ), statData[i],config);
						ctx.translate(midPosX + labelRadius * Math.cos(posAngle), midPosY - labelRadius * Math.sin(posAngle));
						var rotateVal=0;
						if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,-1,{nullValue : true} ) == "inRadiusAxis") rotateVal=2 * Math.PI - posAngle;
						else if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,-1,{nullValue : true} ) == "inRadiusAxisRotateLabels") {
							if ((posAngle + 2 * Math.PI) % (2 * Math.PI) > Math.PI / 2 && (posAngle + 2 * Math.PI) % (2 * Math.PI) < 3 * Math.PI / 2) rotateVal=3 * Math.PI - posAngle;
							else rotateVal=2 * Math.PI - posAngle;
						} else rotateVal=setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,-1,{nullValue : true} ) * (Math.PI / 180);
						ctx.rotate(rotateVal);
						setTextBordersAndBackground(ctx,dispString,setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,-1,{nullValue : true} ),0,0,setOptionValue(true,1,"INGRAPHDATABORDERS",ctx,data,statData,undefined,config.inGraphDataBorders,"inGraphDataBorders",i,-1,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSCOLOR",ctx,data,statData,undefined,config.inGraphDataBordersColor,"inGraphDataBordersColor",i,-1,{nullValue : true} ),setOptionValue(true,ctx.chartLineScale,"INGRAPHDATABORDERSWIDTH",ctx,data,statData,undefined,config.inGraphDataBordersWidth,"inGraphDataBordersWidth",i,-1,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSXSPACE",ctx,data,statData,undefined,config.inGraphDataBordersXSpace,"inGraphDataBordersXSpace",i,-1,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSYSPACE",ctx,data,statData,undefined,config.inGraphDataBordersYSpace,"inGraphDataBordersYSpace",i,-1,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSSTYLE",ctx,data,statData,undefined,config.inGraphDataBordersStyle,"inGraphDataBordersStyle",i,-1,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABACKGROUNDCOLOR",ctx,data,statData,undefined,config.inGraphDataBackgroundColor,"inGraphDataBackgroundColor",i,-1,{nullValue : true} ),"INGRAPHDATA",config.inGraphDataBordersRadius);
						ctx.fillTextMultiLine(dispString, 0, 0, ctx.textBaseline, setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,-1,{nullValue : true} ), true,config.detectMouseOnText,ctx,"INGRAPHDATA_TEXTMOUSE",rotateVal,midPosX + labelRadius * Math.cos(posAngle), midPosY - labelRadius * Math.sin(posAngle),i,-1);
						ctx.restore();
					}
				}
			}
			if(msr.legendMsr.dispLegend)drawLegend(msr.legendMsr,data,config,ctx,"PolarArea");
		};

		function drawScale() {
			for (var i = 0; i < calculatedScale.steps; i++) {
				if (config.scaleShowLine && (i+1) % config.scaleGridLinesStep==0) {
					ctx.beginPath();
					ctx.arc(midPosX, midPosY, scaleHop * (i + 1), 4*Math.PI-debAngle, 4*Math.PI-finAngle, true);
					ctx.strokeStyle = config.scaleLineColor;
					ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
					ctx.setLineDash(lineStyleFn(config.scaleLineStyle));

					ctx.stroke();
					ctx.setLineDash([]);
				}
				if (config.scaleShowLabels) {

					if(Math.abs(config.totalAmplitude-360)<config.zeroValue)scaleAngle=Math.PI/2;
					else scaleAngle=(debAngle+finAngle)/2;

					ctx.textAlign = "center";
					ctx.font = config.scaleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.scaleFontSize)).toString() + "px " + config.scaleFontFamily;
					var label = calculatedScale.labels[i + 1];
					if (config.scaleShowLabelBackdrop) {
						var textWidth = ctx.measureTextMultiLine(label, Math.ceil(ctx.chartTextScale*config.scaleFontSize));
						ctx.fillStyle = config.scaleBackdropColor;
						ctx.beginPath();
						ctx.rect(
							Math.round(midPosX + Math.cos(scaleAngle)*(scaleHop*(i+1)) - textWidth / 2 - Math.ceil(ctx.chartSpaceScale*config.scaleBackdropPaddingX)), //X
							Math.round(midPosY - Math.sin(scaleAngle)*(scaleHop * (i + 1)) - (Math.ceil(ctx.chartTextScale*config.scaleFontSize)) * 0.5 - Math.ceil(ctx.chartSpaceScale*config.scaleBackdropPaddingY)), //Y
							Math.round(textWidth + (Math.ceil(ctx.chartSpaceScale*config.scaleBackdropPaddingX) * 2)), //Width
							Math.round((Math.ceil(ctx.chartTextScale*config.scaleFontSize)) + (Math.ceil(ctx.chartSpaceScale*config.scaleBackdropPaddingY) * 2)) //Height
						);
						ctx.fill();
					}
					ctx.textBaseline = "middle";
					ctx.fillStyle = config.scaleFontColor;
					ctx.fillTextMultiLine(label, midPosX + Math.cos(scaleAngle)*(scaleHop*(i+1)) , midPosY - Math.sin(scaleAngle)*(scaleHop * (i + 1)), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"SCALE_TEXTMOUSE",0,0,0,i,-1);
				}
			}
		};

		function getValueBounds() {
			var upperValue = -Number.MAX_VALUE;
			var lowerValue = Number.MAX_VALUE;
			for (var i = 0; i < data.length; i++) {
				if(typeof data[i].value == "undefined") continue;
				if (1 * data[i].value > upperValue) {
					upperValue = 1 * data[i].value;
				}
				if (1 * data[i].value < lowerValue) {
					lowerValue = 1 * data[i].value;
				}
			};
			if(upperValue<lowerValue){upperValue=0;lowerValue=0;}
			if (Math.abs(upperValue - lowerValue) < config.zeroValue) {
				if(Math.abs(upperValue)< config.zeroValue) upperValue = .9;
				if(upperValue>0) {
					upperValue=upperValue*1.1;
					lowerValue=lowerValue*0.9;
				} else {
					upperValue=upperValue*0.9;
					lowerValue=lowerValue*1.1;
				}
			}
			if(typeof config.graphMin=="function") lowerValue= setOptionValue(true,1,"GRAPHMIN",ctx,data,statData,undefined,config.graphMin,"graphMin",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMin)) lowerValue = config.graphMin;
			if(typeof config.graphMax=="function") upperValue= setOptionValue(true,1,"GRAPHMAX",ctx,data,statData,undefined,config.graphMax,"graphMax",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMax)) upperValue = config.graphMax;
			var maxSteps = Math.floor((scaleHeight / (labelHeight * 0.66)));
			var minSteps = Math.floor((scaleHeight / labelHeight * 0.5));
			if(upperValue<lowerValue){lowerValue=upperValue-1;}

			return {
				maxValue: upperValue,
				minValue: lowerValue,
				maxSteps: maxSteps,
				minSteps: minSteps
			};
		};

		return {
			data:data,
			config:config,
			ctx:ctx
		};
	};
	var Radar = function(data, config, ctx) {
		var maxSize, scaleHop, calculatedScale, labelHeight, scaleHeight, valueBounds, labelTemplateString, msr, midPosX, midPosY;

		ctx.tpchart="Radar";
		ctx.tpdata=0;

	        if (!init_and_start(ctx,data,config)) return;
		var statData=initPassVariableData_part1(data,config,ctx);
		valueBounds = getValueBounds();

		config.logarithmic = false;
		config.logarithmic2 = false;
		//If no labels are defined set to an empty array, so referencing length for looping doesn't blow up.
		if (!data.labels) data.labels = [];
		//Check and set the scale
		labelTemplateString = (config.scaleShowLabels) ? config.scaleLabel : "";
		if (!config.scaleOverride) {
			calculatedScale = calculateScale(1, config, valueBounds.maxSteps, valueBounds.minSteps, valueBounds.maxValue, valueBounds.minValue, labelTemplateString);
			msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, null, true, false, false, true, config.datasetFill, "Radar");
		} else {
			var scaleStartValue= setOptionValue(true,1,"SCALESTARTVALUE",ctx,data,statData,undefined,config.scaleStartValue,"scaleStartValue",-1,-1,{nullValue : true} );
			var scaleSteps =setOptionValue(true,1,"SCALESTEPS",ctx,data,statData,undefined,config.scaleSteps,"scaleSteps",-1,-1,{nullValue : true} );
			var scaleStepWidth = setOptionValue(true,1,"SCALESTEPWIDTH",ctx,data,statData,undefined,config.scaleStepWidth,"scaleStepWidth",-1,-1,{nullValue : true} );
			calculatedScale = {
				steps: scaleSteps,
				stepValue: scaleStepWidth,
				graphMin: scaleStartValue,
				graphMax: scaleStartValue + scaleSteps * scaleStepWidth,
				labels: []
			}
			populateLabels(1, config, labelTemplateString, calculatedScale.labels, calculatedScale.steps, scaleStartValue, calculatedScale.graphMax, scaleStepWidth);
			msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, null, true, false, false, true, config.datasetFill, "Radar");
		}

		calculateDrawingSizes();
		midPosY = msr.topNotUsableSize + (msr.availableHeight / 2);
		scaleHop = maxSize / (calculatedScale.steps);
		//Wrap in an animation loop wrapper
		initPassVariableData_part2(statData,data,config,ctx,{midPosX : midPosX, midPosY : midPosY, calculatedScale: calculatedScale, scaleHop: scaleHop, maxSize:maxSize,outerVal : -1});
		animationLoop(config, drawScale, drawAllDataPoints, ctx, msr.clrx, msr.clry, msr.clrwidth, msr.clrheight, midPosX, midPosY, midPosX - maxSize, midPosY + maxSize, data, statData);
		//Radar specific functions.
		function drawAllDataPoints(animationDecimal) {
			var rotationDegree = (2 * Math.PI) / data.datasets[0].data.length;
			ctx.save();
			//We accept multiple data sets for radar charts, so show loop through each set
			for (var i = 0; i < data.datasets.length; i++) {
				var fPt = -1;
				for (var j = 0; j < data.datasets[i].data.length; j++) {
					var currentAnimPc = animationCorrection(animationDecimal, data, config, i, j, false).animVal;
					if (currentAnimPc > 1) currentAnimPc = currentAnimPc - 1;
					if (!(typeof(data.datasets[i].data[j]) == 'undefined')) {
						if (fPt == -1) {
							ctx.beginPath();
							ctx.moveTo(midPosX + currentAnimPc * statData[i][j].offsetX, midPosY - currentAnimPc * statData[i][j].offsetY);
							fPt = j;
						} else {
							ctx.lineTo(midPosX + currentAnimPc * statData[i][j].offsetX, midPosY - currentAnimPc * statData[i][j].offsetY);
						}
					}
				}
				ctx.closePath();
				if (config.datasetFill) {
					ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data.datasets[i].fillColor,config.defaultFillColor,"fillColor",i,-1,{animationValue : currentAnimPc, midPosX : statData[i][0].midPosX, midPosY : statData[i][0].midPosY, ext_radius : (config.animationLeftToRight ? 1 : currentAnimPc) * (statData[i][0].calculated_offset_max)} );
				} else ctx.fillStyle = "rgba(0,0,0,0)";

				ctx.strokeStyle=setOptionValue(true,1,"STROKECOLOR",ctx,data,statData,data.datasets[i].strokeColor,config.defaultStrokeColor,"strokeColor",i,-1,{nullvalue : null} );
				ctx.lineWidth = Math.ceil(ctx.chartLineScale*setOptionValue(true,1,"LINEWIDTH",ctx,data,statData,data.datasets[i].datasetStrokeWidth,config.datasetStrokeWidth,"datasetStrokeWidth",i,-1,{nullvalue : null} ));
				ctx.fill();
				ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"LINEDASH",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
				ctx.stroke();
				ctx.setLineDash([]);
				if (animationDecimal >= config.animationStopValue) {
					ctx.beginPath();

					for (var k = 0; k < data.datasets[i].data.length; k++) {
						if (!(typeof(data.datasets[i].data[k]) == 'undefined')) {
							if(setOptionValue(true,1,"POINTDOT",ctx,data,statData,undefined,config.pointDot,"pointDot",i,k,{nullvalue : null} )) {
								ctx.beginPath();
								ctx.fillStyle=setOptionValue(true,1,"MARKERFILLCOLOR",ctx,data,statData,data.datasets[i].pointColor,config.defaultStrokeColor,"pointColor",i,k,{nullvalue: true} );
								ctx.strokeStyle=setOptionValue(true,1,"MARKERSTROKESTYLE",ctx,data,statData,data.datasets[i].pointStrokeColor,config.defaultStrokeColor,"pointStrokeColor",i,k,{nullvalue: true} );
								ctx.lineWidth=setOptionValue(true,ctx.chartLineScale,"MARKERLINEWIDTH",ctx,data,statData,data.datasets[i].pointDotStrokeWidth,config.pointDotStrokeWidth,"pointDotStrokeWidth",i,k,{nullvalue: true} );
								var markerShape=setOptionValue(true,1,"MARKERSHAPE",ctx,data,statData,data.datasets[i].markerShape,config.markerShape,"markerShape",i,k,{nullvalue: true} );
								var markerRadius=setOptionValue(true,ctx.chartSpaceScale,"MARKERRADIUS",ctx,data,statData,data.datasets[i].pointDotRadius,config.pointDotRadius,"pointDotRadius",i,k,{nullvalue: true} );
								var markerStrokeStyle=setOptionValue(true,1,"MARKERSTROKESTYLE",ctx,data,statData,data.datasets[i].pointDotStrokeStyle,config.pointDotStrokeStyle,"pointDotStrokeStyle",i,k,{nullvalue: true} );
								drawMarker(ctx,midPosX + currentAnimPc * statData[i][k].offsetX, midPosY - currentAnimPc * statData[i][k].offsetY, markerShape,markerRadius,markerStrokeStyle);							
							}
						}
					}
				}
			}
			ctx.restore();
			if (animationDecimal >= config.animationStopValue) {
				for (i = 0; i < data.datasets.length; i++) {
					for (j = 0; j < data.datasets[i].data.length; j++) {
						if (typeof(data.datasets[i].data[j]) == 'undefined') continue;
						jsGraphAnnotate[ctx.ChartNewId][jsGraphAnnotate[ctx.ChartNewId].length] = ["POINT", i,j,statData,setOptionValue(true,1,"ANNOTATEDISPLAY",ctx,data,statData,data.datasets[i].annotateDisplay,config.annotateDisplay,"annotateDisplay",i,j,{nullValue : true})];
						if(setOptionValue(true,1,"INGRAPHDATASHOW",ctx,data,statData,data.datasets[i].inGraphDataShow,config.inGraphDataShow,"inGraphDataShow",i,j,{nullValue : true})) {
							ctx.save();
							ctx.beginPath();
							ctx.textAlign = setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,j,{nullValue: true  });
							ctx.textBaseline = setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,j,{nullValue : true} );
							if (setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,j,{nullValue: true  }) == "off-center") {
								if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,j,{nullValue : true} ) == "inRadiusAxis" || (config.startAngle * Math.PI / 180 - j * rotationDegree + 4 * Math.PI) % (2 * Math.PI) > 3 * Math.PI / 2 || (config.startAngle * Math.PI / 180 - j * rotationDegree + 4 * Math.PI) % (2 * Math.PI) <= Math.PI / 2) ctx.textAlign = "left";
								else ctx.textAlign = "right";
							} else if (setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,j,{nullValue: true  }) == "to-center") {
								if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,j,{nullValue : true} ) == "inRadiusAxis" || (config.startAngle * Math.PI / 180 - j * rotationDegree + 4 * Math.PI) % (2 * Math.PI) > 3 * Math.PI / 2 || (config.startAngle * Math.PI / 180 - j * rotationDegree + 4 * Math.PI) % (2 * Math.PI) < Math.PI / 2) ctx.textAlign = "right";
								else ctx.textAlign = "left";
							} else ctx.textAlign = setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,j,{nullValue: true  });
							if (setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,j,{nullValue : true} ) == "off-center") {
								if ((config.startAngle * Math.PI / 180 - j * rotationDegree + 4 * Math.PI) % (2 * Math.PI) > Math.PI) ctx.textBaseline = "bottom";
								else ctx.textBaseline = "top";
							} else if (setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,j,{nullValue : true} ) == "to-center") {
								if ((config.startAngle * Math.PI / 180 - j * rotationDegree + 4 * Math.PI) % (2 * Math.PI) > Math.PI) ctx.textBaseline = "top";
								else ctx.textBaseline = "bottom";
							} else ctx.textBaseline = setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,j,{nullValue : true} );
							ctx.font = setOptionValue(true,1,"INGRAPHDATAFONTSTYLE",ctx,data,statData,undefined,config.inGraphDataFontStyle,"inGraphDataFontStyle",i,j,{nullValue : true} ) + ' ' + setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ) + 'px ' + setOptionValue(true,1,"INGRAPHDATAFONTFAMILY",ctx,data,statData,undefined,config.inGraphDataFontFamily,"inGraphDataFontFamily",i,j,{nullValue : true} );
							ctx.fillStyle = setOptionValue(true,1,"INGRAPHDATAFONTCOLOR",ctx,data,statData,undefined,config.inGraphDataFontColor,"inGraphDataFontColor",i,j,{nullValue : true} );
							var radiusPrt;
							if (setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",i,j,{nullValue : true} ) == 1) radiusPrt = 0 + setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,j,{nullValue: true} );
							else if (setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",i,j,{nullValue : true} ) == 2) radiusPrt = (statData[i][j].calculated_offset) / 2 + setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,j,{nullValue: true} );
							else if (setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",i,j,{nullValue : true} ) == 3) radiusPrt = (statData[i][j].calculated_offset) + setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,j,{nullValue: true} );
							var x_pos,y_pos;
							if(statData[i][j].calculated_offset>0) {
							        x_pos=midPosX + statData[i][j].offsetX * (radiusPrt/statData[i][j].calculated_offset);
							        y_pos=midPosY - statData[i][j].offsetY * (radiusPrt/statData[i][j].calculated_offset);
//								ctx.translate(midPosX + statData[i][j].offsetX * (radiusPrt/statData[i][j].calculated_offset), midPosY - statData[i][j].offsetY * (radiusPrt/statData[i][j].calculated_offset));
							} else {
								x_pos=midPosX;
								y_pos=midPosY;
//								ctx.translate(midPosX, midPosY);
							}
							ctx.translate(x_pos,y_pos);
							var rotateVal=0;
							if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,j,{nullValue : true} ) == "inRadiusAxis") rotateVal= j * rotationDegree;
							else if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,j,{nullValue : true} ) == "inRadiusAxisRotateLabels") {
								if ((j * rotationDegree + 2 * Math.PI) % (2 * Math.PI) > Math.PI / 2 && (j * rotationDegree + 2 * Math.PI) % (2 * Math.PI) < 3 * Math.PI / 2) rotateVal= 3 * Math.PI + j * rotationDegree;
								else rotateVal = 2 * Math.PI + j * rotationDegree;
							} else rotateVal=setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,j,{nullValue : true} ) * (Math.PI / 180);
							ctx.rotate(rotateVal);
							var dispString = tmplbis(setOptionValue(true,1,"INGRAPHDATATMPL",ctx,data,statData,undefined,config.inGraphDataTmpl,"inGraphDataTmpl",i,j,{nullValue : true} ), statData[i][j],config);
							setTextBordersAndBackground(ctx,dispString,setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),0,0,setOptionValue(true,1,"INGRAPHDATABORDERS",ctx,data,statData,undefined,config.inGraphDataBorders,"inGraphDataBorders",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSCOLOR",ctx,data,statData,undefined,config.inGraphDataBordersColor,"inGraphDataBordersColor",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartLineScale,"INGRAPHDATABORDERSWIDTH",ctx,data,statData,undefined,config.inGraphDataBordersWidth,"inGraphDataBordersWidth",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSXSPACE",ctx,data,statData,undefined,config.inGraphDataBordersXSpace,"inGraphDataBordersXSpace",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSYSPACE",ctx,data,statData,undefined,config.inGraphDataBordersYSpace,"inGraphDataBordersYSpace",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSSTYLE",ctx,data,statData,undefined,config.inGraphDataBordersStyle,"inGraphDataBordersStyle",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABACKGROUNDCOLOR",ctx,data,statData,undefined,config.inGraphDataBackgroundColor,"inGraphDataBackgroundColor",i,j,{nullValue : true} ),"INGRAPHDATA",config.inGraphDataBordersRadius);
							ctx.fillTextMultiLine(dispString, 0, 0, ctx.textBaseline, setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),true,config.detectMouseOnText,ctx,"INGRAPHDATA_TEXTMOUSE",rotateVal,x_pos,y_pos,i,j);
							ctx.restore();
						}
					}
				}
			}
			if(msr.legendMsr.dispLegend)drawLegend(msr.legendMsr,data,config,ctx,"Radar");
		};

		function drawScale() {
			var rotationDegree = (2 * Math.PI) / data.datasets[0].data.length;
			ctx.save();
			ctx.translate(midPosX, midPosY);
			ctx.rotate((90 - config.startAngle) * Math.PI / 180);
			if (config.angleShowLineOut) {
				ctx.strokeStyle = config.angleLineColor;
				ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.angleLineWidth);
				for (var h = 0; h < data.datasets[0].data.length; h++) {
					ctx.rotate(rotationDegree);
					ctx.beginPath();
					ctx.moveTo(0, 0);
					ctx.lineTo(0, -maxSize);
//					ctx.setLineDash(lineStyleFn(config.angleLineStyle));
					ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"ANGLELINESTYLE",ctx,data,statData,undefined,config.angleLineStyle,"angleLineStyle",h,-1,{nullValue : true} )));
					ctx.stroke();
					ctx.setLineDash([]);
				}
			}
			for (var i = 0; i < calculatedScale.steps; i++) {
				ctx.beginPath();
				if (config.scaleShowLine && (i+1) % config.scaleGridLinesStep == 0 ) {
					ctx.strokeStyle = config.scaleLineColor;
					ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
					ctx.moveTo(0, -scaleHop * (i + 1));
					for (var j = 0; j < data.datasets[0].data.length; j++) {
						ctx.rotate(rotationDegree);
						ctx.lineTo(0, -scaleHop * (i + 1));
					}
					ctx.closePath();
					ctx.setLineDash(lineStyleFn(config.scaleLineStyle));
					ctx.stroke();
					ctx.setLineDash([]);
				}
			}
			ctx.rotate(-(90 - config.startAngle) * Math.PI / 180);
			if (config.scaleShowLabels) {
				for (i = 0; i < calculatedScale.steps; i++) {
					ctx.textAlign = 'center';
					ctx.font = config.scaleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.scaleFontSize)).toString() + "px " + config.scaleFontFamily;
					ctx.textBaseline = "middle";
					if (config.scaleShowLabelBackdrop) {
						var textWidth = ctx.measureTextMultiLine(calculatedScale.labels[i + 1], (Math.ceil(ctx.chartTextScale*config.scaleFontSize))).textWidth;
						ctx.fillStyle = config.scaleBackdropColor;
						ctx.beginPath();
						ctx.rect(
							Math.round(Math.cos(config.startAngle * Math.PI / 180) * (scaleHop * (i + 1)) - textWidth / 2 - Math.ceil(ctx.chartSpaceScale*config.scaleBackdropPaddingX)), //X
							Math.round((-Math.sin(config.startAngle * Math.PI / 180) * scaleHop * (i + 1)) - (Math.ceil(ctx.chartTextScale*config.scaleFontSize)) * 0.5 - Math.ceil(ctx.chartSpaceScale*config.scaleBackdropPaddingY)), //Y
							Math.round(textWidth + (Math.ceil(ctx.chartSpaceScale*config.scaleBackdropPaddingX) * 2)), //Width
							Math.round((Math.ceil(ctx.chartTextScale*config.scaleFontSize)) + (Math.ceil(ctx.chartSpaceScale*config.scaleBackdropPaddingY) * 2)) //Height
						);
						ctx.fill();
					}
					ctx.fillStyle = config.scaleFontColor;
					ctx.fillTextMultiLine(calculatedScale.labels[i + 1], Math.cos(config.startAngle * Math.PI / 180) * (scaleHop * (i + 1)), -Math.sin(config.startAngle * Math.PI / 180) * scaleHop * (i + 1), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"SCALE_TEXTMOUSE",0,midPosX, midPosY,i,-1);
				}
			}
			for (var k = 0; k < data.labels.length; k++) {
				ctx.font = config.pointLabelFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.pointLabelFontSize)).toString() + "px " + config.pointLabelFontFamily;
				ctx.fillStyle = config.pointLabelFontColor;
				var opposite = Math.sin((90 - config.startAngle) * Math.PI / 180 + rotationDegree * k) * (maxSize + (Math.ceil(ctx.chartTextScale*config.pointLabelFontSize)));
				var adjacent = Math.cos((90 - config.startAngle) * Math.PI / 180 + rotationDegree * k) * (maxSize + (Math.ceil(ctx.chartTextScale*config.pointLabelFontSize)));
				var vangle = (90 - config.startAngle) * Math.PI / 180 + rotationDegree * k;
				while (vangle < 0) vangle = vangle + 2 * Math.PI;
				while (vangle > 2 * Math.PI) vangle = vangle - 2 * Math.PI;
				if (vangle == Math.PI || vangle == 0) {
					ctx.textAlign = "center";
				} else if (vangle > Math.PI) {
					ctx.textAlign = "right";
				} else {
					ctx.textAlign = "left";
				}
				ctx.textBaseline = "middle";
				ctx.fillTextMultiLine(data.labels[k], opposite, -adjacent, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.pointLabelFontSize)),true,config.detectMouseOnText,ctx,"LABEL_TEXTMOUSE",0,midPosX, midPosY,k,-1);
			}
			ctx.restore();
		};

		function calculateDrawingSizes() {
			var midX, mxlb, maxL, maxR, iter, nbiter, prevMaxSize, prevMidX,i,textMeasurement;
			var rotationDegree = (2 * Math.PI) / data.datasets[0].data.length;
			var rotateAngle = config.startAngle * Math.PI / 180;
			// Compute range for Mid Point of graph
			ctx.font = config.pointLabelFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.pointLabelFontSize)).toString() + "px " + config.pointLabelFontFamily;
			if (!config.graphMaximized) {
				maxR = msr.availableWidth / 2;
				maxL = msr.availableWidth / 2;
				nbiter = 1;
			} else {
				maxR = msr.availableWidth / 2;
				maxL = msr.availableWidth / 2;
				nbiter = 40;
				for (i = 0; i < data.labels.length; i++) {
					textMeasurement = ctx.measureTextMultiLine(data.labels[i], (Math.ceil(ctx.chartTextScale*config.scaleFontSize))).textWidth + ctx.measureTextMultiLine(data.labels[i], (Math.ceil(ctx.chartTextScale*config.scaleFontSize))).textHeight;
					mxlb = (msr.availableWidth - textMeasurement) / (1 + Math.abs(Math.cos(rotateAngle)));
					if ((rotateAngle < Math.PI / 2 && rotateAngle > -Math.PI / 2) || rotateAngle > 3 * Math.PI / 2) {
						if (mxlb < maxR) maxR = mxlb;
					} else if (Math.cos(rotateAngle) != 0) {
						if (mxlb < maxL) maxL = mxlb;
					}
					rotateAngle -= rotationDegree;
				}
			}
			// compute max Radius and midPoint in that range
			prevMaxSize = 0;
			prevMidX = 0;
			midPosX = maxR + msr.leftNotUsableSize;
			for (midX = maxR, iter = 0; iter < nbiter; ++iter, midX += (msr.availableWidth - maxL - maxR) / nbiter) {
				maxSize = Max([midX, msr.availableWidth - midX]);
				rotateAngle = config.startAngle * Math.PI / 180;
				mxlb = msr.available;
				for (i = 0; i < data.labels.length; i++) {
					textMeasurement = ctx.measureTextMultiLine(data.labels[i], (Math.ceil(ctx.chartTextScale*config.scaleFontSize))).textWidth + ctx.measureTextMultiLine(data.labels[i], (Math.ceil(ctx.chartTextScale*config.scaleFontSize))).textHeight;
					if ((rotateAngle < Math.PI / 2 && rotateAngle > -Math.PI / 2) || rotateAngle > 3 * Math.PI / 2) {
						mxlb = ((msr.availableWidth - midX) - textMeasurement) / Math.abs(Math.cos(rotateAngle));
					} else if (Math.cos(rotateAngle != 0)) {
						mxlb = (midX - textMeasurement) / Math.abs(Math.cos(rotateAngle));
					}
					if (mxlb < maxSize) maxSize = mxlb;
					if (Math.sin(rotateAngle) * msr.availableHeight / 2 > msr.availableHeight / 2 - (Math.ceil(ctx.chartTextScale*config.scaleFontSize)) * 2) {
						mxlb = Math.sin(rotateAngle) * msr.availableHeight / 2 - 1.5 * (Math.ceil(ctx.chartTextScale*config.scaleFontSize));
						if (mxlb < maxSize) maxSize = mxlb;
					}
					rotateAngle -= rotationDegree;
				}
				if (maxSize > prevMaxSize) {
					prevMaxSize = maxSize;
					midPosX = midX + msr.leftNotUsableSize;    
				}
			}
			maxSize = prevMaxSize - (Math.ceil(ctx.chartTextScale*config.scaleFontSize)) / 2;
			//If the label height is less than 5, set it to 5 so we don't have lines on top of each other.
			labelHeight = Default(labelHeight, 5);
		};

		function getValueBounds() {
			var upperValue = -Number.MAX_VALUE;
			var lowerValue = Number.MAX_VALUE;
			for (var i = 0; i < data.datasets.length; i++) {
				for (var j = 0; j < data.datasets[i].data.length; j++) {
					if(typeof data.datasets[i].data[j]=="undefined")continue;
					if (1 * data.datasets[i].data[j] > upperValue) {
						upperValue = 1 * data.datasets[i].data[j]
					}
					if (1 * data.datasets[i].data[j] < lowerValue) {
						lowerValue = 1 * data.datasets[i].data[j]
					}
				}
			}
			if(upperValue<lowerValue){upperValue=0;lowerValue=0;}
			if (Math.abs(upperValue - lowerValue) < config.zeroValue) {
				if(Math.abs(upperValue)< config.zeroValue){ upperValue = .9;lowerValue=-.9;}
				if(upperValue>0) {
					upperValue=upperValue*1.1;
					lowerValue=lowerValue*0.9;
				} else {
					upperValue=upperValue*0.9;
					lowerValue=lowerValue*1.1;
				}
			}
			if(typeof config.graphMin=="function") lowerValue= setOptionValue(true,1,"GRAPHMIN",ctx,data,statData,undefined,config.graphMin,"graphMin",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMin)) lowerValue = config.graphMin;
			if(typeof config.graphMax=="function") upperValue= setOptionValue(true,1,"GRAPHMAX",ctx,data,statData,undefined,config.graphMax,"graphMax",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMax)) upperValue = config.graphMax;
			var maxSteps = Math.floor((scaleHeight / (labelHeight * 0.66)));
			var minSteps = Math.floor((scaleHeight / labelHeight * 0.5));
			if(upperValue<lowerValue){lowerValue=upperValue-1;}
			return {
				maxValue: upperValue,
				minValue: lowerValue,
				maxSteps: maxSteps,
				minSteps: minSteps
			};
		};

		return {
			data:data,
			config:config,
			ctx:ctx
		};
	};


	var Pie = function(data, config, ctx) {
		var msr, midPieX, midPieY, pieRadius;
		ctx.tpchart="Pie";
		return(Doughnut(data,config,ctx));
	};
	
	var Doughnut = function(data, config, ctx) {
		var msr, midPieX, midPieY, doughnutRadius;

		if(typeof ctx.tpchart == "undefined")ctx.tpchart="Doughnut";
		ctx.tpdata=1;


	        if (!init_and_start(ctx,data,config)) return;

		var statData=initPassVariableData_part1(data,config,ctx);

		var realCumulativeAngle = (((config.startAngle * (Math.PI / 180) + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI) ; 
		config.logarithmic = false;
		config.logarithmic2 = false;
		msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, "none", null, true, false, false, false, true, "Doughnut");

		var drwSize=calculatePieDrawingSize(ctx,msr,config,data,statData);

		midPieX=drwSize.midPieX;
		midPieY=drwSize.midPieY;
		doughnutRadius=drwSize.radius;
		
		var cutoutRadius;
                if(ctx.tpchart == "Pie")cutoutRadius=0;
		else cutoutRadius = doughnutRadius * (config.percentageInnerCutout / 100);
		if(doughnutRadius > 0) {
			initPassVariableData_part2(statData,data,config,ctx,{midPosX : midPieX,midPosY : midPieY ,int_radius : cutoutRadius ,ext_radius : doughnutRadius,outerVal : -1});
			animationLoop(config, null, drawPieSegments, ctx, msr.clrx, msr.clry, msr.clrwidth, msr.clrheight, midPieX, midPieY, midPieX - doughnutRadius, midPieY + doughnutRadius, data, statData);
		} else {
			testRedraw(ctx,data,config);
			ctx.firstPass=9;
		}


		function drawPieSegments(animationDecimal) {
			var cumulativeAngle = (((-config.startAngle * (Math.PI / 180) + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI) ; 

			var dataCutoutRadius, dataDoughnutRadius;

			var prevAngle=statData[0].firstAngle;

			var fixAngle=0;
			var firstAngle=statData[0].firstAngle;
			if(1*config.animationStartWithData>1 && 1*config.animationStartWithData-1 < data.length) {
				fixAngle=(statData[config.animationStartWithData-1].startAngle-statData[0].firstAngle);
				firstAngle=statData[config.animationStartWithData-1].startAngle;
			}
			
						
			for (var i = 0; i < data.length; i++) {
				var	scaleAnimation = 1,
					rotateAnimation = 1;
				if (ctx.tpchart=="Pie")dataCutoutRadius=cutoutRadius;
				else dataCutoutRadius=cutoutRadius-(doughnutRadius-cutoutRadius)*setOptionValue(true,1,"EXPANDINRADIUS",ctx,data,statData,data[i].expandInRadius,0,"expandInRadius",i,-1,{animationDecimal: animationDecimal, scaleAnimation : scaleAnimation} );
                	        dataDoughnutRadius=doughnutRadius+(doughnutRadius-cutoutRadius)*setOptionValue(true,1,"EXPANDOUTRADIUS",ctx,data,statData,data[i].expandOutRadius,0,"expandOutRadius",i,-1,{animationDecimal: animationDecimal, scaleAnimation : scaleAnimation} );
				if (config.animation) {
					if (config.animateScale) {
						scaleAnimation = animationDecimal;
					}
					if (config.animateRotate) {
						rotateAnimation = animationDecimal;
					}
				}

				correctedRotateAnimation = animationCorrection(rotateAnimation, data, config, i, -1, false).mainVal;
				if (!(typeof(data[i].value) == 'undefined') && 1*data[i].value >=0) {
					ctx.beginPath();
					ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.segmentStrokeWidth);
					ctx.strokeStyle = "rgba(0,0,0,0)";
					if (config.animationByData == "ByArc") {
						endAngle=statData[i].startAngle+correctedRotateAnimation*statData[i].segmentAngle;
						ctx.arc(midPieX, midPieY, scaleAnimation * dataDoughnutRadius, statData[i].startAngle, endAngle,false);
						ctx.arc(midPieX, midPieY, scaleAnimation * dataCutoutRadius, endAngle,statData[i].startAngle, true);
					} else if(config.animationByData) {
					        if (i<1*config.animationStartWithData-1) {
							ctx.arc(midPieX, midPieY, scaleAnimation * dataDoughnutRadius, statData[i].startAngle, statData[i].endAngle,false);
							ctx.arc(midPieX, midPieY, scaleAnimation * dataCutoutRadius, statData[i].endAngle,statData[i].startAngle, true);
						} else if(statData[i].startAngle <= firstAngle+correctedRotateAnimation*(2*Math.PI-fixAngle) ) {
							endAngle=statData[i].endAngle;
							if(statData[i].endAngle > firstAngle+correctedRotateAnimation*(2*Math.PI-fixAngle)) {
								endAngle=firstAngle+correctedRotateAnimation*(2*Math.PI-fixAngle);
							} 
							ctx.arc(midPieX, midPieY, scaleAnimation * dataDoughnutRadius, statData[i].startAngle, endAngle,false);
							ctx.arc(midPieX, midPieY, scaleAnimation * dataCutoutRadius, endAngle,statData[i].startAngle, true);
														
						} else {
							continue; 
						}
					} else {
						ctx.arc(midPieX, midPieY, scaleAnimation * dataDoughnutRadius, prevAngle, prevAngle+correctedRotateAnimation * (statData[i].endAngle-statData[i].startAngle),false);
						ctx.arc(midPieX, midPieY, scaleAnimation * dataCutoutRadius, prevAngle+correctedRotateAnimation * (statData[i].endAngle-statData[i].startAngle), prevAngle, true);
						prevAngle=prevAngle+correctedRotateAnimation * (statData[i].endAngle-statData[i].startAngle);
					}
					ctx.closePath();  
					ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data[i].color,config.defaultFillColor,"color",i,-1,{animationDecimal: animationDecimal, scaleAnimation : scaleAnimation} );
					ctx.fill();
					if(config.segmentShowStroke=="merge") { /* avoid blank stripes between piece of chart */
						ctx.lineWidth =1.5;
						ctx.strokeStyle =setOptionValue(true,1,"COLOR",ctx,data,statData,data[i].color,config.defaultFillColor,"color",i,-1,{animationDecimal: animationDecimal, scaleAnimation : scaleAnimation} );
						ctx.setLineDash([]);
						ctx.stroke();
					}
					else if (config.segmentShowStroke) {
						ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.segmentStrokeWidth);
						ctx.strokeStyle = config.segmentStrokeColor;
						ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"SEGMENTSTROKESTYLE",ctx,data,statData,data[i].segmentStrokeStyle,config.segmentStrokeStyle,"segmentStrokeStyle",i,-1,{animationDecimal: animationDecimal, scaleAnimation : scaleAnimation} )));
						ctx.stroke();					 
						ctx.setLineDash([]);
					}
				}
			}
			if (animationDecimal >= config.animationStopValue) {
				for (i = 0; i < data.length; i++) {
					if (ctx.tpchart=="Pie")dataCutOutRadius=cutoutRadius;
					else dataCutoutRadius=cutoutRadius-(doughnutRadius-cutoutRadius)*setOptionValue(true,1,"EXPANDINRADIUS",ctx,data,statData,data[i].expandInRadius,0,"expandInRadius",i,-1,{animationDecimal: animationDecimal, scaleAnimation : scaleAnimation} );
        	        	        dataDoughnutRadius=doughnutRadius+(doughnutRadius-cutoutRadius)*setOptionValue(true,1,"EXPANDOUTRADIUS",ctx,data,statData,data[i].expandOutRadius,0,"expandOutRadius",i,-1,{animationDecimal: animationDecimal, scaleAnimation : scaleAnimation} );
					if (typeof(data[i].value) == 'undefined' || 1*data[i].value<0) continue;
					jsGraphAnnotate[ctx.ChartNewId][jsGraphAnnotate[ctx.ChartNewId].length] = ["ARC", i,-1,statData,setOptionValue(true,1,"ANNOTATEDISPLAY",ctx,data,statData,data[i].annotateDisplay,config.annotateDisplay,"annotateDisplay",i,-1,{nullValue : true})];
					if (setOptionValue(true,1,"INGRAPHDATASHOW",ctx,data,statData,data[i].inGraphDataShow,config.inGraphDataShow,"inGraphDataShow",i,-1,{nullValue : true}) && statData[i].segmentAngle >= (Math.PI/180) * setOptionValue(true,1,"INGRAPHDATAMINIMUMANGLE",ctx,data,statData,undefined,config.inGraphDataMinimumAngle,"inGraphDataMinimumAngle",i,-1,{nullValue : true} )) {
						if (setOptionValue(true,1,"INGRAPHDATAANGLEPOSITION",ctx,data,statData,undefined,config.inGraphDataAnglePosition,"inGraphDataAnglePosition",i,-1,{nullValue : true} ) == 1) posAngle = statData[i].realStartAngle + setOptionValue(true,1,"INGRAPHDATAPADDINANGLE",ctx,data,statData,undefined,config.inGraphDataPaddingAngle,"inGraphDataPaddingAngle",i,-1,{nullValue: true  }) * (Math.PI / 180);
						else if (setOptionValue(true,1,"INGRAPHDATAANGLEPOSITION",ctx,data,statData,undefined,config.inGraphDataAnglePosition,"inGraphDataAnglePosition",i,-1,{nullValue : true} ) == 2) posAngle = statData[i].realStartAngle- statData[i].segmentAngle / 2 + setOptionValue(true,1,"INGRAPHDATAPADDINANGLE",ctx,data,statData,undefined,config.inGraphDataPaddingAngle,"inGraphDataPaddingAngle",i,-1,{nullValue: true  }) * (Math.PI / 180);
						else if (setOptionValue(true,1,"INGRAPHDATAANGLEPOSITION",ctx,data,statData,undefined,config.inGraphDataAnglePosition,"inGraphDataAnglePosition",i,-1,{nullValue : true} ) == 3) posAngle = statData[i].realStartAngle - statData[i].segmentAngle + setOptionValue(true,1,"INGRAPHDATAPADDINANGLE",ctx,data,statData,undefined,config.inGraphDataPaddingAngle,"inGraphDataPaddingAngle",i,-1,{nullValue: true  }) * (Math.PI / 180);
						if (setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",i,-1,{nullValue : true} ) == 1) labelRadius = dataCutoutRadius + setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,-1,{nullValue: true} );
						else if (setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",i,-1,{nullValue : true} ) == 2) labelRadius = dataCutoutRadius + (dataDoughnutRadius - dataCutoutRadius) / 2 + setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,-1,{nullValue: true} );
						else if (setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",i,-1,{nullValue : true} ) == 3) labelRadius = dataDoughnutRadius + setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,-1,{nullValue: true} );
						ctx.save();
						if (setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,-1,{nullValue: true  }) == "off-center") {
							if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,-1,{nullValue : true} ) == "inRadiusAxis" || (posAngle + 2 * Math.PI) % (2 * Math.PI) >= 3 * Math.PI / 2 || (posAngle + 2 * Math.PI) % (2 * Math.PI) <= Math.PI / 2) ctx.textAlign = "left";
							else ctx.textAlign = "right";
						} else if (setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,-1,{nullValue: true  }) == "to-center") {
							if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,-1,{nullValue : true} ) == "inRadiusAxis" || (posAngle + 2 * Math.PI) % (2 * Math.PI) >= 3 * Math.PI / 2 || (posAngle + 2 * Math.PI) % (2 * Math.PI) <= Math.PI / 2) ctx.textAlign = "right";
							else ctx.textAlign = "left";
						} else ctx.textAlign = setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,-1,{nullValue: true  });
						if (setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,-1,{nullValue : true} ) == "off-center") {
							if ((posAngle + 2 * Math.PI) % (2 * Math.PI) > Math.PI) ctx.textBaseline = "top";
							else ctx.textBaseline = "bottom";
						} else if (setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,-1,{nullValue : true} ) == "to-center") {
							if ((posAngle + 2 * Math.PI) % (2 * Math.PI) > Math.PI) ctx.textBaseline = "bottom";
							else ctx.textBaseline = "top";
						} else ctx.textBaseline = setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,-1,{nullValue : true} );
						ctx.font = setOptionValue(true,1,"INGRAPHDATAFONTSTYLE",ctx,data,statData,undefined,config.inGraphDataFontStyle,"inGraphDataFontStyle",i,-1,{nullValue : true} ) + ' ' + setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,-1,{nullValue : true} ) + 'px ' + setOptionValue(true,1,"INGRAPHDATAFONTFAMILY",ctx,data,statData,undefined,config.inGraphDataFontFamily,"inGraphDataFontFamily",i,-1,{nullValue : true} );
						ctx.fillStyle = setOptionValue(true,1,"INGRAPHDATAFONTCOLOR",ctx,data,statData,undefined,config.inGraphDataFontColor,"inGraphDataFontColor",i,-1,{nullValue : true} );
						var dispString = tmplbis(setOptionValue(true,1,"INGRAPHDATATMPL",ctx,data,statData,undefined,config.inGraphDataTmpl,"inGraphDataTmpl",i,-1,{nullValue : true} ), statData[i],config);
						ctx.translate(midPieX + labelRadius * Math.cos(posAngle), midPieY - labelRadius * Math.sin(posAngle));
						var rotateVal=0;
						if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,-1,{nullValue : true} ) == "inRadiusAxis") rotateVal=2 * Math.PI - posAngle;
						else if (setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,-1,{nullValue : true} ) == "inRadiusAxisRotateLabels") {
							if ((posAngle + 2 * Math.PI) % (2 * Math.PI) > Math.PI / 2 && (posAngle + 2 * Math.PI) % (2 * Math.PI) < 3 * Math.PI / 2) rotateVal=3 * Math.PI - posAngle;
							else rotateVal=2 * Math.PI - posAngle;
						} else rotateVal=setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,-1,{nullValue : true} ) * (Math.PI / 180);
						ctx.rotate(rotateVal);
						setTextBordersAndBackground(ctx,dispString,setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,-1,{nullValue : true} ),0,0,setOptionValue(true,1,"INGRAPHDATABORDERS",ctx,data,statData,undefined,config.inGraphDataBorders,"inGraphDataBorders",i,-1,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSCOLOR",ctx,data,statData,undefined,config.inGraphDataBordersColor,"inGraphDataBordersColor",i,-1,{nullValue : true} ),setOptionValue(true,ctx.chartLineScale,"INGRAPHDATABORDERSWIDTH",ctx,data,statData,undefined,config.inGraphDataBordersWidth,"inGraphDataBordersWidth",i,-1,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSXSPACE",ctx,data,statData,undefined,config.inGraphDataBordersXSpace,"inGraphDataBordersXSpace",i,-1,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSYSPACE",ctx,data,statData,undefined,config.inGraphDataBordersYSpace,"inGraphDataBordersYSpace",i,-1,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSSTYLE",ctx,data,statData,undefined,config.inGraphDataBordersStyle,"inGraphDataBordersStyle",i,-1,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABACKGROUNDCOLOR",ctx,data,statData,undefined,config.inGraphDataBackgroundColor,"inGraphDataBackgroundColor",i,-1,{nullValue : true} ),"INGRAPHDATA",config.inGraphDataBordersRadius);
						ctx.fillTextMultiLine(dispString, 0, 0, ctx.textBaseline, setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,-1,{nullValue : true} ),true,config.detectMouseOnText,ctx,"INGRAPHDATA_TEXTMOUSE",rotateVal,midPieX + labelRadius * Math.cos(posAngle), midPieY - labelRadius * Math.sin(posAngle),i,-1);
						ctx.restore();
					}
				}
			}
			if(msr.legendMsr.dispLegend)drawLegend(msr.legendMsr,data,config,ctx,"Doughnut");
		};
		
		return {
			data:data,
			config:config,
			ctx:ctx
		};
	};
	var Line = function(data, config, ctx) {
		var maxSize, scaleHop, scaleHop2, calculatedScale, calculatedScale2, labelHeight, scaleHeight, valueBounds, labelTemplateString, labelTemplateString2;
		var valueHop, widestXLabel, xAxisLength, yAxisPosX, xAxisPosY, rotateLabels = 0,
			msr;
		var zeroY = 0;
		var zeroY2 = 0;
		ctx.tpchart="Line";
		ctx.tpdata=0;

	        if (!init_and_start(ctx,data,config)) return;
		// adapt data when length is 1;
		var mxlgt = 0;
		for (var i = 0; i < data.datasets.length; i++) {mxlgt = Max([mxlgt, data.datasets[i].data.length]);}
		if (mxlgt == 1) {
			if (typeof(data.labels[0]) == "string") data.labels = ["", data.labels[0], ""];
			for (i = 0; i < data.datasets.length; i++) {
				if (typeof(data.datasets[i].data[0] != "undefined")) data.datasets[i].data = [undefined, data.datasets[i].data[0], undefined];
			}
			mxlgt=3;
		}
		var statData=initPassVariableData_part1(data,config,ctx);
		for (i = 0; i < data.datasets.length; i++) statData[i][0].tpchart="Line";
		msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, "nihil", [""], false, false, true, true, config.datasetFill, "Line");
		valueBounds = getValueBounds();
		// true or fuzzy (error for negativ values (included 0))
		if (config.logarithmic !== false) {
			if (valueBounds.minValue <= 0) {
				config.logarithmic = false;
			}
		}
		if (config.logarithmic2 !== false) {
			if (valueBounds.minValue2 <= 0) {
				config.logarithmic2 = false;
			}
		}
		// Check if logarithmic is meanigful
		var OrderOfMagnitude = calculateOrderOfMagnitude(Math.pow(10, calculateOrderOfMagnitude(valueBounds.maxValue) + 1)) - calculateOrderOfMagnitude(Math.pow(10, calculateOrderOfMagnitude(valueBounds.minValue)));
		if ((config.logarithmic == 'fuzzy' && OrderOfMagnitude < 4) || config.scaleOverride) {
			config.logarithmic = false;
		}
		// Check if logarithmic is meanigful
		var OrderOfMagnitude2 = calculateOrderOfMagnitude(Math.pow(10, calculateOrderOfMagnitude(valueBounds.maxValue2) + 1)) - calculateOrderOfMagnitude(Math.pow(10, calculateOrderOfMagnitude(valueBounds.minValue2)));
		if ((config.logarithmic2 == 'fuzzy' && OrderOfMagnitude2 < 4) || config.scaleOverride2) {
			config.logarithmic2 = false;
		}
		//Check and set the scale
		labelTemplateString = (config.scaleShowLabels) ? config.scaleLabel : "";
		labelTemplateString2 = (config.scaleShowLabels2) ? config.scaleLabel2 : "";
		if (!config.scaleOverride) {
			if(valueBounds.maxSteps>0 && valueBounds.minSteps>0) {
				calculatedScale = calculateScale(1, config, valueBounds.maxSteps, valueBounds.minSteps, valueBounds.maxValue, valueBounds.minValue, labelTemplateString);
			}
		} else {
			var scaleStartValue= setOptionValue(true,1,"SCALESTARTVALUE",ctx,data,statData,undefined,config.scaleStartValue,"scaleStartValue",-1,-1,{nullValue : true} );
			var scaleSteps =setOptionValue(true,1,"SCALESTEPS",ctx,data,statData,undefined,config.scaleSteps,"scaleSteps",-1,-1,{nullValue : true} );
			var scaleStepWidth = setOptionValue(true,1,"SCALESTEPWIDTH",ctx,data,statData,undefined,config.scaleStepWidth,"scaleStepWidth",-1,-1,{nullValue : true} );
			calculatedScale = {
				steps: scaleSteps,
				stepValue: scaleStepWidth,
				graphMin: scaleStartValue,
				graphMax: scaleStartValue + scaleSteps * scaleStepWidth,
				labels: []
			}
			populateLabels(1, config, labelTemplateString, calculatedScale.labels, calculatedScale.steps, scaleStartValue, calculatedScale.graphMax, scaleStepWidth);
		}

		if (valueBounds.dbAxis) {
			if (!config.scaleOverride2) {
				if(valueBounds.maxSteps>0 && valueBounds.minSteps>0) {
					calculatedScale2 = calculateScale(2, config, valueBounds.maxSteps, valueBounds.minSteps, valueBounds.maxValue2, valueBounds.minValue2, labelTemplateString2);
				}
			} else {
				var scaleStartValue2= setOptionValue(true,1,"SCALESTARTVALUE2",ctx,data,statData,undefined,config.scaleStartValue2,"scaleStartValue2",-1,-1,{nullValue : true} );
				var scaleSteps2 =setOptionValue(true,1,"SCALESTEPS2",ctx,data,statData,undefined,config.scaleSteps2,"scaleSteps2",-1,-1,{nullValue : true} );
				var scaleStepWidth2 = setOptionValue(true,1,"SCALESTEPWIDTH2",ctx,data,statData,undefined,config.scaleStepWidth2,"scaleStepWidth2",-1,-1,{nullValue : true} );

				calculatedScale2 = {
					steps: scaleSteps2,
					stepValue: scaleStepWidth2,
					graphMin: scaleStartValue2,
					graphMax: scaleStartValue2 + scaleSteps2 * scaleStepWidth2,
					labels: []
				}
				populateLabels(2, config, labelTemplateString2, calculatedScale2.labels, calculatedScale2.steps, scaleStartValue2, calculatedScale2.graphMax, scaleStepWidth2);
			}
		} else {
			calculatedScale2 = {
				steps: 0,
				stepValue: 0,
				graphMin: 0,
				graphMax: 0,
				labels: null
			}
		}
		if(valueBounds.maxSteps>0 && valueBounds.minSteps>0) {
			msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, calculatedScale2.labels, false, false, true, true, config.datasetFill, "Line");
			var prevHeight=msr.availableHeight;
			msr.availableHeight = msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
			msr.availableWidth = msr.availableWidth - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft) - Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight);
			scaleHop = Math.floor(msr.availableHeight / calculatedScale.steps);
			scaleHop2 = Math.floor(msr.availableHeight / calculatedScale2.steps);
			valueHop = Math.floor(msr.availableWidth / (data.labels.length - 1));
			if (valueHop == 0 || config.fullWidthGraph) valueHop = (msr.availableWidth / (data.labels.length - 1));
			msr.clrwidth = msr.clrwidth - (msr.availableWidth - (data.labels.length - 1) * valueHop);
			msr.availableWidth = (data.labels.length - 1) * valueHop;
			msr.availableHeight = (calculatedScale.steps) * scaleHop;
			msr.xLabelPos+=(Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop) - (prevHeight-msr.availableHeight));
			msr.clrheight+=(Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop) - (prevHeight-msr.availableHeight));
  			yAxisPosX = msr.leftNotUsableSize + Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft);
			xAxisPosY = msr.topNotUsableSize + msr.availableHeight + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
			drawLabels();

			zeroY = calculateOffset(config.logarithmic, 0, calculatedScale, scaleHop);
			if(typeof calculatedScale2 ==="object")zeroY2 = calculateOffset(config.logarithmic2, 0, calculatedScale2, scaleHop2);
			initPassVariableData_part2(statData,data,config,ctx,{
				xAxisPosY : xAxisPosY,
				yAxisPosX : yAxisPosX,
				valueHop : valueHop,
				nbValueHop : data.labels.length - 1,
				scaleHop : scaleHop,
				zeroY : zeroY,
				calculatedScale : calculatedScale,
				logarithmic  : config.logarithmic,
				scaleHop2: scaleHop2,
				zeroY2: zeroY2,
				msr : msr,
				calculatedScale2: calculatedScale2,
				logarithmic2: config.logarithmic2} );
			animationLoop(config, drawScale, drawLines, ctx, msr.clrx, msr.clry, msr.clrwidth, msr.clrheight, yAxisPosX + msr.availableWidth / 2, xAxisPosY - msr.availableHeight / 2, yAxisPosX, xAxisPosY, data, statData);
		} else {
			testRedraw(ctx,data,config);
			ctx.firstPass=9;
		}


		function drawLines(animPc) {
		
			drawLinesDataset(animPc, data, config, ctx, statData,{xAxisPosY : xAxisPosY,yAxisPosX : yAxisPosX, valueHop : valueHop, nbValueHop : data.labels.length - 1 });
			if (animPc >= 1) {
				if (typeof drawMath == "function") {
					drawMath(ctx, config, data, msr, {
						xAxisPosY: xAxisPosY,
						yAxisPosX: yAxisPosX,
						valueHop: valueHop,
						scaleHop: scaleHop,
						zeroY: zeroY,
						calculatedScale: calculatedScale,
						calculateOffset: calculateOffset,
						statData : statData

					});
				}
			}
			if(msr.legendMsr.dispLegend)drawLegend(msr.legendMsr,data,config,ctx,"Line");
		};

		function drawScale() {
			//X axis line
			// if the xScale should be drawn
			if (config.drawXScaleLine !== false) {
				for (var s = 0; s < config.drawXScaleLine.length; s++) {
					// get special lineWidth and lineColor for this xScaleLine
					ctx.lineWidth = config.drawXScaleLine[s].lineWidth ? config.drawXScaleLine[s].lineWidth : Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
					ctx.strokeStyle = config.drawXScaleLine[s].lineColor ? config.drawXScaleLine[s].lineColor : config.scaleLineColor;
					ctx.beginPath();
					var yPosXScale;
					switch (config.drawXScaleLine[s].position) {
						case "bottom":
							yPosXScale = xAxisPosY;
							break;
						case "top":
							yPosXScale = xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
							break;
						case "0":
						case 0:
							// check if zero exists
							if (zeroY != 0) yPosXScale = xAxisPosY - zeroY;
							break;
					}
					// draw the scale line
					ctx.moveTo(yAxisPosX - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft), yPosXScale);
					ctx.lineTo(yAxisPosX + msr.availableWidth + Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight), yPosXScale);
					ctx.setLineDash(lineStyleFn(config.scaleLineStyle));
					ctx.stroke();
					ctx.setLineDash([]);
 
				}
			}
			for (var i = 0; i < data.labels.length; i++) {
				ctx.beginPath();
				ctx.moveTo(yAxisPosX + i * valueHop, xAxisPosY + Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom));
				ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth);
				ctx.strokeStyle = config.scaleGridLineColor;
				//Check i isnt 0, so we dont go over the Y axis twice.
				if (config.scaleShowGridLines && i > 0 && i % config.scaleXGridLinesStep == 0) {
					ctx.lineTo(yAxisPosX + i * valueHop, xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop));
				} else {
					ctx.lineTo(yAxisPosX + i * valueHop, xAxisPosY);
				}
				ctx.setLineDash(lineStyleFn(config.scaleGridLineStyle));
				ctx.stroke();
            			ctx.setLineDash([]);
            			
			}
			//Y axis
			ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
			ctx.strokeStyle = config.scaleLineColor;
			ctx.beginPath();
			ctx.moveTo(yAxisPosX, xAxisPosY + Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom));
			ctx.lineTo(yAxisPosX, xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop));
			ctx.setLineDash(lineStyleFn(config.scaleLineStyle));
			ctx.stroke();
			ctx.setLineDash([]);
			for (var j = 0; j < calculatedScale.steps; j++) {
				ctx.beginPath();
				ctx.moveTo(yAxisPosX - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft), xAxisPosY - ((j + 1) * scaleHop));
				ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth);
				ctx.strokeStyle = config.scaleGridLineColor;
				if (config.scaleShowGridLines && (j+1) % config.scaleYGridLinesStep == 0) {
					ctx.lineTo(yAxisPosX + msr.availableWidth + Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight), xAxisPosY - ((j + 1) * scaleHop));
				} else {
					ctx.lineTo(yAxisPosX, xAxisPosY - ((j + 1) * scaleHop));
				}
				ctx.setLineDash(lineStyleFn(config.scaleGridLineStyle));
				ctx.stroke();
            			ctx.setLineDash([]);
			}
		};

		function drawLabels() {
			ctx.font = config.scaleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.scaleFontSize)).toString() + "px " + config.scaleFontFamily;
			//X Labels     
			if (config.xAxisTop || config.xAxisBottom) {
				ctx.textBaseline = "top";
				if (msr.rotateLabels > 90) {
					ctx.save();
					ctx.textAlign = "left";
				} else if (msr.rotateLabels > 0) {
					ctx.save();
					ctx.textAlign = "right";
				} else {
					ctx.textAlign = "center";
				}
				ctx.fillStyle = config.scaleFontColor;
				if (config.xAxisBottom) {
					for (var i = 0; i < data.labels.length; i++) {
						if(showLabels(ctx,data,config,i)){
							ctx.save();
							if (msr.rotateLabels > 0) {
								ctx.translate(yAxisPosX + i * valueHop - msr.highestXLabel / 2, msr.xLabelPos);
								ctx.rotate(-(msr.rotateLabels * (Math.PI / 180)));
								ctx.fillTextMultiLine(fmtChartJS(config, data.labels[i], config.fmtXLabel), 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"XSCALE_TEXTMOUSE",-(msr.rotateLabels * (Math.PI / 180)),yAxisPosX + i * valueHop - msr.highestXLabel / 2, msr.xLabelPos,i,-1);
							} else {
								ctx.fillTextMultiLine(fmtChartJS(config, data.labels[i], config.fmtXLabel), yAxisPosX + i * valueHop, msr.xLabelPos, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"XSCALE_TEXTMOUSE",0,0,0,i,-1);
							}
							ctx.restore();
						}
					}
				}
			}
			//Y Labels
			ctx.textAlign = "right";
			ctx.textBaseline = "middle";
			for (var j = ((config.showYAxisMin) ? -1 : 0); j < calculatedScale.steps; j++) {
				if (config.scaleShowLabels) {
					if(showYLabels(ctx,data,config,j+1,calculatedScale.labels[j + 1])) {				
						if (config.yAxisLeft) {
							ctx.textAlign = "right";
							ctx.fillTextMultiLine(calculatedScale.labels[j + 1], yAxisPosX - (Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - ((j + 1) * scaleHop), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YLEFTAXIS_TEXTMOUSE",0,0,0,-1,j);
						}
						if (config.yAxisRight && !valueBounds.dbAxis) {
							ctx.textAlign = "left";
							ctx.fillTextMultiLine(calculatedScale.labels[j + 1], yAxisPosX + msr.availableWidth + (Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - ((j + 1) * scaleHop), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YRIGHTAXIS_TEXTMOUSE",0,0,0,-1,j);
						}
					}
				}
			}
			if (config.yAxisRight && valueBounds.dbAxis) {
				for (j = ((config.showYAxisMin) ? -1 : 0); j < calculatedScale2.steps; j++) {
					if (config.scaleShowLabels) {
						ctx.textAlign = "left";
						ctx.fillTextMultiLine(calculatedScale2.labels[j + 1], yAxisPosX + msr.availableWidth + (Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - ((j + 1) * scaleHop2), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YRIGHTAXIS_TEXTMOUSE",0,0,0,-1,j);
					}
				}
			}
		};

		function getValueBounds() {
			var upperValue = -Number.MAX_VALUE;
			var lowerValue = Number.MAX_VALUE;
			var upperValue2 = -Number.MAX_VALUE;
			var lowerValue2 = Number.MAX_VALUE;
			var secondAxis = false;
			var firstAxis = false;
			var mathValueHeight;
			for (var i = 0; i < data.datasets.length; i++) {
				var mathFctName = data.datasets[i].drawMathDeviation;
				var mathValueHeight = 0;
				if (typeof eval(mathFctName) == "function") {
					var parameter = {
						data: data,
						datasetNr: i
					};
					mathValueHeightVal = window[mathFctName](parameter);
				} else mathValueHeightVal=0;
				for (var j = 0; j < data.datasets[i].data.length; j++) {
					if(typeof mathValueHeightVal=="object") mathValueHeight=mathValueHeightVal[Math.min(mathValueHeightVal.length,j)];
					else mathValueHeight=mathValueHeightVal;
					
					if(typeof data.datasets[i].data[j] == "undefined") continue;
					if (data.datasets[i].axis == 2) {
						secondAxis = true;
						if (1 * data.datasets[i].data[j] + mathValueHeight > upperValue2) {
							upperValue2 = 1 * data.datasets[i].data[j] + mathValueHeight
						};
						if (1 * data.datasets[i].data[j] - mathValueHeight < lowerValue2) {
							lowerValue2 = 1 * data.datasets[i].data[j] - mathValueHeight
						};
					} else {
						firstAxis = true;
						if (1 * data.datasets[i].data[j] + mathValueHeight > upperValue) {
							upperValue = 1 * data.datasets[i].data[j] + mathValueHeight
						};
						if (1 * data.datasets[i].data[j] - mathValueHeight < lowerValue) {
							lowerValue = 1 * data.datasets[i].data[j] - mathValueHeight
						};
					}
				}
			};
			if(upperValue<lowerValue){upperValue=0;lowerValue=0;}
			if (Math.abs(upperValue - lowerValue) < config.zeroValue) {
				if(Math.abs(upperValue)< config.zeroValue) upperValue = .9;
				if(upperValue>0) {
					upperValue=upperValue*1.1;
					lowerValue=lowerValue*0.9;
				} else {
					upperValue=upperValue*0.9;
					lowerValue=lowerValue*1.1;
				}
				
			}
			if(typeof config.graphMin=="function")lowerValue= setOptionValue(true,1,"GRAPHMIN",ctx,data,statData,undefined,config.graphMin,"graphMin",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMin)) lowerValue = config.graphMin;
			if(typeof config.graphMax=="function") upperValue= setOptionValue(true,1,"GRAPHMAX",ctx,data,statData,undefined,config.graphMax,"graphMax",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMax)) upperValue = config.graphMax;
			if (secondAxis) {
				if(upperValue2<lowerValue2){upperValue2=0;lowerValue2=0;}
				if (Math.abs(upperValue2 - lowerValue2) < config.zeroValue) {
					if(Math.abs(upperValue2)< config.zeroValue) upperValue2 = .9;
					if(upperValue2>0) {
						upperValue2=upperValue2*1.1;
						lowerValue2=lowerValue2*0.9;
					} else {
						upperValue2=upperValue2*0.9;
						lowerValue2=lowerValue2*1.1;
					}
				}
				if(typeof config.graphMin2=="function")lowerValue2= setOptionValue(true,1,"GRAPHMIN",ctx,data,statData,undefined,config.graphMin2,"graphMin2",-1,-1,{nullValue : true})
				else if (!isNaN(config.graphMin2)) lowerValue2 = config.graphMin2;
				if(typeof config.graphMax2=="function") upperValue2= setOptionValue(true,1,"GRAPHMAX",ctx,data,statData,undefined,config.graphMax2,"graphMax2",-1,-1,{nullValue : true})
				else if (!isNaN(config.graphMax2)) upperValue2 = config.graphMax2;
			}
			if (!firstAxis && secondAxis) {
				upperValue = upperValue2;
				lowerValue = lowerValue2;
			}
			labelHeight = (Math.ceil(ctx.chartTextScale*config.scaleFontSize));
			scaleHeight = msr.availableHeight;
			var maxSteps = Math.floor((scaleHeight / (labelHeight * 0.66)));
			var minSteps = Math.floor((scaleHeight / labelHeight * 0.5));
			if(upperValue<lowerValue){lowerValue=upperValue-1;}
			if(upperValue2<lowerValue2){lowerValue2=upperValue2-1;}
			return {
				maxValue: upperValue,
				minValue: lowerValue,
				maxValue2: upperValue2,
				minValue2: lowerValue2,
				dbAxis: secondAxis,
				maxSteps: maxSteps,
				minSteps: minSteps
			};
		};
		return {
			data:data,
			config:config,
			ctx:ctx
		};
	};
	var StackedBar = function(data, config, ctx) {
		var maxSize, scaleHop, calculatedScale, labelHeight, scaleHeight, valueBounds, labelTemplateString, valueHop, widestXLabel, xAxisLength, yAxisPosX, xAxisPosY, barWidth, rotateLabels = 0,
			msr;

		ctx.tpchart="StackedBar";
		ctx.tpdata=0;

	        if (!init_and_start(ctx,data,config)) return;
		var statData=initPassVariableData_part1(data,config,ctx);

		var nrOfBars = data.datasets.length;
		for (var i = 0; i < data.datasets.length; i++) {
			if (data.datasets[i].type == "Line") { statData[i][0].tpchart="Line";nrOfBars--;}
			else statData[i][0].tpchart="Bar";	
		}                               
		config.logarithmic = false;
		msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, "nihil", [""], true, false, true, true, true, "StackedBar");
		valueBounds = getValueBounds();
		if(valueBounds.maxSteps>0 && valueBounds.minSteps>0) {
			//Check and set the scale
			labelTemplateString = (config.scaleShowLabels) ? config.scaleLabel : "";
			if (!config.scaleOverride) {
				calculatedScale = calculateScale(1, config, valueBounds.maxSteps, valueBounds.minSteps, valueBounds.maxValue, valueBounds.minValue, labelTemplateString);
				msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, null, true, false, true, true, true, "StackedBar");
			} else {
				var scaleStartValue= setOptionValue(true,1,"SCALESTARTVALUE",ctx,data,statData,undefined,config.scaleStartValue,"scaleStartValue",-1,-1,{nullValue : true} );
				var scaleSteps =setOptionValue(true,1,"SCALESTEPS",ctx,data,statData,undefined,config.scaleSteps,"scaleSteps",-1,-1,{nullValue : true} );
				var scaleStepWidth = setOptionValue(true,1,"SCALESTEPWIDTH",ctx,data,statData,undefined,config.scaleStepWidth,"scaleStepWidth",-1,-1,{nullValue : true} );

				calculatedScale = {
					steps: scaleSteps,
					stepValue: scaleStepWidth,
					graphMin: scaleStartValue,
					labels: []
				}

				for (var i = 0; i <= calculatedScale.steps; i++) {
					if (labelTemplateString) {
						calculatedScale.labels.push(tmpl(labelTemplateString, {
							value: fmtChartJS(config, 1 * ((scaleStartValue + (scaleStepWidth * i)).toFixed(getDecimalPlaces(scaleStepWidth))), config.fmtYLabel)
						},config));
					}
				}
				msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, null, true, false, true, true, true, "StackedBar");
			}
			var prevHeight=msr.availableHeight;
       	
			msr.availableHeight = msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
			msr.availableWidth = msr.availableWidth - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft) - Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight);
			scaleHop = Math.floor(msr.availableHeight / calculatedScale.steps);
			valueHop = Math.floor(msr.availableWidth / (data.labels.length));
			if (valueHop == 0 || config.fullWidthGraph) valueHop = (msr.availableWidth / data.labels.length);
			msr.clrwidth = msr.clrwidth - (msr.availableWidth - ((data.labels.length) * valueHop));
			msr.availableWidth = (data.labels.length) * valueHop;

			msr.availableHeight = (calculatedScale.steps) * scaleHop;
			msr.xLabelPos+=(Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop) - (prevHeight-msr.availableHeight));
			msr.clrheight+=(Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop) - (prevHeight-msr.availableHeight));
       	
			yAxisPosX = msr.leftNotUsableSize + Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft);
			xAxisPosY = msr.topNotUsableSize + msr.availableHeight + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
			barWidth = (valueHop - Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth) * 2 - (Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) * 2) - (Math.ceil(ctx.chartSpaceScale*config.barDatasetSpacing) * 1 - 1) - (Math.ceil(ctx.chartLineScale*config.barStrokeWidth) / 2) - 1);
			if(barWidth>=0 && barWidth<=1)barWidth=1;
			if(barWidth<0 && barWidth>=-1)barWidth=-1;
			var additionalSpaceBetweenBars;
			if(1*config.maxBarWidth >0 && barWidth > 1*config.maxBarWidth) {
				additionalSpaceBetweenBars=(barWidth-1*config.maxBarWidth)/2;
				barWidth=1*config.maxBarWidth;
			} else additionalSpaceBetweenBars=0;

			var zeroY = 0;
			var zeroY2 = 0;

			zeroY = calculateOffset(false, 0, calculatedScale, scaleHop);
			if(typeof calculatedScale2 ==="object") zeroY2 = calculateOffset(config.logarithmic2, 0, calculatedScale2, scaleHop2);       	
			drawLabels();
			initPassVariableData_part2(statData,data,config,ctx,{ 
				msr: msr,
				zeroY : zeroY,
				zeroY2 : zeroY2,
				logarithmic  : false,
				logarithmic2 : false,
				calculatedScale : calculatedScale,
				additionalSpaceBetweenBars : additionalSpaceBetweenBars,
				scaleHop : scaleHop,
				valueHop : valueHop,
				yAxisPosX : yAxisPosX,
				xAxisPosY : xAxisPosY,
				barWidth : barWidth
			 });
			animationLoop(config, drawScale, drawBars, ctx, msr.clrx, msr.clry, msr.clrwidth, msr.clrheight, yAxisPosX + msr.availableWidth / 2, xAxisPosY - msr.availableHeight / 2, yAxisPosX, xAxisPosY, data, statData);
		} else {
			testRedraw(ctx,data,config);
			ctx.firstPass=9;
		}
		function drawBars(animPc) {
			var prevTopPos = new Array();
			var prevTopNeg = new Array();

			for (var i = 0; i < data.datasets.length; i++) {
				if(data.datasets[i].type=="Line") continue;
				for (var j = 0; j < data.datasets[i].data.length; j++) {
					ctx.save();
					ctx.lineWidth=Math.ceil(ctx.chartLineScale*setOptionValue(true,1,"BARSTROKEWIDTH",ctx,data,statData,data.datasets[i].barStrokeWidth,config.barStrokeWidth,"barStrokeWidth",i,j,{animationValue: currentAnimPc, xPosLeft : statData[i][j].xPosLeft, yPosBottom : botBar, xPosRight : statData[i][j].xPosRight, yPosTop : topBar} ));					
					var currentAnimPc = animationCorrection(animPc, data, config, i, j, false).animVal;
					if (currentAnimPc > 1) currentAnimPc = currentAnimPc - 1;
					if ((typeof data.datasets[i].data[j] == 'undefined') || 1*data.datasets[i].data[j] == 0 ) continue;
					if(typeof prevTopPos[j]=="undefined"){
						prevTopPos[j]=statData[statData[i][j].firstNotMissing][j].yPosBottom;
						prevTopNeg[j]=statData[statData[i][j].firstNotMissing][j].yPosBottom;
					}
					var botBar, topBar;
					if(1*data.datasets[i].data[j] > 0) botBar=prevTopPos[j];
					else botBar=prevTopNeg[j];
					topBar=botBar+currentAnimPc*(statData[i][j].yPosTop-statData[i][j].yPosBottom);
					if(1*data.datasets[i].data[j] > 0) prevTopPos[j]=topBar;
					else prevTopNeg[j]=topBar;
					ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data.datasets[i].fillColor,config.defaultFillColor,"fillColor",i,j,{animationValue: currentAnimPc, xPosLeft : statData[i][j].xPosLeft, yPosBottom : botBar, xPosRight : statData[i][j].xPosRight, yPosTop : topBar} );
					ctx.strokeStyle=setOptionValue(true,1,"STROKECOLOR",ctx,data,statData,data.datasets[i].strokeColor,config.defaultStrokeColor,"strokeColor",i,j,{nullvalue : null} );

					if(currentAnimPc !=0 && botBar!=topBar) {
						ctx.beginPath();
						ctx.moveTo(statData[i][j].xPosLeft, botBar+(topBar-botBar)/2);
						ctx.lineTo(statData[i][j].xPosLeft, topBar);
						ctx.lineTo(statData[i][j].xPosRight, topBar);
						ctx.lineTo(statData[i][j].xPosRight, botBar);
						ctx.lineTo(statData[i][j].xPosLeft, botBar);
						ctx.lineTo(statData[i][j].xPosLeft, botBar+(topBar-botBar)/2);
						if (config.barShowStroke) { 
							ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"STROKESTYLE",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
							ctx.stroke();
							ctx.setLineDash([]);
						};
						ctx.closePath();
						ctx.fill();
					}
					ctx.restore();
				}
			}
			drawLinesDataset(animPc, data, config, ctx, statData,{xAxisPosY : xAxisPosY,yAxisPosX : yAxisPosX, valueHop : valueHop, nbValueHop : data.labels.length });

			if (animPc >= config.animationStopValue) {
				var 	yPos = 0,
					xPos = 0;
				for (i = 0; i < data.datasets.length; i++) {
					for (j = 0; j < data.datasets[i].data.length; j++) {
						if (typeof(data.datasets[i].data[j]) == 'undefined') continue;
						jsGraphAnnotate[ctx.ChartNewId][jsGraphAnnotate[ctx.ChartNewId].length] = ["RECT", i, j, statData,setOptionValue(true,1,"ANNOTATEDISPLAY",ctx,data,statData,data.datasets[i].annotateDisplay,config.annotateDisplay,"annotateDisplay",i,j,{nullValue : true})];
						if(setOptionValue(true,1,"INGRAPHDATASHOW",ctx,data,statData,data.datasets[i].inGraphDataShow,config.inGraphDataShow,"inGraphDataShow",i,j,{nullValue : true})) {
							ctx.save();
							ctx.textAlign = setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,j,{nullValue: true  });
							ctx.textBaseline = setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,j,{nullValue : true} );
							ctx.font = setOptionValue(true,1,"INGRAPHDATAFONTSTYLE",ctx,data,statData,undefined,config.inGraphDataFontStyle,"inGraphDataFontStyle",i,j,{nullValue : true} ) + ' ' + setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ) + 'px ' + setOptionValue(true,1,"INGRAPHDATAFONTFAMILY",ctx,data,statData,undefined,config.inGraphDataFontFamily,"inGraphDataFontFamily",i,j,{nullValue : true} );
							ctx.fillStyle = setOptionValue(true,1,"INGRAPHDATAFONTCOLOR",ctx,data,statData,undefined,config.inGraphDataFontColor,"inGraphDataFontColor",i,j,{nullValue : true} );
							var dispString = tmplbis(setOptionValue(true,1,"INGRAPHDATATMPL",ctx,data,statData,undefined,config.inGraphDataTmpl,"inGraphDataTmpl",i,j,{nullValue : true} ), statData[i][j],config);
							ctx.beginPath();
							ctx.beginPath();
							yPos = 0;
							xPos = 0;
							if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 1) {
								xPos = statData[i][j].xPosLeft + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 2) {
								xPos = statData[i][j].xPosLeft + barWidth / 2 + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 3) {
								xPos = statData[i][j].xPosLeft+ barWidth + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							}
							if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 1) {
								yPos = statData[i][j].yPosBottom - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 2) {
								yPos = (statData[i][j].yPosTop + statData[i][j].yPosBottom)/2 - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 3) {
								yPos = statData[i][j].yPosTop - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							}
							if(yPos>msr.topNotUsableSize) {
								ctx.translate(xPos, yPos);
								var rotateVal=setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,j,{nullValue : true} ) * (Math.PI / 180);
								ctx.rotate(rotateVal);
								setTextBordersAndBackground(ctx,dispString,setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),0,0,setOptionValue(true,1,"INGRAPHDATABORDERS",ctx,data,statData,undefined,config.inGraphDataBorders,"inGraphDataBorders",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSCOLOR",ctx,data,statData,undefined,config.inGraphDataBordersColor,"inGraphDataBordersColor",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartLineScale,"INGRAPHDATABORDERSWIDTH",ctx,data,statData,undefined,config.inGraphDataBordersWidth,"inGraphDataBordersWidth",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSXSPACE",ctx,data,statData,undefined,config.inGraphDataBordersXSpace,"inGraphDataBordersXSpace",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSYSPACE",ctx,data,statData,undefined,config.inGraphDataBordersYSpace,"inGraphDataBordersYSpace",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSSTYLE",ctx,data,statData,undefined,config.inGraphDataBordersStyle,"inGraphDataBordersStyle",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABACKGROUNDCOLOR",ctx,data,statData,undefined,config.inGraphDataBackgroundColor,"inGraphDataBackgroundColor",i,j,{nullValue : true} ),"INGRAPHDATA",config.inGraphDataBordersRadius);
								ctx.fillTextMultiLine(dispString, 0, 0, ctx.textBaseline, setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),true,config.detectMouseOnText,ctx,"INGRAPHDATA_TEXTMOUSE",rotateVal,xPos, yPos,i,j);
							}
							ctx.restore();
						}
					}
				}
			}
			if(msr.legendMsr.dispLegend)drawLegend(msr.legendMsr,data,config,ctx,"StackedBar");
		};

		function drawScale() {
			//X axis line                                                          
			ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
			ctx.strokeStyle = config.scaleLineColor;
			ctx.setLineDash(lineStyleFn(config.scaleLineStyle));
			ctx.beginPath();
			ctx.moveTo(yAxisPosX - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft), xAxisPosY);
			ctx.lineTo(yAxisPosX + msr.availableWidth + Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight), xAxisPosY);
			ctx.stroke();
			ctx.setLineDash([]);
			ctx.setLineDash(lineStyleFn(config.scaleGridLineStyle));
			for (var i = 0; i < data.labels.length; i++) {
				ctx.beginPath();
				ctx.moveTo(yAxisPosX + i * valueHop, xAxisPosY + Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom));
				ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth);
				ctx.strokeStyle = config.scaleGridLineColor;
				//Check i isnt 0, so we dont go over the Y axis twice.
				if (config.scaleShowGridLines && i > 0 && i % config.scaleXGridLinesStep == 0) {
					ctx.lineTo(yAxisPosX + i * valueHop, xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop));
				} else {
					ctx.lineTo(yAxisPosX + i * valueHop, xAxisPosY);
				}
				ctx.stroke();
			}
			ctx.setLineDash([]);
			
			//Y axis
			ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
			ctx.strokeStyle = config.scaleLineColor;
			ctx.setLineDash(lineStyleFn(config.scaleLineStyle));
			ctx.beginPath();
			ctx.moveTo(yAxisPosX, xAxisPosY + Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom));
			ctx.lineTo(yAxisPosX, xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop));
			ctx.stroke();
			ctx.setLineDash([]);
			
			ctx.setLineDash(lineStyleFn(config.scaleGridLineStyle));
			for (var j = ((config.showYAxisMin) ? -1 : 0); j < calculatedScale.steps; j++) {
				ctx.beginPath();
				ctx.moveTo(yAxisPosX - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft), xAxisPosY - ((j + 1) * scaleHop));
				ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth);
				ctx.strokeStyle = config.scaleGridLineColor;
				if (config.scaleShowGridLines && (j+1) % config.scaleYGridLinesStep == 0) {
					ctx.lineTo(yAxisPosX + msr.availableWidth + Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight), xAxisPosY - ((j + 1) * scaleHop));
				} else {
					ctx.lineTo(yAxisPosX, xAxisPosY - ((j + 1) * scaleHop));
				}
				ctx.stroke();
			}
			ctx.setLineDash([]);
		};

		function drawLabels() {
			ctx.font = config.scaleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.scaleFontSize)).toString() + "px " + config.scaleFontFamily;
			//X axis labels                                                          
			if (config.xAxisTop || config.xAxisBottom) {
				ctx.textBaseline = "top";
				if (msr.rotateLabels > 90) {
					ctx.save();
					ctx.textAlign = "left";
				} else if (msr.rotateLabels > 0) {
					ctx.save();
					ctx.textAlign = "right";
				} else {
					ctx.textAlign = "center";
				}
				ctx.fillStyle = config.scaleFontColor;
				if (config.xAxisBottom) {
					for (var i = 0; i < data.labels.length; i++) {
						if(showLabels(ctx,data,config,i)){
							ctx.save();
							if (msr.rotateLabels > 0) {
								ctx.translate(yAxisPosX + Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) + i * valueHop + additionalSpaceBetweenBars+ (barWidth / 2) - msr.highestXLabel / 2, msr.xLabelPos);
								ctx.rotate(-(msr.rotateLabels * (Math.PI / 180)));
								ctx.fillTextMultiLine(fmtChartJS(config, data.labels[i], config.fmtXLabel), 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"XAXIS_TEXTMOUSE",-(msr.rotateLabels * (Math.PI / 180)),yAxisPosX + Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) + i * valueHop + additionalSpaceBetweenBars+(barWidth / 2) - msr.highestXLabel / 2, msr.xLabelPos,i,-1);
							} else {
								ctx.fillTextMultiLine(fmtChartJS(config, data.labels[i], config.fmtXLabel), yAxisPosX + Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) + i * valueHop + additionalSpaceBetweenBars+(barWidth / 2), msr.xLabelPos, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"XAXIS_TEXTMOUSE",0,0,0,i,-1);
							}
							ctx.restore();
						}
					}
				}
			}
			//Y axis
			ctx.textAlign = "right";
			ctx.textBaseline = "middle";
			for (var j = ((config.showYAxisMin) ? -1 : 0); j < calculatedScale.steps; j++) {
				if (config.scaleShowLabels) {
					if(showYLabels(ctx,data,config,j+1,calculatedScale.labels[j + 1])) {				
						if (config.yAxisLeft) {
							ctx.textAlign = "right";
							ctx.fillTextMultiLine(calculatedScale.labels[j + 1], yAxisPosX - (Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - ((j + 1) * scaleHop), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YLEFTAXIS_TEXTMOUSE",0,0,0,-1,j);
						}
						if (config.yAxisRight) {
							ctx.textAlign = "left";
							ctx.fillTextMultiLine(calculatedScale.labels[j + 1], yAxisPosX + msr.availableWidth + (Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - ((j + 1) * scaleHop), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YRIGHTAXIS_TEXTMOUSE",0,0,0,-1,j);
						}
					}
				}
			}
		};

		function getValueBounds() {
			var maxValp = -Number.MAX_VALUE;
			var minValp = Number.MAX_VALUE;
			var maxValn = -Number.MAX_VALUE;
			var minValn = Number.MAX_VALUE;

			var tempp=[];
			var tempn=[];
			var inp=0;
			var inn=0;

			for (var i = 0; i < data.datasets.length; i++) {
				for (var j = 0; j < data.datasets[i].data.length; j++) {
					if(1 * data.datasets[i].data[j] > 0) {
						if(statData[i][0].tpchart=="Bar") {
							if(typeof tempp[j] == "undefined") tempp[j]=0;
							tempp[j] += 1 * data.datasets[i].data[j];
							maxValp=Math.max(maxValp,tempp[j]);
						} else maxValp=Math.max(maxValp,1 * data.datasets[i].data[j]);
						minValp=Math.min(minValp,1 * data.datasets[i].data[j]);
						inp=1;
					} else if(typeof (1 * data.datasets[i].data[j])==="number" && typeof data.datasets[i].data[j]!="undefined") {
						if(statData[i][0].tpchart=="Bar") {
							if(typeof tempn[j] == "undefined") tempn[j]=0;
							tempn[j] += (1 * data.datasets[i].data[j]);
							minValn=Math.min(minValn,tempn[j]);
						} else minValn=Math.min(minValn,1 * data.datasets[i].data[j]);
						maxValn=Math.max(maxValn,1 * data.datasets[i].data[j]);
						inn=1;
					}
				}
			};
			var upperValue, lowerValue;
			if (inp==0){upperValue=maxValn;lowerValue=minValn;}
			else if(inn==0) { upperValue=maxValp;lowerValue=minValp;}
			else { upperValue=maxValp;lowerValue=minValn; }

			if(typeof config.graphMin=="function")lowerValue= setOptionValue(true,1,"GRAPHMIN",ctx,data,statData,undefined,config.graphMin,"graphMin",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMin)) lowerValue = config.graphMin;
			if(typeof config.graphMax=="function") upperValue= setOptionValue(true,1,"GRAPHMAX",ctx,data,statData,undefined,config.graphMax,"graphMax",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMax)) upperValue = config.graphMax;

			if(upperValue<lowerValue){upperValue=0;lowerValue=0;}
			if (Math.abs(upperValue - lowerValue) < config.zeroValue) {
				if(Math.abs(upperValue)< config.zeroValue) upperValue = .9;
				if(upperValue>0) {
					upperValue=upperValue*1.1;
					lowerValue=lowerValue*0.9;
				} else {
					upperValue=upperValue*0.9;
					lowerValue=lowerValue*1.1;
				}
			}

			labelHeight = (Math.ceil(ctx.chartTextScale*config.scaleFontSize));
			scaleHeight = msr.availableHeight;
			var maxSteps = Math.floor((scaleHeight / (labelHeight * 0.66)));
			var minSteps = Math.floor((scaleHeight / labelHeight * 0.5));
			if(upperValue<lowerValue){lowerValue=upperValue-1;}

			return {
				maxValue: upperValue,
				minValue: lowerValue,
				maxSteps: maxSteps,
				minSteps: minSteps
			};
		};
		return {
			data:data,
			config:config,
			ctx:ctx
		};
	};
	/**
	 * Reverse the data structure for horizontal charts
	 * - reverse labels and every array inside datasets
	 * @param {object} data datasets and labels for the chart
	 * @return return the reversed data
	 */
	function reverseData(data) {
		data.labels = data.labels.reverse();
		for (var i = 0; i < data.datasets.length; i++) {
			for (var key in data.datasets[i]) {
				if (Array.isArray(data.datasets[i][key])) {
					data.datasets[i][key] = data.datasets[i][key].reverse();
				}
			}
		}
		return data;
	};
	var HorizontalStackedBar = function(data, config, ctx) {
		var maxSize, scaleHop, calculatedScale, labelHeight, scaleHeight, valueBounds, labelTemplateString, valueHop, widestXLabel, xAxisLength, yAxisPosX, xAxisPosY, barWidth, rotateLabels = 0,
			msr;

		if (config.reverseOrder && typeof ctx.reversed == "undefined") {
			ctx.reversed=true;
			data = reverseData(data);
		}

		ctx.tpchart="HorizontalStackedBar";
		ctx.tpdata=0;
	        if (!init_and_start(ctx,data,config)) return;
		var statData=initPassVariableData_part1(data,config,ctx);

		config.logarithmic = false;
		msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, "nihil", [""], true, true, true, true, true, "HorizontalStackedBar");
 		valueBounds = getValueBounds();
		
		if(valueBounds.maxSteps>0 && valueBounds.minSteps>0) {
			//Check and set the scale
			labelTemplateString = (config.scaleShowLabels) ? config.scaleLabel : "";
			if (!config.scaleOverride) {
				calculatedScale = calculateScale(1, config, valueBounds.maxSteps, valueBounds.minSteps, valueBounds.maxValue, valueBounds.minValue, labelTemplateString);
				msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, null, true, true, true, true, true, "HorizontalStackedBar");
			} else {
				var scaleStartValue= setOptionValue(true,1,"SCALESTARTVALUE",ctx,data,statData,undefined,config.scaleStartValue,"scaleStartValue",-1,-1,{nullValue : true} );
				var scaleSteps =setOptionValue(true,1,"SCALESTEPS",ctx,data,statData,undefined,config.scaleSteps,"scaleSteps",-1,-1,{nullValue : true} );
				var scaleStepWidth = setOptionValue(true,1,"SCALESTEPWIDTH",ctx,data,statData,undefined,config.scaleStepWidth,"scaleStepWidth",-1,-1,{nullValue : true} );

				calculatedScale = {
					steps: scaleSteps,
					stepValue: scaleStepWidth,
					graphMin: scaleStartValue,
					labels: []
				}
				for (var i = 0; i <= calculatedScale.steps; i++) {
					if (labelTemplateString) {
						calculatedScale.labels.push(tmpl(labelTemplateString, {
							value: fmtChartJS(config, 1 * ((scaleStartValue + (scaleStepWidth * i)).toFixed(getDecimalPlaces(scaleStepWidth))), config.fmtYLabel)
						},config));
					}
				}
				msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, null, true, true, true, true, true, "HorizontalStackedBar");
			}
			msr.availableHeight = msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
			msr.availableWidth = msr.availableWidth - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft) - Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight);
			scaleHop = Math.floor(msr.availableHeight / data.labels.length);
			valueHop = Math.floor(msr.availableWidth / (calculatedScale.steps));
			if (valueHop == 0 || config.fullWidthGraph) valueHop = (msr.availableWidth / (calculatedScale.steps));
			msr.clrwidth = msr.clrwidth - (msr.availableWidth - (calculatedScale.steps * valueHop));
			msr.availableWidth = (calculatedScale.steps) * valueHop;
			msr.availableHeight = (data.labels.length) * scaleHop;
			yAxisPosX = msr.leftNotUsableSize + Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft);
			xAxisPosY = msr.topNotUsableSize + msr.availableHeight + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
			barWidth = (scaleHop - Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth) * 2 - (Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) * 2) - (Math.ceil(ctx.chartSpaceScale*config.barDatasetSpacing) * 1 - 1) - (Math.ceil(ctx.chartLineScale*config.barStrokeWidth) / 2) - 1);
			if(barWidth>=0 && barWidth<=1)barWidth=1;
			if(barWidth<0 && barWidth>=-1)barWidth=-1;
			var additionalSpaceBetweenBars;
			if(1*config.maxBarWidth >0 && barWidth > 1*config.maxBarWidth) {
				additionalSpaceBetweenBars= (barWidth-1*config.maxBarWidth)/2;
				barWidth=1*config.maxBarWidth;
			} else additionalSpaceBetweenBars=0;

			drawLabels();
			var zeroY=  HorizontalCalculateOffset(0 , calculatedScale, scaleHop);
			initPassVariableData_part2(statData,data,config,ctx,{ 
				yAxisPosX : yAxisPosX,
				additionalSpaceBetweenBars : additionalSpaceBetweenBars,
				xAxisPosY : xAxisPosY,
				barWidth : barWidth,
				zeroY : zeroY,
				scaleHop : scaleHop,
				valueHop : valueHop,
				calculatedScale : calculatedScale
			});
			
			animationLoop(config, drawScale, drawBars, ctx, msr.clrx, msr.clry, msr.clrwidth, msr.clrheight, yAxisPosX + msr.availableWidth / 2, xAxisPosY - msr.availableHeight / 2, yAxisPosX, xAxisPosY, data, statData);
		} else {
			testRedraw(ctx,data,config);
			ctx.firstPass=9;
		}
		function HorizontalCalculateOffset(val, calculatedScale, scaleHop) {
			var outerValue = calculatedScale.steps * calculatedScale.stepValue;
			var adjustedValue = val - calculatedScale.graphMin;
			var scalingFactor = CapValue(adjustedValue / outerValue, 1, 0);
			return (scaleHop * calculatedScale.steps) * scalingFactor;
		};

		function drawBars(animPc) {
			var prevLeftPos = new Array();
			var prevLeftNeg = new Array();

//			ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.barStrokeWidth);
			for (var i = 0; i < data.datasets.length; i++) {
				for (var j = 0; j < data.datasets[i].data.length; j++) {
					ctx.lineWidth=Math.ceil(ctx.chartLineScale*setOptionValue(true,1,"BARSTROKEWIDTH",ctx,data,statData,data.datasets[i].barStrokeWidth,config.barStrokeWidth,"barStrokeWidth",i,j,{animationValue: currentAnimPc, xPosLeft : leftBar, yPosBottom : statData[i][j].yPosBottom, xPosRight : rightBar, yPosTop : statData[i][j].yPosBottom} ));
					var currentAnimPc = animationCorrection(animPc, data, config, i, j, false).animVal;
					if (currentAnimPc > 1) currentAnimPc = currentAnimPc - 1;
					if ((typeof(data.datasets[i].data[j]) == 'undefined') || 1*data.datasets[i].data[j] == 0 ) continue;
					if(typeof prevLeftPos[j]=="undefined"){
						prevLeftPos[j]=statData[statData[i][j].firstNotMissing][j].xPosLeft;
						prevLeftNeg[j]=statData[statData[i][j].firstNotMissing][j].xPosLeft;
					}

					var leftBar, rightBar;
////					if(config.animationByDataset) {
////						leftBar= statData[i][j].xPosLeft;
////						rightBar= statData[i][j].xPosRight;
////						rightBar=leftBar+currentAnimPc*(rightBar-leftBar);
////					} else {
						if(1*data.datasets[i].data[j] > 0) leftBar=prevLeftPos[j];
						else leftBar=prevLeftNeg[j];
//						leftBar=statData[statData[i][j].firstNotMissing][j].xPosLeft + currentAnimPc*(statData[i][j].xPosLeft-statData[statData[i][j].firstNotMissing][j].xPosLeft);
//						rightBar=statData[statData[i][j].firstNotMissing][j].xPosLeft + currentAnimPc*(statData[i][j].xPosRight-statData[statData[i][j].firstNotMissing][j].xPosLeft);
						rightBar=leftBar+currentAnimPc*(statData[i][j].xPosRight-statData[i][j].xPosLeft);

						if(1*data.datasets[i].data[j] > 0) prevLeftPos[j]=rightBar;
						else prevLeftNeg[j]=rightBar;
////					}
					ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data.datasets[i].fillColor,config.defaultFillColor,"fillColor",i,j,{animationValue: currentAnimPc, xPosLeft : leftBar, yPosBottom : statData[i][j].yPosBottom, xPosRight : rightBar, yPosTop : statData[i][j].yPosBottom} );

					ctx.strokeStyle=setOptionValue(true,1,"STROKECOLOR",ctx,data,statData,data.datasets[i].strokeColor,config.defaultStrokeColor,"strokeColor",i,j,{nullvalue : null} );

					if(currentAnimPc !=0 && statData[i][j].xPosLeft!=statData[i][j].xPosRight ) {
						ctx.beginPath();
						ctx.moveTo(leftBar+(rightBar-leftBar)/2, statData[i][j].yPosTop);
						ctx.lineTo(rightBar, statData[i][j].yPosTop);
						ctx.lineTo(rightBar, statData[i][j].yPosBottom);
						ctx.lineTo(leftBar, statData[i][j].yPosBottom);
						ctx.lineTo(leftBar, statData[i][j].yPosTop);
						ctx.lineTo(leftBar+(rightBar-leftBar)/2, statData[i][j].yPosTop);
						if (config.barShowStroke){  
							ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"STROKESTYLE",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
							ctx.stroke(); 
							ctx.setLineDash([]);
						}
						ctx.closePath();
						ctx.fill();
					}
				}
			}
			if (animPc >= config.animationStopValue) {
				var yPos = 0,
					xPos = 0;
				for (i = 0; i < data.datasets.length; i++) {
					for (j = 0; j < data.datasets[i].data.length; j++) {
						if ((typeof(data.datasets[i].data[j]) == 'undefined')) continue;
						jsGraphAnnotate[ctx.ChartNewId][jsGraphAnnotate[ctx.ChartNewId].length] = ["RECT", i ,j, statData,setOptionValue(true,1,"ANNOTATEDISPLAY",ctx,data,statData,data.datasets[i].annotateDisplay,config.annotateDisplay,"annotateDisplay",i,j,{nullValue : true})];
						if(setOptionValue(true,1,"INGRAPHDATASHOW",ctx,data,statData,data.datasets[i].inGraphDataShow,config.inGraphDataShow,"inGraphDataShow",i,j,{nullValue : true})) {
							ctx.save();
							ctx.textAlign = setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,j,{nullValue: true  });
							ctx.textBaseline = setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,j,{nullValue : true} );
							ctx.font = setOptionValue(true,1,"INGRAPHDATAFONTSTYLE",ctx,data,statData,undefined,config.inGraphDataFontStyle,"inGraphDataFontStyle",i,j,{nullValue : true} ) + ' ' + setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ) + 'px ' + setOptionValue(true,1,"INGRAPHDATAFONTFAMILY",ctx,data,statData,undefined,config.inGraphDataFontFamily,"inGraphDataFontFamily",i,j,{nullValue : true} );
							ctx.fillStyle = setOptionValue(true,1,"INGRAPHDATAFONTCOLOR",ctx,data,statData,undefined,config.inGraphDataFontColor,"inGraphDataFontColor",i,j,{nullValue : true} );
							var dispString = tmplbis(setOptionValue(true,1,"INGRAPHDATATMPL",ctx,data,statData,undefined,config.inGraphDataTmpl,"inGraphDataTmpl",i,j,{nullValue : true} ),statData[i][j],config);
							ctx.beginPath();
							yPos = 0;
							xPos = 0;
							if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 1) {
								xPos = statData[i][j].xPosLeft + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 2) {
								xPos = statData[i][j].xPosLeft + (statData[i][j].xPosRight-statData[i][j].xPosLeft)/2 + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 3) {
								xPos = statData[i][j].xPosRight + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							}
							if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 1) {
								yPos = statData[i][j].yPosBottom - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 2) {
								yPos = statData[i][j].yPosBottom - barWidth / 2 - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 3) {
								yPos = statData[i][j].yPosTop - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							}
//								if(xPos<=msr.availableWidth+msr.leftNotUsableSize) {
								ctx.translate(xPos, yPos);
								rotateVal=setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,j,{nullValue : true} ) * (Math.PI / 180);
								ctx.rotate(rotateVal);
								setTextBordersAndBackground(ctx,dispString,setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),0,0,setOptionValue(true,1,"INGRAPHDATABORDERS",ctx,data,statData,undefined,config.inGraphDataBorders,"inGraphDataBorders",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSCOLOR",ctx,data,statData,undefined,config.inGraphDataBordersColor,"inGraphDataBordersColor",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartLineScale,"INGRAPHDATABORDERSWIDTH",ctx,data,statData,undefined,config.inGraphDataBordersWidth,"inGraphDataBordersWidth",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSXSPACE",ctx,data,statData,undefined,config.inGraphDataBordersXSpace,"inGraphDataBordersXSpace",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSYSPACE",ctx,data,statData,undefined,config.inGraphDataBordersYSpace,"inGraphDataBordersYSpace",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSSTYLE",ctx,data,statData,undefined,config.inGraphDataBordersStyle,"inGraphDataBordersStyle",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABACKGROUNDCOLOR",ctx,data,statData,undefined,config.inGraphDataBackgroundColor,"inGraphDataBackgroundColor",i,j,{nullValue : true} ),"INGRAPHDATA",config.inGraphDataBordersRadius);
								ctx.fillTextMultiLine(dispString, 0, 0, ctx.textBaseline, setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),true,config.detectMouseOnText,ctx,"INGRAPHDATA_TEXTMOUSE",rotateVal,xPos, yPos,i,j);
								ctx.restore();
//							}
						}
					}
				}
			}
			if(msr.legendMsr.dispLegend)drawLegend(msr.legendMsr,data,config,ctx,"HorizontalStackedBar");
		};

		function drawScale() {
			//X axis line                                                          
			ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
			ctx.strokeStyle = config.scaleLineColor;
			ctx.setLineDash(lineStyleFn(config.scaleLineStyle));
			ctx.beginPath();
			ctx.moveTo(yAxisPosX - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft), xAxisPosY);
			ctx.lineTo(yAxisPosX + msr.availableWidth, xAxisPosY);
			ctx.stroke();
			ctx.setLineDash([]);
			
			ctx.setLineDash(lineStyleFn(config.scaleGridLineStyle));
			for (var i = ((config.showYAxisMin) ? -1 : 0); i < calculatedScale.steps; i++) {
				if (i >= 0) {
					ctx.beginPath();
					ctx.moveTo(yAxisPosX + i * valueHop, xAxisPosY + Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom));
					ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth);
					ctx.strokeStyle = config.scaleGridLineColor;
					//Check i isnt 0, so we dont go over the Y axis twice.
					if (config.scaleShowGridLines && i > 0 && i % config.scaleXGridLinesStep == 0) {
						ctx.lineTo(yAxisPosX + i * valueHop, xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop));
					} else {
						ctx.lineTo(yAxisPosX + i * valueHop, xAxisPosY);
					}
					ctx.stroke();
				}
				ctx.setLineDash([]);
			}
			//Y axis
			ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
			ctx.strokeStyle = config.scaleLineColor;
			ctx.setLineDash(lineStyleFn(config.scaleLineStyle));
			ctx.beginPath();
			ctx.moveTo(yAxisPosX, xAxisPosY + Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom));
			ctx.lineTo(yAxisPosX, xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop));
			ctx.stroke();
			ctx.setLineDash([]);
			ctx.setLineDash(lineStyleFn(config.scaleGridLineStyle));
			for (var j = 0; j < data.labels.length; j++) {
				ctx.beginPath();
				ctx.moveTo(yAxisPosX - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft), xAxisPosY - ((j + 1) * scaleHop));
				ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth);
				ctx.strokeStyle = config.scaleGridLineColor;
				if (config.scaleShowGridLines && (j+1) % config.scaleYGridLinesStep == 0) {
					ctx.lineTo(yAxisPosX + msr.availableWidth, xAxisPosY - ((j + 1) * scaleHop));
				} else {
					ctx.lineTo(yAxisPosX, xAxisPosY - ((j + 1) * scaleHop));
				}
				ctx.stroke();
			}
			ctx.setLineDash([]);
		};

		function drawLabels() {
			ctx.font = config.scaleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.scaleFontSize)).toString() + "px " + config.scaleFontFamily;
			//X axis line                                                          
			if (config.scaleShowLabels && (config.xAxisTop || config.xAxisBottom)) {
				ctx.textBaseline = "top";
				if (msr.rotateLabels > 90) {
					ctx.save();
					ctx.textAlign = "left";
				} else if (msr.rotateLabels > 0) {
					ctx.save();
					ctx.textAlign = "right";
				} else {
					ctx.textAlign = "center";
				}
				ctx.fillStyle = config.scaleFontColor;
				if (config.xAxisBottom) {
					for (var i = ((config.showYAxisMin) ? -1 : 0); i < calculatedScale.steps; i++) {
						if(showYLabels(ctx,data,config,i+1,calculatedScale.labels[i+ 1])) {				
							ctx.save();
							if (msr.rotateLabels > 0) {
								ctx.translate(yAxisPosX + (i + 1) * valueHop - msr.highestXLabel / 2, msr.xLabelPos);
								ctx.rotate(-(msr.rotateLabels * (Math.PI / 180)));
								ctx.fillTextMultiLine(calculatedScale.labels[i + 1], 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"XAXIS_TEXTMOUSE",-(msr.rotateLabels * (Math.PI / 180)),yAxisPosX + (i + 1) * valueHop - msr.highestXLabel / 2, msr.xLabelPos,i,-1);
							} else {
								ctx.fillTextMultiLine(calculatedScale.labels[i + 1], yAxisPosX + ((i + 1) * valueHop), msr.xLabelPos, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"XAXIS_TEXTMOUSE",0,0,0,i,-1);
							}
							ctx.restore();
						}
					}
				}
			}
			//Y axis
			ctx.textAlign = "right";
			ctx.textBaseline = "middle";
			for (var j = 0; j < data.labels.length; j++) {
				if(showLabels(ctx,data,config,j)){
					if (config.yAxisLeft) {
						ctx.textAlign = "right";
						ctx.fillTextMultiLine(fmtChartJS(config, data.labels[j], config.fmtXLabel), yAxisPosX - (Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - ((j + 1) * scaleHop) + Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) + additionalSpaceBetweenBars + (barWidth / 2), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YLEFTAXIS_TEXTMOUSE",0,0,0,-1,j);
					}
					if (config.yAxisRight) {
						ctx.textAlign = "left";
						ctx.fillTextMultiLine(fmtChartJS(config, data.labels[j], config.fmtXLabel), yAxisPosX + msr.availableWidth + (Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - ((j + 1) * scaleHop) + additionalSpaceBetweenBars+ (barWidth / 2), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YRIGHTAXIS_TEXTMOUSE",0,0,0,-1,j);
					}
				}
			}
		};

		function getValueBounds() {
			var upperValue = -Number.MAX_VALUE;
			var lowerValue = Number.MAX_VALUE;
			var minvl = new Array(data.datasets.length);
			var maxvl = new Array(data.datasets.length);
			for (var i = 0; i < data.datasets.length; i++) {
				for (var j = 0; j < data.datasets[i].data.length; j++) {
					var k = i;
					var tempp = 0;
					var tempn = 0;
					if (!(typeof(data.datasets[0].data[j]) == 'undefined')) {
						if(1 * data.datasets[0].data[j] > 0) {
							tempp += 1 * data.datasets[0].data[j];
							if (tempp > upperValue) {
								upperValue = tempp;
							};
							if (tempp < lowerValue) {
								lowerValue = tempp;
							};
						} else {
							tempn += 1 * data.datasets[0].data[j];
							if (tempn > upperValue) {
								upperValue = tempn;
							};
							if (tempn < lowerValue) {
								lowerValue = tempn;
							};
						}
					}
					while (k > 0) { //get max of stacked data
						if (!(typeof(data.datasets[k].data[j]) == 'undefined')) {
							if(1 * data.datasets[k].data[j] > 0) {
								tempp += 1 * data.datasets[k].data[j];
								if (tempp > upperValue) {
									upperValue = tempp;
								};
								if (tempp < lowerValue) {
									lowerValue = tempp;
								};
							} else {
								tempn += 1 * data.datasets[k].data[j];
								if (tempn > upperValue) {
									upperValue = tempn;
								};
								if (tempn < lowerValue) {
									lowerValue = tempn;
								};
							}
						}
						k--;
					}
				}
			};
			if(typeof config.graphMin=="function")lowerValue= setOptionValue(true,1,"GRAPHMIN",ctx,data,statData,undefined,config.graphMin,"graphMin",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMin)) lowerValue = config.graphMin;
			if(typeof config.graphMax=="function") upperValue= setOptionValue(true,1,"GRAPHMAX",ctx,data,statData,undefined,config.graphMax,"graphMax",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMax)) upperValue = config.graphMax;
			if(upperValue<lowerValue){upperValue=0;lowerValue=0;}
			if (Math.abs(upperValue - lowerValue) < config.zeroValue) {
				if(Math.abs(upperValue)< config.zeroValue) upperValue = .9;
				if(upperValue>0) {
					upperValue=upperValue*1.1;
					lowerValue=lowerValue*0.9;
				} else {
					upperValue=upperValue*0.9;
					lowerValue=lowerValue*1.1;
				}
			}
			labelHeight = (Math.ceil(ctx.chartTextScale*config.scaleFontSize));
			scaleHeight = msr.availableHeight;
			var maxSteps = Math.floor((scaleHeight / (labelHeight * 0.66)));
			var minSteps = Math.floor((scaleHeight / labelHeight * 0.5));
			if(upperValue<lowerValue){lowerValue=upperValue-1;}
			return {
				maxValue: upperValue,
				minValue: lowerValue,
				maxSteps: maxSteps,
				minSteps: minSteps
			};


		};
		return {
			data:data,
			config:config,
			ctx:ctx
		};
	};
	var Bar = function(data, config, ctx) {

		var maxSize, scaleHop, scaleHop2, calculatedScale, calculatedScale2, labelHeight, scaleHeight, valueBounds, labelTemplateString, labelTemplateString2, valueHop, widestXLabel, xAxisLength, yAxisPosX, xAxisPosY, barWidth, rotateLabels = 0,
			msr;
	
		ctx.tpchart="Bar";
		ctx.tpdata=0;

	        if (!init_and_start(ctx,data,config)) return;

		var statData=initPassVariableData_part1(data,config,ctx);

		var nrOfBars = data.datasets.length;
		for (var i = 0; i < data.datasets.length; i++) {
			if (data.datasets[i].type == "Line") { statData[i][0].tpchart="Line";nrOfBars--;}
			else statData[i][0].tpchart="Bar";	
		}                               


		// change the order (at first all bars then the lines) (form of BubbleSort)
		var bufferDataset, l = 0;
		
		msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, "nihil", [""], true, false, true, true, true, "Bar");
		valueBounds = getValueBounds();
		if(valueBounds.minValue<=0)config.logarithmic=false;
		if(valueBounds.maxSteps>0 && valueBounds.minSteps>0) {
			// true or fuzzy (error for negativ values (included 0))
			if (config.logarithmic !== false) {
				if (valueBounds.minValue <= 0) {
					config.logarithmic = false;
				}
			}
			if (config.logarithmic2 !== false) {
				if (valueBounds.minValue2 <= 0) {
					config.logarithmic2 = false;
				}
			}
			// Check if logarithmic is meanigful
			var OrderOfMagnitude = calculateOrderOfMagnitude(Math.pow(10, calculateOrderOfMagnitude(valueBounds.maxValue) + 1)) - calculateOrderOfMagnitude(Math.pow(10, calculateOrderOfMagnitude(valueBounds.minValue)));
			if ((config.logarithmic == 'fuzzy' && OrderOfMagnitude < 4) || config.scaleOverride) {
				config.logarithmic = false;
			}
			// Check if logarithmic is meanigful
			var OrderOfMagnitude2 = calculateOrderOfMagnitude(Math.pow(10, calculateOrderOfMagnitude(valueBounds.maxValue2) + 1)) - calculateOrderOfMagnitude(Math.pow(10, calculateOrderOfMagnitude(valueBounds.minValue2)));
			if ((config.logarithmic2 == 'fuzzy' && OrderOfMagnitude2 < 4) || config.scaleOverride2) {
				config.logarithmic2 = false;
			}

			//Check and set the scale
			labelTemplateString = (config.scaleShowLabels) ? config.scaleLabel : "";
			labelTemplateString2 = (config.scaleShowLabels2) ? config.scaleLabel2 : "";
			if (!config.scaleOverride) {
				calculatedScale = calculateScale(1, config, valueBounds.maxSteps, valueBounds.minSteps, valueBounds.maxValue, valueBounds.minValue, labelTemplateString);
			} else {
				var scaleStartValue= setOptionValue(true,1,"SCALESTARTVALUE",ctx,data,statData,undefined,config.scaleStartValue,"scaleStartValue",-1,-1,{nullValue : true} );
				var scaleSteps =setOptionValue(true,1,"SCALESTEPS",ctx,data,statData,undefined,config.scaleSteps,"scaleSteps",-1,-1,{nullValue : true} );
				var scaleStepWidth = setOptionValue(true,1,"SCALESTEPWIDTH",ctx,data,statData,undefined,config.scaleStepWidth,"scaleStepWidth",-1,-1,{nullValue : true} );

				calculatedScale = {
					steps: scaleSteps,
					stepValue: scaleStepWidth,
					graphMin: scaleStartValue,
					graphMax: scaleStartValue + scaleSteps * scaleStepWidth,
					labels: []
				}
				populateLabels(1, config, labelTemplateString, calculatedScale.labels, calculatedScale.steps, scaleStartValue, calculatedScale.graphMax, scaleStepWidth);
			}
			if (valueBounds.dbAxis) {
				if (!config.scaleOverride2) {
					calculatedScale2 = calculateScale(2, config, valueBounds.maxSteps, valueBounds.minSteps, valueBounds.maxValue2, valueBounds.minValue2, labelTemplateString2);
				} else {
					var scaleStartValue2= setOptionValue(true,1,"SCALESTARTVALUE2",ctx,data,statData,undefined,config.scaleStartValue2,"scaleStartValue2",-1,-1,{nullValue : true} );
					var scaleSteps2 =setOptionValue(true,1,"SCALESTEPS2",ctx,data,statData,undefined,config.scaleSteps2,"scaleSteps2",-1,-1,{nullValue : true} );
					var scaleStepWidth2 = setOptionValue(true,1,"SCALESTEPWIDTH2",ctx,data,statData,undefined,config.scaleStepWidth2,"scaleStepWidth2",-1,-1,{nullValue : true} );

					calculatedScale2 = {
						steps: scaleSteps2,
						stepValue: scaleStepWidth2,
						graphMin: scaleStartValue2,
						graphMax: scaleStartValue2 + scaleSteps2 * scaleStepWidth2,
						labels: []
					}
					populateLabels(2, config, labelTemplateString2, calculatedScale2.labels, calculatedScale2.steps, scaleStartValue2, calculatedScale2.graphMax, scaleStepWidth2);
				}
			} else {
				calculatedScale2 = {
					steps: 0,
					stepValue: 0,
					graphMin: 0,
					graphMax: 0,
					labels: null
				}
			}
			msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, calculatedScale2.labels, true, false, true, true, true, "Bar");

			var prevHeight=msr.availableHeight;

			msr.availableHeight = msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
			msr.availableWidth = msr.availableWidth - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft) - Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight);
			scaleHop = Math.floor(msr.availableHeight / calculatedScale.steps);
			scaleHop2 = Math.floor(msr.availableHeight / calculatedScale2.steps);
			valueHop = Math.floor(msr.availableWidth / (data.labels.length));
			if (valueHop == 0 || config.fullWidthGraph) valueHop = (msr.availableWidth / data.labels.length);
			msr.clrwidth = msr.clrwidth - (msr.availableWidth - ((data.labels.length) * valueHop));
			msr.availableWidth = (data.labels.length) * valueHop;
			msr.availableHeight = (calculatedScale.steps) * scaleHop;
			msr.xLabelPos+=(Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop) - (prevHeight-msr.availableHeight));
			msr.clrheight+=(Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop) - (prevHeight-msr.availableHeight));

			yAxisPosX = msr.leftNotUsableSize + Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft);
			xAxisPosY = msr.topNotUsableSize + msr.availableHeight + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
			barWidth = (valueHop - Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth) * 2 - (Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) * 2) - (Math.ceil(ctx.chartSpaceScale*config.barDatasetSpacing) * nrOfBars - 1) - ((Math.ceil(ctx.chartLineScale*config.barStrokeWidth) / 2) * nrOfBars - 1)) / nrOfBars;
			if(barWidth>=0 && barWidth<=1)barWidth=1;
			if(barWidth<0 && barWidth>=-1)barWidth=-1;
			var additionalSpaceBetweenBars;
			if(1*config.maxBarWidth >0 && barWidth > 1*config.maxBarWidth) {
				additionalSpaceBetweenBars=nrOfBars*(barWidth-1*config.maxBarWidth)/2;
				barWidth=1*config.maxBarWidth;
			} else additionalSpaceBetweenBars=0;

			var zeroY2 = 0;
			var zeroY = calculateOffset(config.logarithmic, 0, calculatedScale, scaleHop);
			if(typeof calculatedScale2 ==="object") zeroY2 = calculateOffset(config.logarithmic2, 0, calculatedScale2, scaleHop2);
			initPassVariableData_part2(statData,data,config,ctx,{ 
				msr: msr,
				yAxisPosX : yAxisPosX,
				xAxisPosY : xAxisPosY,
				valueHop : valueHop,
				nbValueHop : data.labels.length - 1,
				barWidth : barWidth,
				additionalSpaceBetweenBars : additionalSpaceBetweenBars,
				zeroY : zeroY,
				zeroY2 : zeroY2,
				calculatedScale : calculatedScale,
				calculatedScale2 : calculatedScale2,
				scaleHop : scaleHop,	
				scaleHop2 : scaleHop2	
			});
			drawLabels();
			animationLoop(config, drawScale, drawBars, ctx, msr.clrx, msr.clry, msr.clrwidth, msr.clrheight, yAxisPosX + msr.availableWidth / 2, xAxisPosY - msr.availableHeight / 2, yAxisPosX, xAxisPosY, data, statData);
		} else {
			testRedraw(ctx,data,config);
			ctx.firstPass=9;
		}

		function drawBars(animPc) {
			var t1, t2, t3;


			for (var i = 0; i < data.datasets.length; i++) {
				if(data.datasets[i].type=="Line") continue;
				for (var j = 0; j < data.datasets[i].data.length; j++) {
					ctx.save();
					ctx.lineWidth=Math.ceil(ctx.chartLineScale*setOptionValue(true,1,"BARSTROKEWIDTH",ctx,data,statData,data.datasets[i].barStrokeWidth,config.barStrokeWidth,"barStrokeWidth",i,j,{animationValue: currentAnimPc, xPosLeft : statData[i][j].xPosLeft, yPosBottom : statData[i][j].yPosBottom, xPosRight : statData[i][j].xPosLeft+barWidth, yPosTop : statData[i][j].yPosBottom-barHeight} ));				
					if (!(typeof(data.datasets[i].data[j]) == 'undefined')) {
						var currentAnimPc = animationCorrection(animPc, data, config, i, j, false).animVal;
						if (currentAnimPc > 1) currentAnimPc = currentAnimPc - 1;
						var barHeight = currentAnimPc * (statData[i][j].barHeight) + (Math.ceil(ctx.chartLineScale*config.barStrokeWidth) / 2);
						ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data.datasets[i].fillColor,config.defaultFillColor,"fillColor",i,j,{animationValue: currentAnimPc, xPosLeft : statData[i][j].xPosLeft, yPosBottom : statData[i][j].yPosBottom, xPosRight : statData[i][j].xPosLeft+barWidth, yPosTop : statData[i][j].yPosBottom-barHeight} );
						ctx.strokeStyle=setOptionValue(true,1,"STROKECOLOR",ctx,data,statData,data.datasets[i].strokeColor,config.defaultStrokeColor,"strokeColor",i,j,{nullvalue : null} );
						roundRect(ctx, statData[i][j].xPosLeft, statData[i][j].yPosBottom, barWidth, barHeight, config.barShowStroke, config.barBorderRadius,i,j,(data.datasets[i].data[j] < 0 ? -1  : 1));
					}
					ctx.restore();
				}
			}
			drawLinesDataset(animPc, data, config, ctx, statData,{xAxisPosY : xAxisPosY,yAxisPosX : yAxisPosX, valueHop : valueHop, nbValueHop : data.labels.length });

			if (animPc >= config.animationStopValue) {

				for (i = 0; i < data.datasets.length; i++) {
					for (j = 0; j < data.datasets[i].data.length; j++) {
						if (typeof(data.datasets[i].data[j]) == 'undefined') continue;
						if (data.datasets[i].type == "Line") continue;
						jsGraphAnnotate[ctx.ChartNewId][jsGraphAnnotate[ctx.ChartNewId].length] = ["RECT", i , j, statData,setOptionValue(true,1,"ANNOTATEDISPLAY",ctx,data,statData,data.datasets[i].annotateDisplay,config.annotateDisplay,"annotateDisplay",i,j,{nullValue : true})];
						if(setOptionValue(true,1,"INGRAPHDATASHOW",ctx,data,statData,data.datasets[i].inGraphDataShow,config.inGraphDataShow,"inGraphDataShow",i,j,{nullValue : true})) {
							ctx.save();
							ctx.textAlign = setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,j,{nullValue: true  });
							ctx.textBaseline = setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,j,{nullValue : true} );
							ctx.font = setOptionValue(true,1,"INGRAPHDATAFONTSTYLE",ctx,data,statData,undefined,config.inGraphDataFontStyle,"inGraphDataFontStyle",i,j,{nullValue : true} ) + ' ' + setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ) + 'px ' + setOptionValue(true,1,"INGRAPHDATAFONTFAMILY",ctx,data,statData,undefined,config.inGraphDataFontFamily,"inGraphDataFontFamily",i,j,{nullValue : true} );
							ctx.fillStyle = setOptionValue(true,1,"INGRAPHDATAFONTCOLOR",ctx,data,statData,undefined,config.inGraphDataFontColor,"inGraphDataFontColor",i,j,{nullValue : true} );
							t1 = statData[i][j].yPosBottom;
							t2 = statData[i][j].yPosTop;
							ctx.beginPath();
							var yPos = 0,
								xPos = 0;
							if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 1) {
								xPos = statData[i][j].xPosLeft + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 2) {
								xPos = statData[i][j].xPosLeft + barWidth / 2 + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 3) {
								xPos = statData[i][j].xPosLeft + barWidth + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							}
							if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 1) {
								yPos = statData[i][j].yPosBottom - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 2) {
								yPos = (statData[i][j].yPosBottom+statData[i][j].yPosTop)/2 - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 3) {
								yPos = statData[i][j].yPosTop - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							}
							ctx.translate(xPos, yPos);
							var dispString = tmplbis(setOptionValue(true,1,"INGRAPHDATATMPL",ctx,data,statData,undefined,config.inGraphDataTmpl,"inGraphDataTmpl",i,j,{nullValue : true} ), statData[i][j],config);
							rotateVal=setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,j,{nullValue : true} ) * (Math.PI / 180);
							ctx.rotate(rotateVal);
							setTextBordersAndBackground(ctx,dispString,setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),0,0,setOptionValue(true,1,"INGRAPHDATABORDERS",ctx,data,statData,undefined,config.inGraphDataBorders,"inGraphDataBorders",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSCOLOR",ctx,data,statData,undefined,config.inGraphDataBordersColor,"inGraphDataBordersColor",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartLineScale,"INGRAPHDATABORDERSWIDTH",ctx,data,statData,undefined,config.inGraphDataBordersWidth,"inGraphDataBordersWidth",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSXSPACE",ctx,data,statData,undefined,config.inGraphDataBordersXSpace,"inGraphDataBordersXSpace",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSYSPACE",ctx,data,statData,undefined,config.inGraphDataBordersYSpace,"inGraphDataBordersYSpace",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSSTYLE",ctx,data,statData,undefined,config.inGraphDataBordersStyle,"inGraphDataBordersStyle",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABACKGROUNDCOLOR",ctx,data,statData,undefined,config.inGraphDataBackgroundColor,"inGraphDataBackgroundColor",i,j,{nullValue : true} ),"INGRAPHDATA",config.inGraphDataBordersRadius);
							ctx.fillTextMultiLine(dispString, 0, 0, ctx.textBaseline, setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),true,config.detectMouseOnText,ctx,"INGRAPHDATA_TEXTMOUSE",rotateVal,xPos, yPos,i,j);
							ctx.restore();
						}
					}
				}
			}
			if (animPc >= 1 && typeof drawMath == "function") {
				drawMath(ctx, config, data, msr, {
					xAxisPosY: xAxisPosY,
					yAxisPosX: yAxisPosX,
					valueHop: valueHop,
					scaleHop: scaleHop,
					zeroY: zeroY,
					calculatedScale: calculatedScale,
					calculateOffset: calculateOffset,
					additionalSpaceBetweenBars : additionalSpaceBetweenBars,
					barWidth: barWidth
				});
			}
			if(msr.legendMsr.dispLegend)drawLegend(msr.legendMsr,data,config,ctx,"Bar");
		};

		function roundRect(ctx, x, y, w, h, stroke, radius,i,j,fact) {
			ctx.beginPath();
			ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"STROKESTYLE",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
			ctx.moveTo(x + (w/2), y);
			ctx.lineTo(x + w , y);
			ctx.lineTo(x + w, y - h + fact*radius);
			ctx.quadraticCurveTo(x + w, y - h, x + w - radius, y - h);
			ctx.lineTo(x + radius, y - h);
			ctx.quadraticCurveTo(x, y - h, x, y - h + fact*radius);
			ctx.lineTo(x, y);
			ctx.lineTo(x + (w/2), y);
			if (stroke) ctx.stroke();
			ctx.closePath();
			ctx.fill();
			ctx.setLineDash([]);
		};

		function drawScale() {
			//X axis line                                                          
			ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
			ctx.strokeStyle = config.scaleLineColor;
			ctx.setLineDash(lineStyleFn(config.scaleLineStyle));

			ctx.beginPath();
			ctx.moveTo(yAxisPosX - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft), xAxisPosY);
			ctx.lineTo(yAxisPosX + msr.availableWidth + Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight), xAxisPosY);
			ctx.stroke();
			ctx.setLineDash([]);
			
			ctx.setLineDash(lineStyleFn(config.scaleGridLineStyle));
			for (var i = 0; i < data.labels.length; i++) {
				ctx.beginPath();
				ctx.moveTo(yAxisPosX + i * valueHop, xAxisPosY + Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom));
				ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth);
				ctx.strokeStyle = config.scaleGridLineColor;
				//Check i isnt 0, so we dont go over the Y axis twice.
				if (config.scaleShowGridLines && i > 0 && i % config.scaleXGridLinesStep == 0) {
					ctx.lineTo(yAxisPosX + i * valueHop, xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop));
				} else {
					ctx.lineTo(yAxisPosX + i * valueHop, xAxisPosY);
				}
				ctx.stroke();
			}
			ctx.setLineDash([]);
			
			//Y axis
			ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
			ctx.strokeStyle = config.scaleLineColor;
			ctx.setLineDash(lineStyleFn(config.scaleLineStyle));
			ctx.beginPath();
			ctx.moveTo(yAxisPosX, xAxisPosY + Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom));
			ctx.lineTo(yAxisPosX, xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop));
			ctx.stroke();
			ctx.setLineDash([]);
			
			ctx.setLineDash(lineStyleFn(config.scaleGridLineStyle));
			for (var j = 0; j < calculatedScale.steps; j++) {
				ctx.beginPath();
				ctx.moveTo(yAxisPosX - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft), xAxisPosY - ((j + 1) * scaleHop));
				ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth);
				ctx.strokeStyle = config.scaleGridLineColor;
				if (config.scaleShowGridLines && (j+1) % config.scaleYGridLinesStep == 0) {
					ctx.lineTo(yAxisPosX + msr.availableWidth + Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight), xAxisPosY - ((j + 1) * scaleHop));
				} else {
					ctx.lineTo(yAxisPosX, xAxisPosY - ((j + 1) * scaleHop));
				}
				ctx.stroke();
			}
			ctx.setLineDash([]);
		};

		function drawLabels() {
			ctx.font = config.scaleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.scaleFontSize)).toString() + "px " + config.scaleFontFamily;
			//X axis line                                                          
			if (config.xAxisTop || config.xAxisBottom) {
				ctx.textBaseline = "top";
				if (msr.rotateLabels > 90) {
					ctx.save();
					ctx.textAlign = "left";
				} else if (msr.rotateLabels > 0) {
					ctx.save();
					ctx.textAlign = "right";
				} else {
					ctx.textAlign = "center";
				}
				ctx.fillStyle = config.scaleFontColor;
				if (config.xAxisBottom) {
					for (var i = 0; i < data.labels.length; i++) {
						if(showLabels(ctx,data,config,i)){
							ctx.save();
							if (msr.rotateLabels > 0) {
								ctx.translate(yAxisPosX + i * valueHop + (valueHop / 2) - msr.highestXLabel / 2, msr.xLabelPos);
								ctx.rotate(-(msr.rotateLabels * (Math.PI / 180)));
								ctx.fillTextMultiLine(fmtChartJS(config, data.labels[i], config.fmtXLabel), 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"XAXIS_TEXTMOUSE",-(msr.rotateLabels * (Math.PI / 180)),yAxisPosX + i * valueHop + (valueHop / 2) - msr.highestXLabel / 2, msr.xLabelPos,i,-1);
							} else {
								ctx.fillTextMultiLine(fmtChartJS(config, data.labels[i], config.fmtXLabel), yAxisPosX + i * valueHop + (valueHop / 2), msr.xLabelPos, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"XAXIS_TEXTMOUSE",0,0,0,i,-1);
							}
							ctx.restore();
						}
					}
				}
			}
			//Y axis
			ctx.textAlign = "right";
			ctx.textBaseline = "middle";
			for (var j = ((config.showYAxisMin) ? -1 : 0); j < calculatedScale.steps; j++) {
				if (config.scaleShowLabels) {
					if(showYLabels(ctx,data,config,j+1,calculatedScale.labels[j+ 1])) {				
						if (config.yAxisLeft) {
							ctx.textAlign = "right";
							ctx.fillTextMultiLine(calculatedScale.labels[j + 1], yAxisPosX - (Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - ((j + 1) * scaleHop), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YLEFTAXIS_TEXTMOUSE",0,0,0,-1,j);
						}
						if (config.yAxisRight && !valueBounds.dbAxis) {
							ctx.textAlign = "left";
							ctx.fillTextMultiLine(calculatedScale.labels[j + 1], yAxisPosX + msr.availableWidth + (Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - ((j + 1) * scaleHop), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YRIGHTAXIS_TEXTMOUSE",0,0,0,-1,j);
						}
					}
				}
			}
			if (config.yAxisRight && valueBounds.dbAxis) {
				for (j = ((config.showYAxisMin) ? -1 : 0); j < calculatedScale2.steps; j++) {
					if (config.scaleShowLabels) {
						ctx.textAlign = "left";
						ctx.fillTextMultiLine(calculatedScale2.labels[j + 1], yAxisPosX + msr.availableWidth + (Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - ((j + 1) * scaleHop2), ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YRIGHTAXIS_TEXTMOUSE",0,0,0,-1,j);
					}
				}
			}
		};

		function getValueBounds() {
			var upperValue = -Number.MAX_VALUE;
			var lowerValue = Number.MAX_VALUE;
			var upperValue2 = -Number.MAX_VALUE;
			var lowerValue2 = Number.MAX_VALUE;
			var secondAxis = false;
			var firstAxis = false;
			var mathValueHeight;			
			for (var i = 0; i < data.datasets.length; i++) {
				var mathFctName = data.datasets[i].drawMathDeviation;
				var mathValueHeight = 0;
				if (typeof eval(mathFctName) == "function") {
					var parameter = {
						data: data,
						datasetNr: i
					};
					mathValueHeightVal = window[mathFctName](parameter);
				} else mathValueHeightVal=0;
				for (var j = 0; j < data.datasets[i].data.length; j++) {
					if(typeof mathValueHeightVal=="object") mathValueHeight=mathValueHeightVal[Math.min(mathValueHeightVal.length,j)];
					else mathValueHeight=mathValueHeightVal;
					if(typeof data.datasets[i].data[j]=="undefined")continue;
					if (data.datasets[i].axis == 2) {
						secondAxis = true;
						if (1 * data.datasets[i].data[j] + mathValueHeight > upperValue2) {
							upperValue2 = 1 * data.datasets[i].data[j] + mathValueHeight;
						};
						if (1 * data.datasets[i].data[j] - mathValueHeight < lowerValue2) {
							lowerValue2 = 1 * data.datasets[i].data[j] - mathValueHeight;
						};
					} else {
						firstAxis=true;
						if (1 * data.datasets[i].data[j] + mathValueHeight > upperValue) {
							upperValue = 1 * data.datasets[i].data[j] + mathValueHeight;
						};
						if (1 * data.datasets[i].data[j] - mathValueHeight < lowerValue) {
							lowerValue = 1 * data.datasets[i].data[j] - mathValueHeight;
						};
					}
				}
			};
			if(upperValue<lowerValue){upperValue=0;lowerValue=0;}
			if (Math.abs(upperValue - lowerValue) < config.zeroValue) {
				if(Math.abs(upperValue)< config.zeroValue) upperValue = .9;
				if(upperValue>0) {
					upperValue=upperValue*1.1;
					lowerValue=lowerValue*0.9;
				} else {
					upperValue=upperValue*0.9;
					lowerValue=lowerValue*1.1;
				}
			}
			if(typeof config.graphMin=="function")lowerValue= setOptionValue(true,1,"GRAPHMIN",ctx,data,statData,undefined,config.graphMin,"graphMin",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMin)) lowerValue = config.graphMin;
			if(typeof config.graphMax=="function") upperValue= setOptionValue(true,1,"GRAPHMAX",ctx,data,statData,undefined,config.graphMax,"graphMax",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMax)) upperValue = config.graphMax;

			if (secondAxis) {
				if(upperValue2<lowerValue2){upperValue2=0;lowerValue2=0;}
				if (Math.abs(upperValue2 - lowerValue2) < config.zeroValue) {
					if(Math.abs(upperValue2)< config.zeroValue) upperValue2 = .9;
					if(upperValue2>0) {
						upperValue2=upperValue2*1.1;
						lowerValue2=lowerValue2*0.9;
					} else {
						upperValue2=upperValue2*0.9;
						lowerValue2=lowerValue2*1.1;
					}
				}
				if(typeof config.graphMin2=="function")lowerValue2= setOptionValue(true,1,"GRAPHMIN",ctx,data,statData,undefined,config.graphMin2,"graphMin2",-1,-1,{nullValue : true})
				else if (!isNaN(config.graphMin2)) lowerValue2 = config.graphMin2;
				if(typeof config.graphMax2=="function") upperValue2= setOptionValue(true,1,"GRAPHMAX",ctx,data,statData,undefined,config.graphMax2,"graphMax2",-1,-1,{nullValue : true})
				else if (!isNaN(config.graphMax2)) upperValue2 = config.graphMax2;
			}
			if (!firstAxis && secondAxis) {
				upperValue = upperValue2;
				lowerValue = lowerValue2;
			}

			labelHeight = (Math.ceil(ctx.chartTextScale*config.scaleFontSize));
			scaleHeight = msr.availableHeight;
			var maxSteps = Math.floor((scaleHeight / (labelHeight * 0.66)));
			var minSteps = Math.floor((scaleHeight / labelHeight * 0.5));
			if(upperValue<lowerValue){lowerValue=upperValue-1;}
			if(upperValue2<lowerValue2){lowerValue2=upperValue2-1;}
			return {
				maxValue: upperValue,
				minValue: lowerValue,
				maxValue2: upperValue2,
				minValue2: lowerValue2,
				dbAxis: secondAxis,
				maxSteps: maxSteps,
				minSteps: minSteps
			};
		};

		return {
			data:data,
			config:config,
			ctx:ctx
		};
	};

	var HorizontalBar = function(data, config, ctx) {
		var maxSize, scaleHop, calculatedScale, labelHeight, scaleHeight, valueBounds, labelTemplateString, valueHop, widestXLabel, xAxisLength, yAxisPosX, xAxisPosY, barWidth, rotateLabels = 0,
			msr;
		ctx.tpchart="HorizontalBar";
		ctx.tpdata=0;

	        if (!init_and_start(ctx,data,config)) return;

		if (config.reverseOrder && typeof ctx.reversed == "undefined") {
			ctx.reversed=true;
			data = reverseData(data);
		}

		var statData=initPassVariableData_part1(data,config,ctx);

		msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, "nihil", [""], true, true, true, true, true, "StackedBar");
		valueBounds = getValueBounds();
		if(valueBounds.minValue<=0)config.logarithmic=false;
		if(valueBounds.maxSteps>0 && valueBounds.minSteps>0) {
			if (config.logarithmic !== false) {
				if (valueBounds.minValue <= 0) {
					config.logarithmic = false;
				}
			}
			//Check and set the scale
			labelTemplateString = (config.scaleShowLabels) ? config.scaleLabel : "";
			if (!config.scaleOverride) {
				calculatedScale = calculateScale(1, config, valueBounds.maxSteps, valueBounds.minSteps, valueBounds.maxValue, valueBounds.minValue, labelTemplateString);
				msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, null, true, true, true, true, true, "HorizontalBar");
			} else {
				var scaleStartValue= setOptionValue(true,1,"SCALESTARTVALUE",ctx,data,statData,undefined,config.scaleStartValue,"scaleStartValue",-1,-1,{nullValue : true} );
				var scaleSteps =setOptionValue(true,1,"SCALESTEPS",ctx,data,statData,undefined,config.scaleSteps,"scaleSteps",-1,-1,{nullValue : true} );
				var scaleStepWidth = setOptionValue(true,1,"SCALESTEPWIDTH",ctx,data,statData,undefined,config.scaleStepWidth,"scaleStepWidth",-1,-1,{nullValue : true} );

				calculatedScale = {
					steps: scaleSteps,
					stepValue: scaleStepWidth,
					graphMin: scaleStartValue,
					graphMax: scaleStartValue + scaleSteps * scaleStepWidth,
					labels: []
				}
				populateLabels(1, config, labelTemplateString, calculatedScale.labels, calculatedScale.steps, scaleStartValue, calculatedScale.graphMax, scaleStepWidth);
				msr = setMeasures(data, config, ctx, ctx.canvas.height, ctx.canvas.width, calculatedScale.labels, null, true, true, true, true, true, "HorizontalBar");
			}
			msr.availableHeight = msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom) - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
			msr.availableWidth = msr.availableWidth - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft) - Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight);
			scaleHop = Math.floor(msr.availableHeight / data.labels.length);
			valueHop = Math.floor(msr.availableWidth / (calculatedScale.steps));
			if (valueHop == 0 || config.fullWidthGraph) valueHop = (msr.availableWidth / calculatedScale.steps);
			msr.clrwidth = msr.clrwidth - (msr.availableWidth - (calculatedScale.steps * valueHop));
			msr.availableWidth = (calculatedScale.steps) * valueHop;
			msr.availableHeight = (data.labels.length) * scaleHop;
			yAxisPosX = msr.leftNotUsableSize + Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft);
			xAxisPosY = msr.topNotUsableSize + msr.availableHeight + Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop);
			barWidth = (scaleHop - Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth) * 2 - (Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) * 2) - (Math.ceil(ctx.chartSpaceScale*config.barDatasetSpacing) * data.datasets.length - 1) - ((Math.ceil(ctx.chartLineScale*config.barStrokeWidth) / 2) * data.datasets.length - 1)) / data.datasets.length;
			if(barWidth>=0 && barWidth<=1)barWidth=1;
			if(barWidth<0 && barWidth>=-1)barWidth=-1;
			var additionalSpaceBetweenBars;
			if(1*config.maxBarWidth >0 && barWidth > 1*config.maxBarWidth) {
				additionalSpaceBetweenBars=data.datasets.length*(barWidth-1*config.maxBarWidth)/2;
				barWidth=1*config.maxBarWidth;
			} else additionalSpaceBetweenBars=0;

			var zeroY = 0;
			zeroY = calculateOffset(config.logarithmic, 0, calculatedScale, valueHop);
			drawLabels();
			initPassVariableData_part2(statData,data,config,ctx,{ 
				yAxisPosX : yAxisPosX,
				xAxisPosY : xAxisPosY,
				barWidth : barWidth,
				additionalSpaceBetweenBars : additionalSpaceBetweenBars,
				zeroY : zeroY,
				scaleHop : scaleHop,
				valueHop : valueHop,
				calculatedScale : calculatedScale
			});
			animationLoop(config, drawScale, drawBars, ctx, msr.clrx, msr.clry, msr.clrwidth, msr.clrheight, yAxisPosX + msr.availableWidth / 2, xAxisPosY - msr.availableHeight / 2, yAxisPosX, xAxisPosY, data, statData);
		} else {
			testRedraw(ctx,data,config);
			ctx.firstPass=9;
		}

		function drawBars(animPc) {
			for (var i = 0; i < data.datasets.length; i++) {
				for (var j = 0; j < data.datasets[i].data.length; j++) {
//					ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.barStrokeWidth);
ctx.lineWidth=Math.ceil(ctx.chartLineScale*setOptionValue(true,1,"BARSTROKEWIDTH",ctx,data,statData,data.datasets[i].barStrokeWidth,config.barStrokeWidth,"barStrokeWidth",i,j,{animationValue: currentAnimPc, xPosLeft : statData[i][j].xPosLeft, yPosBottom : statData[i][j].yPosBottom, xPosRight : statData[i][j].xPosLeft+barWidth, yPosTop : statData[i][j].yPosBottom-barHeight} ));				
					var currentAnimPc = animationCorrection(animPc, data, config, i, j, false).animVal;
					if (currentAnimPc > 1) currentAnimPc = currentAnimPc - 1;
					var barHeight = currentAnimPc * statData[i][j].barWidth + (Math.ceil(ctx.chartLineScale*config.barStrokeWidth) / 2);
					ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data.datasets[i].fillColor,config.defaultFillColor,"fillColor",i,j,{animationValue: currentAnimPc, xPosLeft : statData[i][j].xPosLeft, yPosBottom : statData[i][j].yPosBottom, xPosRight : statData[i][j].xPosLeft+barHeight, yPosTop : statData[i][j].yPosBottom} );
					ctx.strokeStyle=setOptionValue(true,1,"STROKECOLOR",ctx,data,statData,data.datasets[i].strokeColor,config.defaultStrokeColor,"strokeColor",i,j,{nullvalue : null} );

					if (!(typeof(data.datasets[i].data[j]) == 'undefined')) {
						roundRect(ctx, statData[i][j].yPosTop, statData[i][j].xPosLeft , barWidth, barHeight, config.barShowStroke, config.barBorderRadius, 0,i,j,(data.datasets[i].data[j] < 0 ? -1  : 1));
					}
				}
			}
			if (animPc >= config.animationStopValue) {
				for (i = 0; i < data.datasets.length; i++) {
					for (j = 0; j < data.datasets[i].data.length; j++) {
						if (typeof(data.datasets[i].data[j]) == 'undefined') continue;
						jsGraphAnnotate[ctx.ChartNewId][jsGraphAnnotate[ctx.ChartNewId].length] = ["RECT", i ,j ,statData,setOptionValue(true,1,"ANNOTATEDISPLAY",ctx,data,statData,data.datasets[i].annotateDisplay,config.annotateDisplay,"annotateDisplay",i,j,{nullValue : true})];
                       				if(setOptionValue(true,1,"INGRAPHDATASHOW",ctx,data,statData,data.datasets[i].inGraphDataShow,config.inGraphDataShow,"inGraphDataShow",i,j,{nullValue : true}))  {
							ctx.save();
							ctx.textAlign = setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,j,{nullValue: true  });
							ctx.textBaseline = setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,j,{nullValue : true} );
							ctx.font = setOptionValue(true,1,"INGRAPHDATAFONTSTYLE",ctx,data,statData,undefined,config.inGraphDataFontStyle,"inGraphDataFontStyle",i,j,{nullValue : true} ) + ' ' + setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ) + 'px ' + setOptionValue(true,1,"INGRAPHDATAFONTFAMILY",ctx,data,statData,undefined,config.inGraphDataFontFamily,"inGraphDataFontFamily",i,j,{nullValue : true} );
							ctx.fillStyle = setOptionValue(true,1,"INGRAPHDATAFONTCOLOR",ctx,data,statData,undefined,config.inGraphDataFontColor,"inGraphDataFontColor",i,j,{nullValue : true} );
							ctx.beginPath();
							var yPos = 0,
								xPos = 0;
							if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 1) {
								yPos = statData[i][j].yPosTop - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} ) + barWidth;
							} else if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 2) {
								yPos = statData[i][j].yPosTop + barWidth / 2 - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAYPOSITION",ctx,data,statData,undefined,config.inGraphDataYPosition,"inGraphDataYPosition",i,j,{nullValue : true} ) == 3) {
								yPos = statData[i][j].yPosTop - setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							}
							if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 1) {
								xPos = statData[i][j].xPosLeft + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 2) {
								xPos = (statData[i][j].xPosLeft+statData[i][j].xPosRight)/2 + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							} else if (setOptionValue(true,1,"INGRAPHDATAXPOSITION",ctx,data,statData,undefined,config.inGraphDataXPosition,"inGraphDataXPosition",i,j,{nullValue : true} ) == 3) {
								xPos = statData[i][j].xPosRight + setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} );
							}
							ctx.translate(xPos, yPos);
							var dispString = tmplbis(setOptionValue(true,1,"INGRAPHDATATMPL",ctx,data,statData,undefined,config.inGraphDataTmpl,"inGraphDataTmpl",i,j,{nullValue : true} ), statData[i][j],config);
							var rotateVal=setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,j,{nullValue : true} ) * (Math.PI / 180);
							ctx.rotate(rotateVal);
							setTextBordersAndBackground(ctx,dispString,setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),0,0,setOptionValue(true,1,"INGRAPHDATABORDERS",ctx,data,statData,undefined,config.inGraphDataBorders,"inGraphDataBorders",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSCOLOR",ctx,data,statData,undefined,config.inGraphDataBordersColor,"inGraphDataBordersColor",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartLineScale,"INGRAPHDATABORDERSWIDTH",ctx,data,statData,undefined,config.inGraphDataBordersWidth,"inGraphDataBordersWidth",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSXSPACE",ctx,data,statData,undefined,config.inGraphDataBordersXSpace,"inGraphDataBordersXSpace",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSYSPACE",ctx,data,statData,undefined,config.inGraphDataBordersYSpace,"inGraphDataBordersYSpace",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSSTYLE",ctx,data,statData,undefined,config.inGraphDataBordersStyle,"inGraphDataBordersStyle",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABACKGROUNDCOLOR",ctx,data,statData,undefined,config.inGraphDataBackgroundColor,"inGraphDataBackgroundColor",i,j,{nullValue : true} ),"INGRAPHDATA",config.inGraphDataBordersRadius);
							ctx.fillTextMultiLine(dispString, 0, 0, ctx.textBaseline, setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),true,config.detectMouseOnText,ctx,"INGRAPHDATA_TEXTMOUSE",rotateVal,xPos, yPos,i,j);
							ctx.restore();
						}
					}
				}
			}
			if(msr.legendMsr.dispLegend)drawLegend(msr.legendMsr,data,config,ctx,"HorizontalBar");
		};

		function roundRect(ctx, x, y, w, h, stroke, radius, zeroY,i,j,fact) {
			ctx.beginPath();
			ctx.moveTo(y + zeroY, x );
			ctx.lineTo(y + zeroY, x + w);
			ctx.lineTo(y + h - fact*radius, x + w);
			ctx.quadraticCurveTo(y + h, x + w, y + h, x + w - radius);
			ctx.lineTo(y + h, x + radius);
			ctx.quadraticCurveTo(y + h, x, y + h - fact*radius, x);
			ctx.lineTo(y + zeroY, x);
			ctx.quadraticCurveTo(y + zeroY, x, y + zeroY, x + w);

			if (stroke) { 
				ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"STROKESTYLE",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
				ctx.stroke();
				ctx.setLineDash([]);
			};
			ctx.closePath();
			ctx.fill();
		};

		function drawScale() {
			//X axis line                                                          
			ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
			ctx.strokeStyle = config.scaleLineColor;
			ctx.setLineDash(lineStyleFn(config.scaleLineStyle));
			ctx.beginPath();
			ctx.moveTo(yAxisPosX - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft), xAxisPosY);
			ctx.lineTo(yAxisPosX + msr.availableWidth + Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight), xAxisPosY);
			ctx.stroke();
			ctx.setLineDash([]);
			
			ctx.setLineDash(lineStyleFn(config.scaleGridLineStyle));
			for (var i = ((config.showYAxisMin) ? -1 : 0); i < calculatedScale.steps; i++) {
				if (i >= 0) {
					ctx.beginPath();
					ctx.moveTo(yAxisPosX + i * valueHop, xAxisPosY + Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom));
					ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth);
					ctx.strokeStyle = config.scaleGridLineColor;
					//Check i isnt 0, so we dont go over the Y axis twice.
					if (config.scaleShowGridLines && i > 0 && i % config.scaleXGridLinesStep == 0) {
						ctx.lineTo(yAxisPosX + i * valueHop, xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop));
					} else {
						ctx.lineTo(yAxisPosX + i * valueHop, xAxisPosY);
					}
					ctx.stroke();
				}

			}
			ctx.setLineDash([]);
			//Y axis
			ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleLineWidth);
			ctx.strokeStyle = config.scaleLineColor;
			ctx.setLineDash(lineStyleFn(config.scaleLineStyle));
			ctx.beginPath();
			ctx.moveTo(yAxisPosX, xAxisPosY + Math.ceil(ctx.chartLineScale*config.scaleTickSizeBottom));
			ctx.lineTo(yAxisPosX, xAxisPosY - msr.availableHeight - Math.ceil(ctx.chartLineScale*config.scaleTickSizeTop));
			ctx.stroke();
			ctx.setLineDash([]);
			ctx.setLineDash(lineStyleFn(config.scaleGridLineStyle));
			for (var j = 0; j < data.labels.length; j++) {
				ctx.beginPath();
				ctx.moveTo(yAxisPosX - Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft), xAxisPosY - ((j + 1) * scaleHop));
				ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.scaleGridLineWidth);
				ctx.strokeStyle = config.scaleGridLineColor;
				if (config.scaleShowGridLines && (j+1) % config.scaleYGridLinesStep == 0) {
					ctx.lineTo(yAxisPosX + msr.availableWidth + Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight), xAxisPosY - ((j + 1) * scaleHop));
				} else {
					ctx.lineTo(yAxisPosX, xAxisPosY - ((j + 1) * scaleHop));
				}
				ctx.stroke();
			}
			ctx.setLineDash([]);
		};

		function drawLabels() {
			ctx.font = config.scaleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.scaleFontSize)).toString() + "px " + config.scaleFontFamily;
			//X axis line                                                          
			if (config.scaleShowLabels && (config.xAxisTop || config.xAxisBottom)) {
				ctx.textBaseline = "top";
				if (msr.rotateLabels > 90) {
					ctx.save();
					ctx.textAlign = "left";
				} else if (msr.rotateLabels > 0) {
					ctx.save();
					ctx.textAlign = "right";
				} else {
					ctx.textAlign = "center";
				}
				ctx.fillStyle = config.scaleFontColor;
				if (config.xAxisBottom) {
					for (var i = ((config.showYAxisMin) ? -1 : 0); i < calculatedScale.steps; i++) {
						if(showYLabels(ctx,data,config,i+1,calculatedScale.labels[i+ 1])) {				
							ctx.save();
							if (msr.rotateLabels > 0) {
								ctx.translate(yAxisPosX + (i + 1) * valueHop - msr.highestXLabel / 2, msr.xLabelPos);
								ctx.rotate(-(msr.rotateLabels * (Math.PI / 180)));
								ctx.fillTextMultiLine(calculatedScale.labels[i + 1], 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"XAXIS_TEXTMOUSE",-(msr.rotateLabels * (Math.PI / 180)),yAxisPosX + (i + 1) * valueHop - msr.highestXLabel / 2, msr.xLabelPos,i,-1);
							} else {
								ctx.fillTextMultiLine(calculatedScale.labels[i + 1], yAxisPosX + (i + 1) * valueHop, msr.xLabelPos, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"XAXIS_TEXTMOUSE",0,0,0,i,-1);
							}
							ctx.restore();
						}
					}
				}
			}
			//Y axis
			ctx.textAlign = "right";
			ctx.textBaseline = "middle";
			for (var j = 0; j < data.labels.length; j++) {
				if(showLabels(ctx,data,config,j)){
					if (config.yAxisLeft) {
						ctx.textAlign = "right";
						ctx.fillTextMultiLine(fmtChartJS(config, data.labels[j], config.fmtXLabel), yAxisPosX - (Math.ceil(ctx.chartLineScale*config.scaleTickSizeLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - (j * scaleHop) - scaleHop / 2, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YLEFTAXIS_TEXTMOUSE",0,0,0,-1,j);
					}
					if (config.yAxisRight) {
						ctx.textAlign = "left";
						ctx.fillTextMultiLine(fmtChartJS(config, data.labels[j], config.fmtXLabel), yAxisPosX + msr.availableWidth + (Math.ceil(ctx.chartLineScale*config.scaleTickSizeRight) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight)), xAxisPosY - (j * scaleHop) - scaleHop / 2, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.scaleFontSize)),true,config.detectMouseOnText,ctx,"YRIGHTAXIS_TEXTMOUSE",0,0,0,-1,j);
					}
				}
			}
		};

		function getValueBounds() {
			var upperValue = -Number.MAX_VALUE;
			var lowerValue = Number.MAX_VALUE;
			for (var i = 0; i < data.datasets.length; i++) {
				for (var j = 0; j < data.datasets[i].data.length; j++) {
					if(typeof data.datasets[i].data[j]=="undefined")continue;
					if (1 * data.datasets[i].data[j] > upperValue) {
						upperValue = 1 * data.datasets[i].data[j]
					};
					if (1 * data.datasets[i].data[j] < lowerValue) {
						lowerValue = 1 * data.datasets[i].data[j]
					};
				}
			};
			if(upperValue<lowerValue){upperValue=0;lowerValue=0;}
			if (Math.abs(upperValue - lowerValue) < config.zeroValue) {
				if(Math.abs(upperValue)< config.zeroValue) upperValue = .9;
				if(upperValue>0) {
					upperValue=upperValue*1.1;
					lowerValue=lowerValue*0.9;
				} else {
					upperValue=upperValue*0.9;
					lowerValue=lowerValue*1.1;
				}
			}
			// AJOUT CHANGEMENT
			if(typeof config.graphMin=="function")lowerValue= setOptionValue(true,1,"GRAPHMIN",ctx,data,statData,undefined,config.graphMin,"graphMin",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMin)) lowerValue = config.graphMin;                                                            
			if(typeof config.graphMax=="function") upperValue= setOptionValue(true,1,"GRAPHMAX",ctx,data,statData,undefined,config.graphMax,"graphMax",-1,-1,{nullValue : true})
			else if (!isNaN(config.graphMax)) upperValue = config.graphMax;

			labelHeight = (Math.ceil(ctx.chartTextScale*config.scaleFontSize));
			scaleHeight = msr.availableHeight;

			var maxSteps = Math.floor((scaleHeight / (labelHeight * 0.66)));
			var minSteps = Math.floor((scaleHeight / labelHeight * 0.5));
			if(upperValue<lowerValue){lowerValue=upperValue-1;}
			return {
				maxValue: upperValue,
				minValue: lowerValue,
				maxSteps: maxSteps,
				minSteps: minSteps
			};
		};

		return {
			data:data,
			config:config,
			ctx:ctx
		};
	};

	function calculateOffset(logarithmic, val, calculatedScale, scaleHop) {
		if (!logarithmic) { // no logarithmic scale
			var outerValue = calculatedScale.steps * calculatedScale.stepValue;
			var adjustedValue = val - calculatedScale.graphMin;
			var scalingFactor = CapValue(adjustedValue / outerValue, 1, 0);
			return (scaleHop * calculatedScale.steps) * scalingFactor;
		} else { // logarithmic scale
//			return CapValue(log10(val) * scaleHop - calculateOrderOfMagnitude(calculatedScale.graphMin) * scaleHop, undefined, 0);
			return CapValue(log10(val) * scaleHop - log10(calculatedScale.graphMin) * scaleHop, undefined, 0);
		}
	};

	function animationLoop(config, drawScale, drawData, ctx, clrx, clry, clrwidth, clrheight, midPosX, midPosY, borderX, borderY, data, statData) {
		var cntiter = 0;
		var animationCount = 1;
		var multAnim = 1;
		if (config.animationStartValue < 0 || config.animationStartValue > 1) config.animation.StartValue = 0;
		if (config.animationStopValue < 0 || config.animationStopValue > 1) config.animation.StopValue = 1;
		if (config.animationStopValue < config.animationStartValue) config.animationStopValue = config.animationStartValue;
		if (isIE() < 9 && isIE() != false) config.animation = false;
		var animFrameAmount = (config.animation) ? 1 / CapValue(config.animationSteps, Number.MAX_VALUE, 1) : 1,
			easingFunction = animationOptions[config.animationEasing],
			percentAnimComplete = (config.animation) ? 0 : 1;
		if (config.animation && config.animationStartValue > 0 && config.animationStartValue <= 1) {
			while (percentAnimComplete < config.animationStartValue) {
				cntiter++;
				percentAnimComplete += animFrameAmount;
			}
		}
		var beginAnim = cntiter;
		var beginAnimPct = percentAnimComplete;
		if (typeof drawScale !== "function") drawScale = function() {};
		if (config.clearRect) {
			if(config.animationForceSetTimeOut)requestAnimFrameSetTimeOut(animLoop);
			else requestAnimFrame(animLoop);
		} else animLoop();

		function animateFrame() {
			var easeAdjustedAnimationPercent = (config.animation) ? CapValue(easingFunction(percentAnimComplete), null, 0) : 1;
			if (1 * cntiter >= 1 * CapValue(config.animationSteps, Number.MAX_VALUE, 1) || config.animation == false || ctx.firstPass%10!=1) easeAdjustedAnimationPercent = 1;
			else if (easeAdjustedAnimationPercent >= 1) easeAdjustedAnimationPercent = 0.9999;
			if (config.animation && !(isIE() < 9 && isIE() != false) && config.clearRect) {
				ctx.clearRect(clrx, clry, clrwidth, clrheight);
			}
			dispCrossImage(ctx, config, midPosX, midPosY, borderX, borderY, false, data, easeAdjustedAnimationPercent, cntiter);
			dispCrossText(ctx, config, midPosX, midPosY, borderX, borderY, false, data, easeAdjustedAnimationPercent, cntiter);
			if(typeof config.beforeDrawFunction == "function") config.beforeDrawFunction("BEFOREDRAWFUNCTION",ctx,data,statData,-1,-1,{animationValue : easeAdjustedAnimationPercent, cntiter: cntiter, config : config, borderX : borderX, borderY : borderY, midPosX : midPosX, midPosY : midPosY});
			if (config.scaleOverlay) {
				drawData(easeAdjustedAnimationPercent);
				if(typeof config.endDrawDataFunction == "function")config.endDrawDataFunction("ENDDATAFUNCTION",ctx,data,statData,-1,-1,{animationValue : easeAdjustedAnimationPercent, cntiter: cntiter, config : config, borderX : borderX, borderY : borderY, midPosX : midPosX, midPosY : midPosY});
				drawScale();
				if(typeof config.endDrawScaleFunction == "function")config.endDrawScaleFunction("ENDSCALEFUNCTION",ctx,data,statData,-1,-1,{animationValue : easeAdjustedAnimationPercent, cntiter: cntiter, config : config, borderX : borderX, borderY : borderY, midPosX : midPosX, midPosY : midPosY});
			} else {
				drawScale();
				if(typeof config.endDrawScaleFunction == "function")config.endDrawScaleFunction("ENDSCALEFUNCTION",ctx,data,statData,-1,-1,{animationValue : easeAdjustedAnimationPercent, cntiter: cntiter, config : config, borderX : borderX, borderY : borderY, midPosX : midPosX, midPosY : midPosY});
				drawData(easeAdjustedAnimationPercent);
				if(typeof config.endDrawDataFunction == "function")config.endDrawDataFunction("ENDDATAFUNCTION",ctx,data,statData,-1,-1,{animationValue : easeAdjustedAnimationPercent, cntiter: cntiter, config : config, borderX : borderX, borderY : borderY, midPosX : midPosX, midPosY : midPosY});
			}
			dispCrossImage(ctx, config, midPosX, midPosY, borderX, borderY, true, data, easeAdjustedAnimationPercent, cntiter);
			dispCrossText(ctx, config, midPosX, midPosY, borderX, borderY, true, data, easeAdjustedAnimationPercent, cntiter);
		};

		function animLoop() {
			//We need to check if the animation is incomplete (less than 1), or complete (1).
			cntiter += multAnim;
			percentAnimComplete += multAnim * animFrameAmount;
			if (cntiter == config.animationSteps || config.animation == false || ctx.firstPass%10!=1) percentAnimComplete = 1;
			else if (percentAnimComplete >= 1) percentAnimComplete = 0.999;
			animateFrame();
			//Stop the loop continuing forever
			if (multAnim == -1 && cntiter <= beginAnim) {
				if (typeof config.onAnimationComplete == "function" && ctx.runanimationcompletefunction==true) config.onAnimationComplete(ctx, config, data, 0, animationCount + 1,statData);
				multAnim = 1;
				if(config.animationForceSetTimeOut)requestAnimFrameSetTimeOut(animLoop);
				else requestAnimFrame(animLoop);
			} else if (percentAnimComplete < config.animationStopValue) {
				if(config.animationForceSetTimeOut)requestAnimFrameSetTimeOut(animLoop);
				else requestAnimFrame(animLoop);
			} else {
				if ((animationCount < config.animationCount || config.animationCount == 0) && (ctx.firstPass%10==1)) {
					animationCount++;
					if (config.animationBackward && multAnim == 1) {
						percentAnimComplete -= animFrameAmount;
						multAnim = -1;
					} else {
						multAnim = 1;
						cntiter = beginAnim - 1;
						percentAnimComplete = beginAnimPct - animFrameAmount;
					}
					window.setTimeout(animLoop, config.animationPauseTime*1000);
				} else {
					if(!testRedraw(ctx,data,config)) {
						if (typeof config.onAnimationComplete == "function" && ctx.runanimationcompletefunction==true) {
							config.onAnimationComplete(ctx, config, data, 1, animationCount + 1,statData);

							ctx.runanimationcompletefunction=false;
						}
					}
					ctx.firstPass=9;
				}
				
			}
		};
	};
	//Declare global functions to be called within this namespace here.
	// shim layer with setTimeout fallback
	var requestAnimFrame = (function() {
		return window.requestAnimationFrame ||
			window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame ||
			window.oRequestAnimationFrame ||
			window.msRequestAnimationFrame ||
			function(callback) {
				window.setTimeout(callback, 1000 / 60);
			};
	})();
	var requestAnimFrameSetTimeOut = (function() {
		return	function(callback) { window.setTimeout(callback, 1000 / 60); };
	})();


	function calculateScale(axis, config, maxSteps, minSteps, maxValue, minValue, labelTemplateString) {
		var graphMin, graphMax, graphRange, stepValue, numberOfSteps, valueRange, rangeOrderOfMagnitude, decimalNum;
		var logarithmic, yAxisMinimumInterval;
		if (axis == 2) {
			logarithmic = config.logarithmic2;
			yAxisMinimumInterval = config.yAxisMinimumInterval2;
		} else {
			logarithmic = config.logarithmic;
			yAxisMinimumInterval = config.yAxisMinimumInterval;
		}

		if (!logarithmic) { // no logarithmic scale
			valueRange = maxValue - minValue;
			rangeOrderOfMagnitude = calculateOrderOfMagnitude(valueRange);
			if(Math.abs(minValue)>config.zeroValue)graphMin = Math.floor(minValue / (1 * Math.pow(10, rangeOrderOfMagnitude))) * Math.pow(10, rangeOrderOfMagnitude);
			else graphMin=0;
			if(Math.abs(maxValue)>config.zeroValue)graphMax = Math.ceil(maxValue / (1 * Math.pow(10, rangeOrderOfMagnitude))) * Math.pow(10, rangeOrderOfMagnitude);
			else graphMax=0;
			if (typeof yAxisMinimumInterval == "number") {
				if(graphMax>=0) {
					graphMin = graphMin - (graphMin % yAxisMinimumInterval);
					while (graphMin > minValue) graphMin = graphMin - yAxisMinimumInterval;
					if (graphMax % yAxisMinimumInterval > config.zeroValue && graphMax % yAxisMinimumInterval < yAxisMinimumInterval - config.zeroValue) {
						graphMax = roundScale(config, (1 + Math.floor(graphMax / yAxisMinimumInterval)) * yAxisMinimumInterval);
					}
					while (graphMax < maxValue) graphMax = graphMax + yAxisMinimumInterval;
				}
			}
		} else { // logarithmic scale
			if(minValue==maxValue)maxValue=maxValue+1;
			if(minValue==0)minValue=0.01;
			var minMag = calculateOrderOfMagnitude(minValue);
			var maxMag = calculateOrderOfMagnitude(maxValue) + 1;
			graphMin = Math.pow(10, minMag);
			graphMax = Math.pow(10, maxMag);
			rangeOrderOfMagnitude = maxMag - minMag;
		}
		graphRange = graphMax - graphMin;
		stepValue = Math.pow(10, rangeOrderOfMagnitude);
		numberOfSteps = Math.round(graphRange / stepValue);
		if (!logarithmic) { // no logarithmic scale
			//Compare number of steps to the max and min for that size graph, and add in half steps if need be.
			var stopLoop = false;
			while (!stopLoop && (numberOfSteps < minSteps || numberOfSteps > maxSteps)) {
				if (numberOfSteps < minSteps) {
					if (typeof yAxisMinimumInterval == "number") {
						if (stepValue / 2 < yAxisMinimumInterval) {
							stopLoop = true;
							stepValue=yAxisMinimumInterval;
							numberOfSteps=Math.ceil(graphRange / stepValue);
						}
					}
					if (!stopLoop) {
						stepValue /=2;
						numberOfSteps = Math.round(graphRange / stepValue);
					}
				} else {
					stepValue *= 2;
					numberOfSteps = Math.round(graphRange / stepValue);
				}
	
			}

			if (typeof yAxisMinimumInterval == "number") {
				if (stepValue < yAxisMinimumInterval) {
					stepValue = yAxisMinimumInterval;
					numberOfSteps = Math.ceil(graphRange / stepValue);
				} 
				if (stepValue % yAxisMinimumInterval > config.zeroValue && stepValue % yAxisMinimumInterval < yAxisMinimumInterval - config.zeroValue) {
					if ((2 * stepValue) % yAxisMinimumInterval < config.zeroValue || (2 * stepValue) % yAxisMinimumInterval > yAxisMinimumInterval - config.zeroValue) {
						stepValue = 2 * stepValue;
						numberOfSteps = Math.ceil(graphRange / stepValue);
					} else {
						stepValue = roundScale(config, (1 + Math.floor(stepValue / yAxisMinimumInterval)) * yAxisMinimumInterval);
						numberOfSteps = Math.ceil(graphRange / stepValue);
					}
				}
			}
			if(config.graphMaximized==true || config.graphMaximized=="bottom" || typeof config.graphMin!=="undefined") {
				while (graphMin+stepValue < minValue && numberOfSteps>=3){graphMin+=stepValue;numberOfSteps--};
			}
			if(config.graphMaximized==true || config.graphMaximized=="top" || typeof config.graphMax!=="undefined") {

				while (graphMin+(numberOfSteps-1)*stepValue >= maxValue && numberOfSteps>=3) numberOfSteps--;
			}
		} else { // logarithmic scale
			numberOfSteps = rangeOrderOfMagnitude; // so scale is 10,100,1000,...
		}
		var labels = [];
		populateLabels(1, config, labelTemplateString, labels, numberOfSteps, graphMin, graphMax, stepValue);
		return {
			steps: numberOfSteps,
			stepValue: stepValue,
			graphMin: graphMin,
			labels: labels,
			maxValue: maxValue
		}
	};

	function roundScale(config, value) {
		var scldec = 0;
		var sscl = "" + config.yAxisMinimumInterval;
		if (sscl.indexOf(".") > 0) {
			scldec = sscl.substr(sscl.indexOf(".")).length;
		}
		return (Math.round(value * Math.pow(10, scldec)) / Math.pow(10, scldec));
	} ;

	function calculateOrderOfMagnitude(val) {
		if (val==0)return 0;
		return Math.floor(Math.log(val) / Math.LN10);
	};
	//Populate an array of all the labels by interpolating the string.
	function populateLabels(axis, config, labelTemplateString, labels, numberOfSteps, graphMin, graphMax, stepValue) {
		var logarithmic;
		if (axis == 2) {
			logarithmic = config.logarithmic2;
			fmtYLabel = config.fmtYLabel2;
		} else {
			logarithmic = config.logarithmic;
			fmtYLabel = config.fmtYLabel;
		}
		if (labelTemplateString) {
			//Fix floating point errors by setting to fixed the on the same decimal as the stepValue.
			var i;
			if (!logarithmic) { // no logarithmic scale
				for (i = 0; i < numberOfSteps + 1; i++) {
					labels.push(tmpl(labelTemplateString, {
						value: fmtChartJS(config, 1 * ((graphMin + (stepValue * i)).toFixed(getDecimalPlaces(stepValue))), fmtYLabel)
					},config));
				}
			} else { // logarithmic scale 10,100,1000,...
				var value = graphMin;
				for (i = 0; i < numberOfSteps + 1; i++) {
					labels.push(tmpl(labelTemplateString, {
						value: fmtChartJS(config, 1 * value.toFixed(getDecimalPlaces(value)), fmtYLabel)
					},config));
					value *= 10;
				}
			}
		}
	};
	//Max value from array
	function Max(array) {
		return Math.max.apply(Math, array);
	};
	//Min value from array
	function Min(array) {
		return Math.min.apply(Math, array);
	};
	//Default if undefined
	function Default(userDeclared, valueIfFalse) {
		if (!userDeclared) {
			return valueIfFalse;
		} else {
			return userDeclared;
		}
	};
	//Apply cap a value at a high or low number
	function CapValue(valueToCap, maxValue, minValue) {
		if (isNumber(maxValue)) {
			if (valueToCap > maxValue) {
				return maxValue;
			}
		}
		if (isNumber(minValue)) {
			if (valueToCap < minValue) {
				return minValue;
			}
		}
		return valueToCap;
	};

	function getDecimalPlaces(num) {
		var match = (''+num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
		if (!match) { 
			return 0;
		}
		return Math.max(
			0,
			(match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0)
		);
	};

	function mergeChartConfig(defaults, userDefined) {
		var returnObj = {};
		for (var attrname in defaults) {
			returnObj[attrname] = defaults[attrname];
		}
		for (var attrnameBis in userDefined) {
			returnObj[attrnameBis] = userDefined[attrnameBis];
		}
		return returnObj;
	};
	//Javascript micro templating by John Resig - source at http://ejohn.org/blog/javascript-micro-templating/
	var cache = {};
	
	function tmpl(str, data,config) {
		newstr=str;
		if(newstr.substr(0,config.templatesOpenTag.length)==config.templatesOpenTag)newstr="<%="+newstr.substr(config.templatesOpenTag.length,newstr.length-config.templatesOpenTag.length);
		if(newstr.substr(newstr.length-config.templatesCloseTag.length,config.templatesCloseTag.length)==config.templatesCloseTag)newstr=newstr.substr(0,newstr.length-config.templatesCloseTag.length)+"%>";
		return tmplpart2(newstr,data);
	}

	function tmplpart2(str, data) {
		// Figure out if we're getting a template, or if we need to
		// load the template - and be sure to cache the result.
		var fn = !/\W/.test(str) ?
			cache[str] = cache[str] ||
			tmplpart2(document.getElementById(str).innerHTML) :
			// Generate a reusable function that will serve as a template
			// generator (and which will be cached).
			new Function("obj",
				"var p=[],print=function(){p.push.apply(p,arguments);};" +
				// Introduce the data as local variables using with(){}
				"with(obj){p.push('" +
				// Convert the template into pure JavaScript
				str
				.replace(/[\r\t\n]/g, " ")
				.split("<%").join("\t")
				.replace(/((^|%>)[^\t]*)'/g, "$1\r")
				.replace(/\t=(.*?)%>/g, "',$1,'")
				.split("\t").join("');")
				.split("%>").join("p.push('")
				.split("\r").join("\\'") + "');}return p.join('');");
		// Provide some basic currying to the user
		return data ? fn(data) : fn;
	};

	function dispCrossText(ctx, config, posX, posY, borderX, borderY, overlay, data, animPC, cntiter) {
		var i, disptxt, txtposx, txtposy, textAlign, textBaseline;
		for (i = 0; i < config.crossText.length; i++) {
			if (config.crossText[i] != "" && config.crossTextOverlay[Min([i, config.crossTextOverlay.length - 1])] == overlay && ((cntiter == 1 && config.crossTextIter[Min([i, config.crossTextIter.length - 1])] == "first") || config.crossTextIter[Min([i, config.crossTextIter.length - 1])] == cntiter || config.crossTextIter[Min([i, config.crossTextIter.length - 1])] == "all" || (animPC == 1 && config.crossTextIter[Min([i, config.crossTextIter.length - 1])] == "last"))) {
				ctx.save();
				ctx.beginPath();
				ctx.font = config.crossTextFontStyle[Min([i, config.crossTextFontStyle.length - 1])] + " " + (Math.ceil(ctx.chartTextScale*config.crossTextFontSize[Min([i, config.crossTextFontSize.length - 1])])).toString() + "px " + config.crossTextFontFamily[Min([i, config.crossTextFontFamily.length - 1])];
				ctx.fillStyle = config.crossTextFontColor[Min([i, config.crossTextFontColor.length - 1])];
				textAlign = config.crossTextAlign[Min([i, config.crossTextAlign.length - 1])];
				textBaseline = config.crossTextBaseline[Min([i, config.crossTextBaseline.length - 1])];
				txtposx = 1 * Math.ceil(ctx.chartSpaceScale*config.crossTextPosX[Min([i, config.crossTextPosX.length - 1])]);
				txtposy = 1 * Math.ceil(ctx.chartSpaceScale*config.crossTextPosY[Min([i, config.crossTextPosY.length - 1])]);
				switch (1 * config.crossTextRelativePosX[Min([i, config.crossTextRelativePosX.length - 1])]) {
					case 0:
						if (textAlign == "default") textAlign = "left";
						break;
					case 1:
						txtposx += borderX;
						if (textAlign == "default") textAlign = "right";
						break;
					case 2:
						txtposx += posX;
						if (textAlign == "default") textAlign = "center";
						break;
					case -2:
						txtposx += context.canvas.width / 2;
						if (textAlign == "default") textAlign = "center";
						break;
					case 3:
						txtposx += txtposx + 2 * posX - borderX;
						if (textAlign == "default") textAlign = "left";
						break;
					case 4:
						txtposx += context.canvas.width;
						if (textAlign == "default") textAlign = "right";
						break;
					default:
						txtposx += posX;
						if (textAlign == "default") textAlign = "center";
						break;
				}
				switch (1 * config.crossTextRelativePosY[Min([i, config.crossTextRelativePosY.length - 1])]) {
					case 0:
						if (textBaseline == "default") textBaseline = "top";
						break;
					case 3:
						txtposy += borderY;
						if (textBaseline == "default") textBaseline = "top";
						break;
					case 2:
						txtposy += posY;
						if (textBaseline == "default") textBaseline = "middle";
						break;
					case -2:
						txtposy += context.canvas.height / 2;
						if (textBaseline == "default") textBaseline = "middle";
						break;
					case 1:
						txtposy += txtposy + 2 * posY - borderY;
						if (textBaseline == "default") textBaseline = "bottom";
						break;
					case 4:
						txtposy += context.canvas.height;
						if (textBaseline == "default") textBaseline = "bottom";
						break;
					default:
						txtposy += posY;
						if (textBaseline == "default") textBaseline = "middle";
						break;
				}
				ctx.textAlign = textAlign;
				ctx.textBaseline = textBaseline;
				ctx.translate(1 * txtposx, 1 * txtposy);
				var rotateVal=Math.PI * config.crossTextAngle[Min([i, config.crossTextAngle.length - 1])] / 180;
				ctx.rotate(rotateVal);
				if (config.crossText[i].substring(0, 1) == "%") {
					if (typeof config.crossTextFunction == "function") disptxt = config.crossTextFunction(i, config.crossText[i], ctx, config, posX, posY, borderX, borderY, overlay, data, animPC);
				} else disptxt = config.crossText[i];

				setTextBordersAndBackground(ctx,disptxt,Math.ceil(ctx.chartTextScale*config.crossTextFontSize[Min([i, config.crossTextFontSize.length - 1])]),0,0,config.crossTextBorders[Min([i, config.crossTextBorders.length - 1])],config.crossTextBordersColor[Min([i, config.crossTextBordersColor.length - 1])],Math.ceil(ctx.chartLineScale*config.crossTextBordersWidth[Min([i, config.crossTextBordersWidth.length - 1])]),Math.ceil(ctx.chartSpaceScale*config.crossTextBordersXSpace[Min([i, config.crossTextBordersXSpace.length - 1])]),Math.ceil(ctx.chartSpaceScale*config.crossTextBordersYSpace[Min([i, config.crossTextBordersYSpace.length - 1])]),config.crossTextBordersStyle[Min([i, config.crossTextBordersStyle.length - 1])],config.crossTextBackgroundColor[Min([i, config.crossTextBackgroundColor.length - 1])],"CROSSTEXT",config.crossTextBordersRadius);
				if((animPC==1 && config.crossTextIter[Min([i, config.crossTextIter.length - 1])] == "all") || config.crossTextIter[Min([i, config.crossTextIter.length - 1])] != "last") {
				       ctx.fillTextMultiLine(disptxt, 0, 0, ctx.textBaseline, Math.ceil(ctx.chartTextScale*config.crossTextFontSize[Min([i, config.crossTextFontSize.length - 1])]),true,config.detectMouseOnText,ctx,"CROSSTEXT_TEXTMOUSE",rotateVal,1 * txtposx, 1 * txtposy,i,-1);
				} else ctx.fillTextMultiLine(disptxt, 0, 0, ctx.textBaseline, Math.ceil(ctx.chartTextScale*config.crossTextFontSize[Min([i, config.crossTextFontSize.length - 1])]),true,false,ctx,"CROSSTEXT_TEXTMOUSE",rotateVal,1 * txtposx, 1 * txtposy,i,-1);
				ctx.restore();
			}
		}
	};

	function dispCrossImage(ctx, config, posX, posY, borderX, borderY, overlay, data, animPC, cntiter) {
		var i, disptxt, imageposx, imageposy, imageAlign, imageBaseline;
		for (i = 0; i < config.crossImage.length; i++) {
			if (typeof config.crossImage[i] != "undefined" && config.crossImageOverlay[Min([i, config.crossImageOverlay.length - 1])] == overlay && ((cntiter == -1 && config.crossImageIter[Min([i, config.crossImageIter.length - 1])] == "background") || (cntiter == 1 && config.crossImageIter[Min([i, config.crossImageIter.length - 1])] == "first") || config.crossImageIter[Min([i, config.crossImageIter.length - 1])] == cntiter || (cntiter != -1 && config.crossImageIter[Min([i, config.crossImageIter.length - 1])] == "all") || (animPC == 1 && config.crossImageIter[Min([i, config.crossImageIter.length - 1])] == "last"))) {
				ctx.save();
				ctx.beginPath();
				imageAlign = config.crossImageAlign[Min([i, config.crossImageAlign.length - 1])];
				imageBaseline = config.crossImageBaseline[Min([i, config.crossImageBaseline.length - 1])];
				imageposx = 1 * Math.ceil(ctx.chartSpaceScale*config.crossImagePosX[Min([i, config.crossImagePosX.length - 1])]);
				imageposy = 1 * Math.ceil(ctx.chartSpaceScale*config.crossImagePosY[Min([i, config.crossImagePosY.length - 1])]);
				switch (1 * config.crossImageRelativePosX[Min([i, config.crossImageRelativePosX.length - 1])]) {
					case 0:
						if (imageAlign == "default") imageAlign = "left";
						break;
					case 1:
						imageposx += borderX;
						if (imageAlign == "default") imageAlign = "right";
						break;
					case 2:
						imageposx += posX;
						if (imageAlign == "default") imageAlign = "center";
						break;
					case -2:
						imageposx += context.canvas.width / 2;
						if (imageAlign == "default") imageAlign = "center";
						break;
					case 3:
						imageposx += imageposx + 2 * posX - borderX;
						if (imageAlign == "default") imageAlign = "left";
						break;
					case 4:
						imageposx += context.canvas.width;
						if (imageAlign == "default") imageAlign = "right";
						break;
					default:
						imageposx += posX;
						if (imageAlign == "default") imageAlign = "center";
						break;
				}
				switch (1 * config.crossImageRelativePosY[Min([i, config.crossImageRelativePosY.length - 1])]) {
					case 0:
						if (imageBaseline == "default") imageBaseline = "top";
						break;
					case 3:
						imageposy += borderY;
						if (imageBaseline == "default") imageBaseline = "top";
						break;
					case 2:
						imageposy += posY;
						if (imageBaseline == "default") imageBaseline = "middle";
						break;
					case -2:
						imageposy += context.canvas.height / 2;
						if (imageBaseline == "default") imageBaseline = "middle";
						break;
					case 1:
						imageposy += imageposy + 2 * posY - borderY;
						if (imageBaseline == "default") imageBaseline = "bottom";
						break;
					case 4:
						imageposy += context.canvas.height;
						if (imageBaseline == "default") imageBaseline = "bottom";
						break;
					default:
						imageposy += posY;
						if (imageBaseline == "default") imageBaseline = "middle";
						break;
				}
				var imageWidth = config.crossImage[i].width;
				switch (imageAlign) {
					case "left":
						break;
					case "right":
						imageposx -= imageWidth;
						break;
					case "center":
						imageposx -= (imageWidth / 2);
						break;
					default:
						break;
				}
				var imageHeight = config.crossImage[i].height;
				switch (imageBaseline) {
					case "top":
						break;
					case "bottom":
						imageposy -= imageHeight;
						break;
					case "middle":
						imageposy -= (imageHeight / 2);
						break;
					default:
						break;
				}
				ctx.translate(1 * imageposx, 1 * imageposy);
				ctx.rotate(Math.PI * config.crossImageAngle[Min([i, config.crossImageAngle.length - 1])] / 180);
				ctx.drawImage(config.crossImage[i], 0, 0);
				ctx.restore();
			}
		}
	};
	//****************************************************************************************
	function setMeasures(data, config, ctx, canvasheight, canvaswidth, ylabels, ylabels2, reverseLegend, reverseAxis, drawAxis, drawLegendOnData, legendBox, typegraph) {

        	var height=canvasheight;
        	var width=canvaswidth;
		if (window.devicePixelRatio>1) {
			height=height/window.devicePixelRatio;
			width=width/window.devicePixelRatio;
		}


        	
		if (config.canvasBackgroundColor != "none") ctx.canvas.style.background = config.canvasBackgroundColor;
		var borderWidth = 0;
		var xAxisLabelPos = 0;
		var graphTitleHeight = 0;
		var graphTitlePosY = 0;
		var graphSubTitleHeight = 0;
		var graphSubTitlePosY = 0;
		var footNoteHeight = 0;
		var footNotePosY = 0;
		var yAxisUnitHeight = 0;
		var yAxisUnitPosY = 0;
		var widestLegend = 0;
		var nbeltLegend = 0;
		var nbLegendLines = 0;
		var nbLegendCols = 0;
		var spaceLegendHeight = 0;
		var xFirstLegendTextPos = 0;
		var yFirstLegendTextPos = 0;
		var xLegendBorderPos = 0;
		var yLegendBorderPos = 0;
		var yAxisLabelWidth = 0;
		var yAxisLabelPosLeft = 0;
		var yAxisLabelPosRight = 0;
		var xAxisLabelHeight = 0;
		var xLabelHeight = 0;
		var widestXLabel = 1;
		var highestXLabel = 1;
		var widestYLabel = 0;
		var highestYLabel = 1;
		var widestYLabel2 = 0;
		var highestYLabel2 = 1;
		var leftNotUsableSize = 0;
		var rightNotUsableSize = 0;
		var rotateLabels = 0;
		var xLabelPos = 0;
		var legendBorderWidth = 0;
		var legendBorderHeight = 0;

		ctx.widthAtSetMeasures=width;
		ctx.heightAtSetMeasures=height;
		
		// Borders
		if (config.canvasBorders) borderWidth = Math.ceil(ctx.chartLineScale*config.canvasBordersWidth);
		// compute widest X label
		var textMsr,i;
		if (drawAxis) {
			ctx.font = config.scaleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.scaleFontSize)).toString() + "px " + config.scaleFontFamily;
			for (i = 0; i < data.labels.length; i++) {
				if(showLabels(ctx,data,config,i) === true) {
					textMsr = ctx.measureTextMultiLine(fmtChartJS(config, data.labels[i], config.fmtXLabel), (Math.ceil(ctx.chartTextScale*config.scaleFontSize)));
					//If the text length is longer - make that equal to longest text!
					widestXLabel = (textMsr.textWidth > widestXLabel) ? textMsr.textWidth : widestXLabel;
					highestXLabel = (textMsr.textHeight > highestXLabel) ? textMsr.textHeight : highestXLabel;
				} 
			}
			if (widestXLabel < Math.ceil(ctx.chartTextScale*config.xScaleLabelsMinimumWidth)) widestXLabel = Math.ceil(ctx.chartTextScale*config.xScaleLabelsMinimumWidth);
		}
		// compute Y Label Width
		if (drawAxis) {
			widestYLabel = 1;
			if (ylabels != null && ylabels != "nihil") {
				ctx.font = config.scaleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.scaleFontSize)).toString() + "px " + config.scaleFontFamily;
				for (i = ylabels.length - 1; i >= 0; i--) {
					if (typeof(ylabels[i]) == "string") {
						if(showYLabels(ctx,data,config,i,ylabels[i])) {
							if (ylabels[i].trim() != "") {
								textMsr = ctx.measureTextMultiLine(fmtChartJS(config, ylabels[i], config.fmtYLabel), (Math.ceil(ctx.chartTextScale*config.scaleFontSize)));
								//If the text length is longer - make that equal to longest text!
								widestYLabel = (textMsr.textWidth > widestYLabel) ? textMsr.textWidth : widestYLabel;
								highestYLabel = (textMsr.textHeight > highestYLabel) ? textMsr.textHeight : highestYLabel;
							}
						}
					}
				}
			}
			if (widestYLabel < Math.ceil(ctx.chartTextScale*config.yScaleLabelsMinimumWidth)) {
				widestYLabel = Math.ceil(ctx.chartTextScale*config.yScaleLabelsMinimumWidth);
			}
			widestYLabel2 = 1;
			if (ylabels2 != null && config.yAxisRight) {
				ctx.font = config.scaleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.scaleFontSize)).toString() + "px " + config.scaleFontFamily;
				for (i = ylabels2.length - 1; i >= 0; i--) {
					if (typeof(ylabels2[i]) == "string") {
						if (ylabels2[i].trim() != "") {
							textMsr = ctx.measureTextMultiLine(fmtChartJS(config, ylabels2[i], config.fmtYLabel2), (Math.ceil(ctx.chartTextScale*config.scaleFontSize)));
							//If the text length is longer - make that equal to longest text!
							widestYLabel2 = (textMsr.textWidth > widestYLabel2) ? textMsr.textWidth : widestYLabel2;
							highestYLabel2 = (textMsr.textHeight > highestYLabel2) ? textMsr.textHeight : highestYLabel2;
						}
					}
				}
			} else {
				widestYLabel2 = widestYLabel;
			}
			if (widestYLabel2 < Math.ceil(ctx.chartTextScale*config.yScaleLabelsMinimumWidth)) {
				widestYLabel2 = Math.ceil(ctx.chartTextScale*config.yScaleLabelsMinimumWidth);
			}
		}
		// yAxisLabel
		leftNotUsableSize = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceLeft)
		rightNotUsableSize = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceRight);
		if (drawAxis) {
			if (typeof(config.yAxisLabel) != "undefined") {
				if (config.yAxisLabel.trim() != "") {
					yAxisLabelWidth = (Math.ceil(ctx.chartTextScale*config.yAxisFontSize)) * (config.yAxisLabel.split("\n").length || 1) + Math.ceil(ctx.chartSpaceScale*config.yAxisLabelSpaceLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisLabelSpaceRight);
					yAxisLabelPosLeft = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisLabelSpaceLeft) + (Math.ceil(ctx.chartTextScale*config.yAxisFontSize));
					yAxisLabelPosRight = width - borderWidth - Math.ceil(ctx.chartSpaceScale*config.spaceRight) - Math.ceil(ctx.chartSpaceScale*config.yAxisLabelSpaceLeft) - (Math.ceil(ctx.chartTextScale*config.yAxisFontSize));
				}
				if(config.yAxisLabelBackgroundColor !="none" || config.yAxisLabelBorders) {
					yAxisLabelWidth+=2*(Math.ceil(ctx.chartSpaceScale*config.yAxisLabelBordersYSpace));
					yAxisLabelPosLeft+=Math.ceil(ctx.chartSpaceScale*config.yAxisLabelBordersYSpace);
					yAxisLabelPosRight-=Math.ceil(ctx.chartSpaceScale*config.yAxisLabelBordersYSpace);
				}

				if(config.graphTitleBorders) {
					yAxisLabelWidth+=2*(Math.ceil(ctx.chartLineScale*config.yAxisLabelBordersWidth));
					yAxisLabelPosLeft+=Math.ceil(ctx.chartLineScale*config.yAxisLabelBordersWidth);
					yAxisLabelPosRight-=Math.ceil(ctx.chartLineScale*config.yAxisLabelBordersWidth);
				}
			}
			if (config.yAxisLeft) {
				if (reverseAxis == false) leftNotUsableSize = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + yAxisLabelWidth + widestYLabel + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight);
				else                      leftNotUsableSize = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + yAxisLabelWidth + widestXLabel + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight);
			}
			if (config.yAxisRight) {
				if (reverseAxis == false) rightNotUsableSize = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceRight) + yAxisLabelWidth + widestYLabel2 + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight);
				else                      rightNotUsableSize = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceRight) + yAxisLabelWidth + widestXLabel  + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceLeft) + Math.ceil(ctx.chartSpaceScale*config.yAxisSpaceRight);
			}
		}
		availableWidth = width - leftNotUsableSize - rightNotUsableSize;
		// Title
		if (config.graphTitle.trim() != "") {
			graphTitleHeight = (Math.ceil(ctx.chartTextScale*config.graphTitleFontSize)) * (config.graphTitle.split("\n").length || 1) + Math.ceil(ctx.chartSpaceScale*config.graphTitleSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.graphTitleSpaceAfter);
			graphTitlePosY = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceTop) + graphTitleHeight - Math.ceil(ctx.chartSpaceScale*config.graphTitleSpaceAfter);
			if(config.graphTitleBackgroundColor !="none" || config.graphTitleBorders) {
				graphTitleHeight+=2*(Math.ceil(ctx.chartSpaceScale*config.graphTitleBordersYSpace));
				graphTitlePosY+=Math.ceil(ctx.chartSpaceScale*config.graphTitleBordersYSpace);
			}

			if(config.graphTitleBorders) {
				graphTitleHeight+=2*(Math.ceil(ctx.chartLineScale*config.graphTitleBordersWidth));
				graphTitlePosY+=Math.ceil(ctx.chartLineScale*config.graphTitleBordersWidth);
			}
		}
		// subTitle
		if (config.graphSubTitle.trim() != "") {
			graphSubTitleHeight = (Math.ceil(ctx.chartTextScale*config.graphSubTitleFontSize)) * (config.graphSubTitle.split("\n").length || 1) + Math.ceil(ctx.chartSpaceScale*config.graphSubTitleSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.graphSubTitleSpaceAfter);
			graphSubTitlePosY = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceTop) + graphTitleHeight + graphSubTitleHeight - Math.ceil(ctx.chartSpaceScale*config.graphSubTitleSpaceAfter);
			if(config.graphSubTitleBackgroundColor !="none" || config.graphSubTitleBorders) {
				graphSubTitleHeight+=2*(Math.ceil(ctx.chartSpaceScale*config.graphSubTitleBordersYSpace));
				graphSubTitlePosY+=Math.ceil(ctx.chartSpaceScale*config.graphSubTitleBordersYSpace);
			}

			if(config.graphSubTitleBorders) {
				graphSubTitleHeight+=2*(Math.ceil(ctx.chartLineScale*config.graphSubTitleBordersWidth));
				graphSubTitlePosY+=Math.ceil(ctx.chartLineScale*config.graphSubTitleBordersWidth);
			}
		}
		// yAxisUnit
		if (drawAxis) {
			if (config.yAxisUnit.trim() != "") {
				yAxisUnitHeight = (Math.ceil(ctx.chartTextScale*config.yAxisUnitFontSize)) * (config.yAxisUnit.split("\n").length || 1) + Math.ceil(ctx.chartSpaceScale*config.yAxisUnitSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.yAxisUnitSpaceAfter);
				yAxisUnitPosY = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceTop) + graphTitleHeight + graphSubTitleHeight + yAxisUnitHeight - Math.ceil(ctx.chartSpaceScale*config.yAxisUnitSpaceAfter);
			}
			if(config.yAxisUnitBackgroundColor !="none" || config.yAxisUnitBorders) {
				yAxisUnitHeight+=2*(Math.ceil(ctx.chartSpaceScale*config.yAxisUnitBordersYSpace));
				yAxisUnitPosY+=Math.ceil(ctx.chartSpaceScale*config.yAxisUnitBordersYSpace);
			}

			if(config.yAxisUnitBorders) {
				yAxisUnitHeight+=2*(Math.ceil(ctx.chartLineScale*config.yAxisUnitBordersWidth));
				yAxisUnitPosY+=Math.ceil(ctx.chartLineScale*config.yAxisUnitBordersWidth);
			}


		}
		topNotUsableSize = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceTop) + graphTitleHeight + graphSubTitleHeight + yAxisUnitHeight + Math.ceil(ctx.chartTextScale*config.graphSpaceBefore);
		// footNote
		if (typeof(config.footNote) != "undefined") {
			if (config.footNote.trim() != "") {
				footNoteHeight = (Math.ceil(ctx.chartTextScale*config.footNoteFontSize)) * (config.footNote.split("\n").length || 1) + Math.ceil(ctx.chartSpaceScale*config.footNoteSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.footNoteSpaceAfter);
				footNotePosY = height - Math.ceil(ctx.chartSpaceScale*config.spaceBottom) - borderWidth - Math.ceil(ctx.chartSpaceScale*config.footNoteSpaceAfter);
				if(config.footNoteBackgroundColor !="none" || config.footNoteBorders) {
					footNoteHeight+=2*(Math.ceil(ctx.chartSpaceScale*config.footNoteBordersYSpace));
					footNotePosY-=Math.ceil(ctx.chartSpaceScale*config.footNoteBordersYSpace);
				}
				if(config.footNoteBorders) {
					footNoteHeight+=2*(Math.ceil(ctx.chartLineScale*config.footNoteBordersWidth));
					footNotePosY-=Math.ceil(ctx.chartLineScale*config.footNoteBordersWidth);
				}
			}
		}
		
		// xAxisLabel
		if (drawAxis) {
			if (typeof(config.xAxisLabel) != "undefined") {
				if (config.xAxisLabel.trim() != "") {
					xAxisLabelHeight = (Math.ceil(ctx.chartTextScale*config.xAxisFontSize)) * (config.xAxisLabel.split("\n").length || 1) + Math.ceil(ctx.chartSpaceScale*config.xAxisLabelSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.xAxisLabelSpaceAfter);
					xAxisLabelPos = height - borderWidth - Math.ceil(ctx.chartSpaceScale*config.spaceBottom) - footNoteHeight - Math.ceil(ctx.chartSpaceScale*config.xAxisLabelSpaceAfter);
					if(config.xAxisLabelBackgroundColor !="none" || config.footNoteBorders) {
						xAxisLabelHeight+=2*(Math.ceil(ctx.chartSpaceScale*config.xAxisLabelBordersYSpace));
						xAxisLabelPos-=Math.ceil(ctx.chartSpaceScale*config.xAxisLabelBordersYSpace);
					}
					if(config.footNoteBorders) {
						xAxisLabelHeight+=2*(Math.ceil(ctx.chartLineScale*config.xAxisLabelBordersWidth));
						xAxisLabelPos-=Math.ceil(ctx.chartLineScale*config.xAxisLabelBordersWidth);
					}
				}
			}
		}

		bottomNotUsableHeightWithoutXLabels = borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceBottom) + footNoteHeight + xAxisLabelHeight + Math.ceil(ctx.chartTextScale*config.graphSpaceAfter);

		// compute space for Legend
		if (typeof(config.legend) != "undefined") {
			if (config.legend == true) {
				ctx.font = config.legendFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.legendFontSize)).toString() + "px " + config.legendFontFamily;
				var textLength;
				if (drawLegendOnData) {
					for (i = data.datasets.length - 1; i >= 0; i--) {
						if (typeof(data.datasets[i].title) == "string") {
							if (data.datasets[i].title.trim() != "") {
								nbeltLegend++;
								textLength = ctx.measureText(fmtChartJS(config, data.datasets[i].title, config.fmtLegend)).width;
								//If the text length is longer - make that equal to longest text!
								widestLegend = (textLength > widestLegend) ? textLength : widestLegend;
							}
						}
					}
				} else {
					for (i = data.length - 1; i >= 0; i--) {
						if (typeof(data[i].title) == "string") {
							if (data[i].title.trim() != "") {
								nbeltLegend++;
								textLength = ctx.measureText(fmtChartJS(config, data[i].title, config.fmtLegend)).width;
								//If the text length is longer - make that equal to longest text!
								widestLegend = (textLength > widestLegend) ? textLength : widestLegend;
							}
						}
					}
				}
				if (nbeltLegend > 1 || (nbeltLegend == 1 && config.showSingleLegend)) {
					widestLegend += Math.ceil(ctx.chartTextScale*config.legendBlockSize) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenBoxAndText);
					if(config.legendPosY==1 || config.legendPosY==2 || config.legendPosY==3) {
						availableLegendWidth = availableWidth- Math.ceil(ctx.chartSpaceScale*Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText)) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText);
					} else {
						availableLegendWidth = width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight) - 2 * (borderWidth) - Math.ceil(ctx.chartSpaceScale*Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText)) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText);
					}
					if (config.legendBorders == true) availableLegendWidth -= 2 * (Math.ceil(ctx.chartLineScale*config.legendBordersWidth)) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceLeft) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceRight);
					maxLegendOnLine = Min([Math.floor((availableLegendWidth + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) / (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal))),config.maxLegendCols]);
					nbLegendLines = Math.ceil(nbeltLegend / maxLegendOnLine);
					nbLegendCols = Math.ceil(nbeltLegend / nbLegendLines);
				
					var legendHeight = nbLegendLines * ((Math.ceil(ctx.chartTextScale*config.legendFontSize)) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextVertical)) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextVertical) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceAfterText);

					switch (config.legendPosY) {
						case 0:
							xFirstLegendTextPos = Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight) - nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) / 2;
							spaceLegendHeight = legendHeight;
							if (config.legendBorders == true) {
								yLegendBorderPos = topNotUsableSize + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) + (Math.ceil(ctx.chartLineScale*config.legendBordersWidth)/2);
								yFirstLegendTextPos = yLegendBorderPos  + (Math.ceil(ctx.chartLineScale*config.legendBordersWidth)/2) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText)+(Math.ceil(ctx.chartTextScale*config.legendFontSize));
								spaceLegendHeight += 2 * Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								xLegendBorderPos = Math.floor(xFirstLegendTextPos - Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText) - (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) / 2));
								legendBorderHeight = Math.ceil(spaceLegendHeight - Math.ceil(ctx.chartLineScale*config.legendBordersWidth)) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								legendBorderWidth = Math.ceil(nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal))) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal) + Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText);
							} else {
								yFirstLegendTextPos = topNotUsableSize + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) + (Math.ceil(ctx.chartLineScale*config.legendBordersWidth)/2);
							}
							if(yAxisUnitHeight>0) {
								yAxisUnitPosY+=spaceLegendHeight;
								if(config.legendBorders==true)yLegendBorderPos-=yAxisUnitHeight;
								yFirstLegendTextPos-=yAxisUnitHeight;
							}
							topNotUsableSize += spaceLegendHeight;
							break;
						case 1:
							spaceLegendHeight = legendHeight;
							xFirstLegendTextPos = Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight) - nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) / 2;
							yFirstLegendTextPos = topNotUsableSize + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText)+(Math.ceil(ctx.chartTextScale*config.legendFontSize));
							if (config.legendBorders == true) {
								yFirstLegendTextPos += Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore)+Math.ceil(ctx.chartLineScale*config.legendBordersWidth);
								yLegendBorderPos = yFirstLegendTextPos - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText) - (Math.ceil(ctx.chartTextScale*config.legendFontSize)) - (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) /2 );
								spaceLegendHeight += 2 * Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								xLegendBorderPos = Math.floor(xFirstLegendTextPos - Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText) - (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) / 2));
								legendBorderHeight = Math.ceil(spaceLegendHeight - Math.ceil(ctx.chartLineScale*config.legendBordersWidth)) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								legendBorderWidth = Math.ceil(nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal))) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal) + Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText);
							}
							break;
						case 2:
							spaceLegendHeight = legendHeight;
							xFirstLegendTextPos = Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight) - nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) / 2;
							yFirstLegendTextPos = topNotUsableSize + (height - topNotUsableSize - bottomNotUsableHeightWithoutXLabels - spaceLegendHeight) /2 + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText)+(Math.ceil(ctx.chartTextScale*config.legendFontSize));
							if (config.legendBorders == true) {
								yFirstLegendTextPos += Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								yLegendBorderPos = yFirstLegendTextPos - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText) - (Math.ceil(ctx.chartTextScale*config.legendFontSize)) - (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) /2 );
								spaceLegendHeight += 2 * Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								xLegendBorderPos = Math.floor(xFirstLegendTextPos - Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText) - (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) / 2));
								legendBorderHeight = Math.ceil(spaceLegendHeight - Math.ceil(ctx.chartLineScale*config.legendBordersWidth)) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								legendBorderWidth = Math.ceil(nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal))) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal) + Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText);
							}
							break;
						case -2:
							spaceLegendHeight = legendHeight;
							xFirstLegendTextPos = Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight) - nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) / 2;
							yFirstLegendTextPos = (height - spaceLegendHeight) /2 + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText)+(Math.ceil(ctx.chartTextScale*config.legendFontSize));
							if (config.legendBorders == true) {
								yFirstLegendTextPos += Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								yLegendBorderPos = yFirstLegendTextPos - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText) - (Math.ceil(ctx.chartTextScale*config.legendFontSize)) - (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) /2 );
								spaceLegendHeight += 2 * Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								xLegendBorderPos = Math.floor(xFirstLegendTextPos - Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText) - (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) / 2));
								legendBorderHeight = Math.ceil(spaceLegendHeight - Math.ceil(ctx.chartLineScale*config.legendBordersWidth)) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								legendBorderWidth = Math.ceil(nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal))) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal) + Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText);
							}
							break;
						case 3:
							spaceLegendHeight = legendHeight;
							xFirstLegendTextPos = Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight) - nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) / 2;
							availableHeight = height - topNotUsableSize - bottomNotUsableHeightWithoutXLabels;
							yFirstLegendTextPos = topNotUsableSize + availableHeight - spaceLegendHeight + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText)+(Math.ceil(ctx.chartTextScale*config.legendFontSize));
							if (config.legendBorders == true) {
								yFirstLegendTextPos -= (Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter)+Math.ceil(ctx.chartLineScale*config.legendBordersWidth));
								yLegendBorderPos = yFirstLegendTextPos - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText) - (Math.ceil(ctx.chartTextScale*config.legendFontSize)) - (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) /2 );
								spaceLegendHeight += 2 * Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								xLegendBorderPos = Math.floor(xFirstLegendTextPos - Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText) - (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) / 2));
								legendBorderHeight = Math.ceil(spaceLegendHeight - Math.ceil(ctx.chartLineScale*config.legendBordersWidth)) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								legendBorderWidth = Math.ceil(nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal))) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal) + Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText);
							}
							break;
						default:
							spaceLegendHeight = legendHeight;
							yFirstLegendTextPos = height - borderWidth - Math.ceil(ctx.chartSpaceScale*config.spaceBottom) - footNoteHeight - spaceLegendHeight + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBeforeText) + (Math.ceil(ctx.chartTextScale*config.legendFontSize));
							xFirstLegendTextPos = Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight) - nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) / 2;
							if (config.legendBorders == true) {
								spaceLegendHeight += 2 * Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								yFirstLegendTextPos -= (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter));
								yLegendBorderPos = Math.floor(height - borderWidth - Math.ceil(ctx.chartSpaceScale*config.spaceBottom) - footNoteHeight - spaceLegendHeight + (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) / 2) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore));
								xLegendBorderPos = Math.floor(xFirstLegendTextPos - Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText) - (Math.ceil(ctx.chartLineScale*config.legendBordersWidth) / 2));
								legendBorderHeight = Math.ceil(spaceLegendHeight - Math.ceil(ctx.chartLineScale*config.legendBordersWidth)) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceBefore) - Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceAfter);
								legendBorderWidth = Math.ceil(nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal))) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal) + Math.ceil(ctx.chartLineScale*config.legendBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText);
							} 
							xAxisLabelPos -= spaceLegendHeight;
							bottomNotUsableHeightWithoutXLabels +=spaceLegendHeight;
							break;
					}		
					var fullLegendWidth=Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText) + nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal) +Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText);
					if (config.legendBorders == true) {
						fullLegendWidth+=2*Math.ceil(ctx.chartLineScale*config.legendBordersWidth)+Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceLeft)+Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceRight);
					}

					switch (config.legendPosX) {
						case 0:
						case 1:
							xFirstLegendTextPos = Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + config.canvasBorders * Math.ceil(ctx.chartLineScale*config.canvasBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText);  
							if (config.legendBorders == true) {
								xFirstLegendTextPos += (Math.ceil(ctx.chartLineScale*config.legendBordersWidth)/2)+Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceLeft);
								xLegendBorderPos = Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + config.canvasBorders * Math.ceil(ctx.chartLineScale*config.canvasBordersWidth) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceLeft);
							}
							if(config.legendPosX==0 && ((config.legendPosY>=1 && config.legendPosY <=3) || config.legendPosY==-2)) {
								leftNotUsableSize+=fullLegendWidth;
								yAxisLabelPosLeft+=fullLegendWidth;
							}
							break;
						case 2:
							xFirstLegendTextPos = leftNotUsableSize + (width - rightNotUsableSize - leftNotUsableSize)/2 - (Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText)-Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText)) - (nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) - Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) / 2;  
							if (config.legendBorders == true) {
								xFirstLegendTextPos -= ((Math.ceil(ctx.chartLineScale*config.legendBordersWidth)/2) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceRight));
								xLegendBorderPos = xFirstLegendTextPos - Math.ceil(ctx.chartLineScale*config.legendBordersWidth)/2 - Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText) ;
							}
							break;
							if((config.legendPosY>=1 && config.legendPosY <=3) || config.legendPosY==-2) {
								rightNotUsableSize+=fullLegendWidth;
								yAxisLabelPosRight-=fullLegendWidth;
							}
						case 3:
						case 4:
							xFirstLegendTextPos = width - rightNotUsableSize - Math.ceil(ctx.chartSpaceScale*config.legendSpaceRightText) - nbLegendCols * (widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal)) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal) / 2;  
							if (config.legendBorders == true) {
								xFirstLegendTextPos -= ((Math.ceil(ctx.chartLineScale*config.legendBordersWidth)/2) + Math.ceil(ctx.chartSpaceScale*config.legendBordersSpaceRight));
								xLegendBorderPos = xFirstLegendTextPos - Math.ceil(ctx.chartLineScale*config.legendBordersWidth)/2 - Math.ceil(ctx.chartSpaceScale*config.legendSpaceLeftText) ;
							}
							if(config.legendPosX==4 && ((config.legendPosY>=1 && config.legendPosY <=3) || config.legendPosY==-2)) {
								rightNotUsableSize+=fullLegendWidth;
								yAxisLabelPosRight-=fullLegendWidth;
							}
							break;
						default:
							break;
					}
					if(config.legendBorders==true) {
						yLegendBorderPos+=Math.ceil(ctx.chartSpaceScale*config.legendYPadding);
						xLegendBorderPos+=Math.ceil(ctx.chartSpaceScale*config.legendXPadding);
						
					}
					yFirstLegendTextPos+=Math.ceil(ctx.chartSpaceScale*config.legendYPadding);	
					xFirstLegendTextPos+=Math.ceil(ctx.chartSpaceScale*config.legendXPadding);	
					
				}
			}
			
		}
		xLabelWidth = 0;
		bottomNotUsableHeightWithXLabels = bottomNotUsableHeightWithoutXLabels;
		if (drawAxis && (config.xAxisBottom || config.xAxisTop)) {
			var widestLabel,highestLabel;		
			if (reverseAxis == false) {
				widestLabel = widestXLabel;
				highestLabel = highestXLabel;
				nblab = data.labels.length;
			} else {
				widestLabel = widestYLabel;
				highestLabel = highestYLabel;
				nblab = ylabels.length;
			}
			if (config.rotateLabels == "smart") {
				rotateLabels = 0;
				if ((availableWidth + Math.ceil(ctx.chartTextScale*config.xAxisSpaceBetweenLabels)) / nblab < (widestLabel + Math.ceil(ctx.chartTextScale*config.xAxisSpaceBetweenLabels))) {
					rotateLabels = 45;
					if (availableWidth / nblab < Math.abs(Math.cos(rotateLabels * Math.PI / 180) * widestLabel)) {
						rotateLabels = 90;
					}
				}
			} else {
				rotateLabels = config.rotateLabels
				if (rotateLabels < 0) rotateLabels = 0;
				if (rotateLabels > 180) rotateLabels = 180;
			}
			if (rotateLabels > 90) rotateLabels += 180;
			xLabelHeight = Math.abs(Math.sin(rotateLabels * Math.PI / 180) * widestLabel) + Math.abs(Math.sin((rotateLabels + 90) * Math.PI / 180) * highestLabel) + Math.ceil(ctx.chartSpaceScale*config.xAxisSpaceBefore) + Math.ceil(ctx.chartSpaceScale*config.xAxisSpaceAfter);
			xLabelPos = height - borderWidth - Math.ceil(ctx.chartSpaceScale*config.spaceBottom) - footNoteHeight - xAxisLabelHeight - (xLabelHeight - Math.ceil(ctx.chartSpaceScale*config.xAxisSpaceBefore)) - Math.ceil(ctx.chartTextScale*config.graphSpaceAfter);
			xLabelWidth = Math.abs(Math.cos(rotateLabels * Math.PI / 180) * widestLabel) + Math.abs(Math.cos((rotateLabels + 90) * Math.PI / 180) * highestLabel);
			leftNotUsableSize = Max([leftNotUsableSize, borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + xLabelWidth / 2]);
			rightNotUsableSize = Max([rightNotUsableSize, borderWidth + Math.ceil(ctx.chartSpaceScale*config.spaceRight) + xLabelWidth / 2]);
			availableWidth = width - leftNotUsableSize - rightNotUsableSize;
			if (config.legend && config.xAxisBottom && config.legendPosY==4) {
				xLabelPos-=spaceLegendHeight;
			} 
			bottomNotUsableHeightWithXLabels = bottomNotUsableHeightWithoutXLabels + xLabelHeight ;
		}  else {
			availableWidth = width - leftNotUsableSize - rightNotUsableSize;
		}

		availableHeight = height - topNotUsableSize - bottomNotUsableHeightWithXLabels;

		// ----------------------- DRAW EXTERNAL ELEMENTS -------------------------------------------------
		dispCrossImage(ctx, config, width / 2, height / 2, width / 2, height / 2, false, data, -1, -1);

		if(typeof config.initFunction == "function") config.initFunction("INITFUNCTION",ctx,data,null,-1,-1,{animationValue : 0, cntiter: 0, config : config, borderX : 0, borderY : 0, midPosX : 0, midPosY : 0});

 		
		 if (ylabels != "nihil") {
			// Draw Borders
			if (borderWidth > 0) {
				ctx.save();
				ctx.beginPath();
				ctx.setLineDash(lineStyleFn(config.canvasBordersStyle));
				ctx.strokeStyle = config.canvasBordersColor;
				ctx.lineWidth = borderWidth;
				ctx.setLineDash(lineStyleFn(config.canvasBordersStyle));
				ctx.strokeStyle = config.canvasBordersColor;
				ctx.drawRectangle({x:0+borderWidth/2,y:0+borderWidth/2,width:width-borderWidth,height:height-borderWidth,borderRadius:config.canvasBordersRadius,fill:false,stroke:true})
				ctx.setLineDash([]);
				ctx.restore();
			}
			// Draw Graph Title
			if (graphTitleHeight > 0) {
				ctx.save();
				ctx.beginPath();
				ctx.font = config.graphTitleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.graphTitleFontSize)).toString() + "px " + config.graphTitleFontFamily;
				ctx.fillStyle = config.graphTitleFontColor;
				ctx.textAlign = "center";
				ctx.textBaseline = "bottom";

				setTextBordersAndBackground(ctx,config.graphTitle,(Math.ceil(ctx.chartTextScale*config.graphTitleFontSize)),Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight)) / 2,graphTitlePosY,config.graphTitleBorders,config.graphTitleBordersColor,Math.ceil(ctx.chartLineScale*config.graphTitleBordersWidth),Math.ceil(ctx.chartSpaceScale*config.graphTitleBordersXSpace),Math.ceil(ctx.chartSpaceScale*config.graphTitleBordersYSpace),config.graphTitleBordersStyle,config.graphTitleBackgroundColor,"GRAPHTITLE",config.graphTitleBordersRadius);

				ctx.translate(Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight)) / 2, graphTitlePosY);
				ctx.fillTextMultiLine(config.graphTitle, 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.graphTitleFontSize)),true,config.detectMouseOnText,ctx,"TITLE_TEXTMOUSE",0,Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight)) / 2, graphTitlePosY,-1,-1);

				ctx.stroke();
				ctx.restore();
			}
			// Draw Graph Sub-Title
			if (graphSubTitleHeight > 0) {
				ctx.save();
				ctx.beginPath();
				ctx.font = config.graphSubTitleFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.graphSubTitleFontSize)).toString() + "px " + config.graphSubTitleFontFamily;
				ctx.fillStyle = config.graphSubTitleFontColor;
				ctx.textAlign = "center";
				ctx.textBaseline = "bottom";
				setTextBordersAndBackground(ctx,config.graphSubTitle,(Math.ceil(ctx.chartTextScale*config.graphSubTitleFontSize)),Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight)) / 2,graphSubTitlePosY,config.graphSubTitleBorders,config.graphSubTitleBordersColor,Math.ceil(ctx.chartLineScale*config.graphSubTitleBordersWidth),Math.ceil(ctx.chartSpaceScale*config.graphSubTitleBordersXSpace),Math.ceil(ctx.chartSpaceScale*config.graphSubTitleBordersYSpace),config.graphSubTitleBordersStyle,config.graphSubTitleBackgroundColor,"GRAPHSUBTITLE",config.graphSubTitleBordersRadius);

				ctx.translate(Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight)) / 2, graphSubTitlePosY);
				ctx.fillTextMultiLine(config.graphSubTitle, 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.graphSubTitleFontSize)),true,config.detectMouseOnText,ctx,"SUBTITLE_TEXTMOUSE",0,Math.ceil(ctx.chartSpaceScale*config.spaceLeft) + (width - Math.ceil(ctx.chartSpaceScale*config.spaceLeft) - Math.ceil(ctx.chartSpaceScale*config.spaceRight)) / 2, graphSubTitlePosY,-1,-1);
				ctx.stroke();
				ctx.restore();
			}
			// Draw Y Axis Unit
			if (yAxisUnitHeight > 0) {
				if (config.yAxisLeft) {
					ctx.save();
					ctx.beginPath();
					ctx.font = config.yAxisUnitFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.yAxisUnitFontSize)).toString() + "px " + config.yAxisUnitFontFamily;
					ctx.fillStyle = config.yAxisUnitFontColor;
					ctx.textAlign = "center";
					ctx.textBaseline = "bottom";
		
					setTextBordersAndBackground(ctx,config.yAxisUnit,(Math.ceil(ctx.chartTextScale*config.yAxisUnitFontSize)),leftNotUsableSize, yAxisUnitPosY,config.yAxisUnitBorders,config.yAxisUnitBordersColor,Math.ceil(ctx.chartLineScale*config.yAxisUnitBordersWidth),Math.ceil(ctx.chartSpaceScale*config.yAxisUnitBordersXSpace),Math.ceil(ctx.chartSpaceScale*config.yAxisUnitBordersYSpace),config.yAxisUnitBordersStyle,config.yAxisUnitBackgroundColor,"YAXISUNIT",config.yAxisUnitBordersRadius);
					ctx.translate(leftNotUsableSize, yAxisUnitPosY);
					ctx.fillTextMultiLine(config.yAxisUnit, 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.yAxisUnitFontSize)),true,config.detectMouseOnText,ctx,"YLEFTAXISUNIT_TEXTMOUSE",0,leftNotUsableSize, yAxisUnitPosY,-1,-1);
					ctx.stroke();
					ctx.restore();
				}
				if (config.yAxisRight) {
					if (config.yAxisUnit2 == '') config.yAxisUnit2 = config.yAxisUnit;
					ctx.save();
					ctx.beginPath();
					ctx.font = config.yAxisUnitFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.yAxisUnitFontSize)).toString() + "px " + config.yAxisUnitFontFamily;
					ctx.fillStyle = config.yAxisUnitFontColor;
					ctx.textAlign = "center";
					ctx.textBaseline = "bottom";
					setTextBordersAndBackground(ctx,config.yAxisUnit2,(Math.ceil(ctx.chartTextScale*config.yAxisUnitFontSize)),width - rightNotUsableSize, yAxisUnitPosY,config.yAxisUnitBorders,config.yAxisUnitBordersColor,Math.ceil(ctx.chartLineScale*config.yAxisUnitBordersWidth),Math.ceil(ctx.chartSpaceScale*config.yAxisUnitBordersXSpace),Math.ceil(ctx.chartSpaceScale*config.yAxisUnitBordersYSpace),config.yAxisUnitBordersStyle,config.yAxisUnitBackgroundColor,"YAXISUNIT",config.yAxisUnitBordersRadius);
					ctx.translate(width - rightNotUsableSize, yAxisUnitPosY);
					ctx.fillTextMultiLine(config.yAxisUnit2, 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.yAxisUnitFontSize)),true,config.detectMouseOnText,ctx,"YRIGHTAXISUNIT_TEXTMOUSE",0,width - rightNotUsableSize, yAxisUnitPosY,-1,-1);
					ctx.stroke();
					ctx.restore();
				}
			}
			// Draw Y Axis Label
			if (yAxisLabelWidth > 0) {
				if (config.yAxisLeft) {
					ctx.save();
					ctx.beginPath();
					ctx.font = config.yAxisFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.yAxisFontSize)).toString() + "px " + config.yAxisFontFamily;
					ctx.fillStyle = config.yAxisFontColor;
					ctx.textAlign = "center";
					ctx.textBaseline = "bottom";

					ctx.translate(yAxisLabelPosLeft, topNotUsableSize + (availableHeight / 2));
					ctx.rotate(-(90 * (Math.PI / 180)));
					setTextBordersAndBackground(ctx,config.yAxisLabel,(Math.ceil(ctx.chartTextScale*config.yAxisFontSize)), 0,0, config.yAxisLabelBorders,config.yAxisLabelBordersColor,Math.ceil(ctx.chartLineScale*config.yAxisLabelBordersWidth),Math.ceil(ctx.chartSpaceScale*config.yAxisLabelBordersXSpace),Math.ceil(ctx.chartSpaceScale*config.yAxisLabelBordersYSpace),config.yAxisLabelBordersStyle,config.yAxisLabelBackgroundColor,"YAXISLABELLEFT",config.yAxisLabelBordersRadius);
					ctx.fillTextMultiLine(config.yAxisLabel, 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.yAxisFontSize)),false,config.detectMouseOnText,ctx,"YLEFTAXISLABEL_TEXTMOUSE",-(90 * (Math.PI / 180)),yAxisLabelPosLeft, topNotUsableSize + (availableHeight / 2),-1,-1);
					ctx.stroke();
					ctx.restore();
				}
				if (config.yAxisRight) {
					if (config.yAxisLabel2 == '') config.yAxisLabel2 = config.yAxisLabel;
					ctx.save();
					ctx.beginPath();
					ctx.font = config.yAxisFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.yAxisFontSize)).toString() + "px " + config.yAxisFontFamily;
					ctx.fillStyle = config.yAxisFontColor;
					ctx.textAlign = "center";
					ctx.textBaseline = "bottom";
					ctx.translate(yAxisLabelPosRight, topNotUsableSize + (availableHeight / 2));
					ctx.rotate(+(90 * (Math.PI / 180)));
					setTextBordersAndBackground(ctx,config.yAxisLabel2,(Math.ceil(ctx.chartTextScale*config.yAxisFontSize)), 0,0, config.yAxisLabelBorders,config.yAxisLabelBordersColor,Math.ceil(ctx.chartLineScale*config.yAxisLabelBordersWidth),Math.ceil(ctx.chartSpaceScale*config.yAxisLabelBordersXSpace),Math.ceil(ctx.chartSpaceScale*config.yAxisLabelBordersYSpace),config.yAxisLabelBordersStyle,config.yAxisLabelBackgroundColor,"YAXISLABELLEFT",config.yAxisLabelBordersRadius);
					ctx.fillTextMultiLine(config.yAxisLabel2, 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.yAxisFontSize)),false,config.detectMouseOnText,ctx,"YRIGHTAXISLABEL_TEXTMOUSE",+(90 * (Math.PI / 180)),yAxisLabelPosRight, topNotUsableSize + (availableHeight / 2),-1,-1);
					ctx.stroke();
					ctx.restore();
				}
			}
			// Draw X Axis Label
			if (xAxisLabelHeight > 0) {
				if (config.xAxisBottom) {
					ctx.save();
					ctx.beginPath();
					ctx.font = config.xAxisFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.xAxisFontSize)).toString() + "px " + config.xAxisFontFamily;
					ctx.fillStyle = config.xAxisFontColor;
					ctx.textAlign = "center";
					ctx.textBaseline = "bottom";
					setTextBordersAndBackground(ctx,config.xAxisLabel,(Math.ceil(ctx.chartTextScale*config.xAxisFontSize)),leftNotUsableSize + (availableWidth / 2), xAxisLabelPos,config.xAxisLabelBorders,config.xAxisLabelBordersColor,Math.ceil(ctx.chartLineScale*config.xAxisLabelBordersWidth),Math.ceil(ctx.chartSpaceScale*config.xAxisLabelBordersXSpace),Math.ceil(ctx.chartSpaceScale*config.xAxisLabelBordersYSpace),config.xAxisLabelBordersStyle,config.xAxisLabelBackgroundColor,"XAXISLABEL",config.xAxisLabelBordersRadius);
					ctx.translate(leftNotUsableSize + (availableWidth / 2), xAxisLabelPos);
					ctx.fillTextMultiLine(config.xAxisLabel, 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.xAxisFontSize)),true,config.detectMouseOnText,ctx,"XAXISLABEL_TEXTMOUSE",0,leftNotUsableSize + (availableWidth / 2), xAxisLabelPos,-1,-1);
					ctx.stroke();
					ctx.restore();
				}
			}
			// Draw Legend
                        var legendMsr;
			if (nbeltLegend > 1 || (nbeltLegend == 1 && config.showSingleLegend)) {
				legendMsr={dispLegend : true, xLegendBorderPos : xLegendBorderPos,
					   yLegendBorderPos : yLegendBorderPos, legendBorderWidth : legendBorderWidth, legendBorderHeight : legendBorderHeight, 
					   nbLegendCols: nbLegendCols, xFirstLegendTextPos : xFirstLegendTextPos , yFirstLegendTextPos : yFirstLegendTextPos, 
					   drawLegendOnData : drawLegendOnData, reverseLegend : reverseLegend, legendBox : legendBox, widestLegend : widestLegend };
				if(config.legendPosY==0 || config.legendPosY==4 || config.legendPosX==0 || config.legendPosX==4) {

					drawLegend(legendMsr,data,config,ctx,typegraph);
					legendMsr={dispLegend : false};
				} 
			} else {
				legendMsr={dispLegend : false };
			}
			// Draw FootNote
			if (config.footNote.trim() != "") {
				ctx.save();
				ctx.font = config.footNoteFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.footNoteFontSize)).toString() + "px " + config.footNoteFontFamily;
				ctx.fillStyle = config.footNoteFontColor;
				ctx.textAlign = "center";
				ctx.textBaseline = "bottom";

				setTextBordersAndBackground(ctx,config.footNote,(Math.ceil(ctx.chartTextScale*config.footNoteFontSize)),leftNotUsableSize + (availableWidth / 2), footNotePosY,config.footNoteBorders,config.footNoteBordersColor,Math.ceil(ctx.chartLineScale*config.footNoteBordersWidth),Math.ceil(ctx.chartSpaceScale*config.footNoteBordersXSpace),Math.ceil(ctx.chartSpaceScale*config.footNoteBordersYSpace),config.footNoteBordersStyle,config.footNoteBackgroundColor,"FOOTNOTE",config.footNoteBordersRadius);

				ctx.translate(leftNotUsableSize + (availableWidth / 2), footNotePosY);
				ctx.fillTextMultiLine(config.footNote, 0, 0, ctx.textBaseline, (Math.ceil(ctx.chartTextScale*config.footNoteFontSize)),true,config.detectMouseOnText,ctx,"FOOTNOTE_TEXTMOUSE",0,leftNotUsableSize + (availableWidth / 2), footNotePosY,-1,-1);
				ctx.stroke();
				ctx.restore();
			}
		}
		clrx = leftNotUsableSize;
		clrwidth = availableWidth;
		clry = topNotUsableSize;
		clrheight = availableHeight;
		return {
			leftNotUsableSize: leftNotUsableSize,
			rightNotUsableSize: rightNotUsableSize,
			availableWidth: availableWidth,
			topNotUsableSize: topNotUsableSize,
			bottomNotUsableHeightWithoutXLabels: bottomNotUsableHeightWithoutXLabels,
			bottomNotUsableHeightWithXLabels: bottomNotUsableHeightWithXLabels,
			availableHeight: availableHeight,
			widestXLabel: widestXLabel,
			highestXLabel: highestXLabel,
			widestYLabel: widestYLabel,
			widestYLabel2: widestYLabel2,
			highestYLabel: highestYLabel,
			rotateLabels: rotateLabels,
			xLabelPos: xLabelPos,
			clrx: clrx,
			clry: clry,
			clrwidth: clrwidth,
			clrheight: clrheight,
			legendMsr : legendMsr
		};
	};


	// Function for drawing lines (BarLine|Line)

	function drawLinesDataset(animPc, data, config, ctx, statData,vars) {
		var y1,y2,y3,diffnb,diffnbj,fact, currentAnimPc;
		var prevypos;
		var pts=[];
		for (var i = 0; i < data.datasets.length; i++) {
			if(setOptionValue(true,1,"ANIMATION",ctx,data,statData,data.datasets[i].animation,config.animation,"animation",i,-1,{nullvalue : null} )==true || animPc >= 1) {
				prevypos="undefined";
				if(statData[i][0].tpchart!="Line")continue;
				if (statData[i].length == 0) continue;
				if (statData[i][0].firstNotMissing == -1) continue;
				ctx.save();
				ctx.beginPath();

				prevAnimPc={ mainVal:0 , subVal : 0,animVal : 0 };
				var firstpt=-1;
				var lastxPos=-1;
				for (var j = statData[i][0].firstNotMissing; j <= statData[i][0].lastNotMissing; j++) {
					if(prevAnimPc.animVal==0 && j>statData[i][0].firstNotMissing) continue;	
					currentAnimPc = animationCorrection(animPc, data, config, i, j, true);
					if (currentAnimPc.mainVal == 0  && (prevAnimPc.mainVal > 0 && firstpt !=-1)) {
						ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"LINEDASH",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
						ctx.stroke();
						ctx.setLineDash([]);
						if(config.extrapolateMissingData) {
							y1=statData[i][statData[i][j].prevNotMissing].yAxisPos - prevAnimPc.mainVal*statData[i][statData[i][j].prevNotMissing].yPosOffset;					
							y2=statData[i][j].yAxisPos - prevAnimPc.mainVal*statData[i][statData[i][j-1].nextNotMissing].yPosOffset;
							diffnb=statData[i][j-1].nextNotMissing-statData[i][j].prevNotMissing;
							diffnbj=(j-1)-statData[i][j].prevNotMissing;
							fact=(diffnbj+prevAnimPc.subVal)/diffnb;
							y3=y1+fact*(y2-y1);					
							traceLine(pts,ctx,statData[i][statData[i][j].prevNotMissing].xPos + fact*(statData[i][statData[i][j-1].nextNotMissing].xPos-statData[i][statData[i][j].prevNotMissing].xPos) , y3,config,data,statData,i,setYposOrigin(i,j,data,statData));
							closebz(pts,ctx,config,i);
							ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"LINEDASH",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
							ctx.stroke();
							ctx.setLineDash([]);
							ctx.strokeStyle = "rgba(0,0,0,0)";
							if(config.datasetFill && setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )!=1) {
								ctx.lineTo(statData[i][statData[i][j].prevNotMissing].xPos + fact*(statData[i][statData[i][j-1].nextNotMissing].xPos-statData[i][statData[i][j].prevNotMissing].xPos) , statData[i][j].yAxisPos );
								ctx.lineTo(statData[i][firstpt].xPos, statData[i][firstpt].xAxisPosY-statData[i][0].zeroY);
								ctx.closePath();

								ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data.datasets[i].fillColor,config.defaultFillColor,"fillColor",i,j,{animationValue: currentAnimPc.mainVal, xPosLeft : statData[i][0].xPos, yPosBottom : Math.max(statData[i][0].yAxisPos,statData[i][0].yAxisPos- ((config.animationLeftToRight) ? 1 : 1*currentAnimPc.mainVal) * statData[i][0].lminvalue_offset), xPosRight : statData[i][data.datasets[i].data.length-1].xPos, yPosTop : Math.min(statData[i][0].yAxisPos, statData[i][0].yAxisPos - ((config.animationLeftToRight) ? 1 : 1*currentAnimPc.mainVal) * statData[i][0].lmaxvalue_offset)} );
								ctx.fill();
								firstpt=-1;
							}
						} else if (!(typeof statData[i][j].value == "undefined")) {
							traceLine(pts,ctx,statData[i][j-1].xPos + prevAnimPc.subVal*(statData[i][j].xPos-statData[i][j-1].xPos) , statData[i][j].yAxisPos - prevAnimPc.mainVal*statData[i][statData[i][j-1].nextNotMissing].yPosOffset,config,data,statData,i,setYposOrigin(i,j,data,statData));
							closebz(pts,ctx,config,i);
							ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"LINEDASH",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
							ctx.stroke();
							ctx.setLineDash([]);
							ctx.strokeStyle = "rgba(0,0,0,0)";
							if(config.datasetFill && setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )!=1) {
								ctx.lineTo(statData[i][j-1].xPos + prevAnimPc.subVal*(statData[i][j].xPos-statData[i][j-1].xPos) , statData[i][j].yAxisPos );
								ctx.lineTo(statData[i][firstpt].xPos, statData[i][firstpt].xAxisPosY-statData[i][0].zeroY);
								ctx.closePath();
								ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data.datasets[i].fillColor,config.defaultFillColor,"fillColor",i,j,{animationValue: currentAnimPc.mainVal, xPosLeft : statData[i][0].xPos, yPosBottom : Math.max(statData[i][0].yAxisPos,statData[i][0].yAxisPos- ((config.animationLeftToRight) ? 1 : 1*currentAnimPc.mainVal) * statData[i][0].lminvalue_offset), xPosRight : statData[i][data.datasets[i].data.length-1].xPos, yPosTop : Math.min(statData[i][0].yAxisPos, statData[i][0].yAxisPos - ((config.animationLeftToRight) ? 1 : 1*currentAnimPc.mainVal) * statData[i][0].lmaxvalue_offset)} );
								ctx.fill();
							}
						}
						prevAnimPc = currentAnimPc;
						continue;
					} else if(currentAnimPc.totVal ==0) {
						ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"LINEDASH",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
						ctx.stroke();
						ctx.setLineDash([]);
						ctx.strokeStyle = "rgba(0,0,0,0)";
					} else {
						ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"LINEDASH",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
						ctx.stroke();
						ctx.setLineDash([]);
						ctx.strokeStyle=setOptionValue(true,1,"STROKECOLOR",ctx,data,statData,data.datasets[i].strokeColor,config.defaultStrokeColor,"strokeColor",i,j,{nullvalue : null} );
					}
					prevAnimPc = currentAnimPc;

					switch(typeof data.datasets[i].data[j]) {
						case "undefined" :
							if (!config.extrapolateMissingData) {
								if(firstpt==-1) continue;
								closebz(pts,ctx,config,i);
								ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"LINEDASH",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
								ctx.stroke();
								ctx.setLineDash([]);
								if (config.datasetFill && firstpt != -1 && setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )!=1) {
									lastxPos=-1;
									ctx.strokeStyle = "rgba(0,0,0,0)";
									ctx.lineTo(statData[i][j-1].xPos, statData[i][j-1].yAxisPos);
									ctx.lineTo(statData[i][firstpt].xPos, statData[i][firstpt].yAxisPos);
									ctx.closePath();
									ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data.datasets[i].fillColor,config.defaultFillColor,"fillColor",i,j,{animationValue: currentAnimPc.mainVal, xPosLeft : statData[i][0].xPos, yPosBottom : Math.max(statData[i][0].yAxisPos,statData[i][0].yAxisPos- ((config.animationLeftToRight) ? 1 : 1*currentAnimPc.mainVal) * statData[i][0].lminvalue_offset), xPosRight : statData[i][data.datasets[i].data.length-1].xPos, yPosTop : Math.min(statData[i][0].yAxisPos, statData[i][0].yAxisPos - ((config.animationLeftToRight) ? 1 : 1*currentAnimPc.mainVal) * statData[i][0].lmaxvalue_offset)} );
									ctx.fill();
								}
								ctx.beginPath();
								prevAnimPc={ mainVal:0 , subVal : 0 };
								firstpt=-1;
							} else if (currentAnimPc.subVal > 0) {
								lastxPos=statData[i][j].xPos + currentAnimPc.subVal*(statData[i][j+1].xPos-statData[i][j].xPos);
								y1=statData[i][statData[i][j+1].prevNotMissing].yAxisPos - statData[i][statData[i][j+1].prevNotMissing].yPosOffset;					
								y2=statData[i][statData[i][j].nextNotMissing].yAxisPos - statData[i][statData[i][j].nextNotMissing].yPosOffset;
								diffnb=statData[i][j].nextNotMissing-statData[i][j+1].prevNotMissing;
								diffnbj=(j)-statData[i][j+1].prevNotMissing;
								fact=(diffnbj+prevAnimPc.subVal)/diffnb;
								y3=y1+fact*(y2-y1);					
//								traceLine(pts,ctx,statData[i][j].xPos + currentAnimPc.subVal*(statData[i][j+1].xPos-statData[i][j].xPos), y3,config,data,statData,i);
								traceLine(pts,ctx,statData[i][statData[i][j].prevNotMissing].xPos + fact*(statData[i][statData[i][j-1].nextNotMissing].xPos-statData[i][statData[i][j].prevNotMissing].xPos) , y3,config,data,statData,i,setYposOrigin(i,j,data,statData));
							}
							break;
						default : 
							ctx.lineWidth = Math.ceil(ctx.chartLineScale*setOptionValue(true,1,"LINEWIDTH",ctx,data,statData,data.datasets[i].datasetStrokeWidth,config.datasetStrokeWidth,"datasetStrokeWidth",i,j,{nullvalue : null} ));
							if (firstpt==-1) {
								firstpt=j;
								ctx.beginPath();
								if(setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )==1) {
									if(typeof statData[i][j].yPosOffsetOrigin != "undefined")ctx.moveTo(statData[i][j].xPos, statData[i][j].yAxisPos - currentAnimPc.mainVal * statData[i][j].yPosOffsetOrigin);
									else ctx.moveTo(statData[i][firstpt].xPos, statData[i][firstpt].xAxisPosY-statData[i][0].zeroY);
									ctx.lineTo(statData[i][j].xPos, statData[i][j].yAxisPos - currentAnimPc.mainVal * statData[i][j].yPosOffset);											
								} else ctx.moveTo(statData[i][j].xPos, statData[i][j].yAxisPos - currentAnimPc.mainVal * statData[i][j].yPosOffset);
								initbz(pts,statData[i][j].xPos, statData[i][j].yAxisPos - currentAnimPc.mainVal * statData[i][j].yPosOffset,i);							
								lastxPos=statData[i][j].xPos;
							} else {
								lastxPos=statData[i][j].xPos;
								traceLine(pts,ctx,statData[i][j].xPos, statData[i][j].yAxisPos - currentAnimPc.mainVal * statData[i][j].yPosOffset,config,data,statData,i,setYposOrigin(i,j,data,statData));
							}	
							if (currentAnimPc.subVal > 0 && statData[i][j].nextNotMissing !=-1 && (config.extrapolateMissing || statData[i][j].nextNotMissing==j+1)) {
								lastxPos=statData[i][j].xPos + currentAnimPc.subVal*(statData[i][j+1].xPos-statData[i][j].xPos);
								y1=statData[i][statData[i][j+1].prevNotMissing].yAxisPos - statData[i][statData[i][j+1].prevNotMissing].yPosOffset;					
								y2=statData[i][statData[i][j].nextNotMissing].yAxisPos - statData[i][statData[i][j].nextNotMissing].yPosOffset;
								y3=y1+currentAnimPc.subVal*(y2-y1);					
								traceLine(pts,ctx,statData[i][j].xPos + currentAnimPc.subVal*(statData[i][statData[i][j].nextNotMissing].xPos-statData[i][j].xPos) , y3,config,data,statData,i,setYposOrigin(i,j,data,statData));
							}
							break
					}
				}
				closebz(pts,ctx,config,i);
				ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"LINEDASH",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
				ctx.stroke();
				ctx.setLineDash([]);
				if (config.datasetFill  && setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )!=1) {
					if (firstpt>=0 ) {
						ctx.strokeStyle = "rgba(0,0,0,0)";
						ctx.lineTo(lastxPos, statData[i][0].xAxisPosY-statData[i][0].zeroY);
						ctx.lineTo(statData[i][firstpt].xPos, statData[i][firstpt].xAxisPosY-statData[i][0].zeroY);
						ctx.closePath();
						ctx.fillStyle=setOptionValue(true,1,"COLOR",ctx,data,statData,data.datasets[i].fillColor,config.defaultFillColor,"fillColor",i,-1,{animationValue: currentAnimPc.mainVal, xPosLeft : statData[i][0].xPos, yPosBottom : Math.max(statData[i][0].yAxisPos,statData[i][0].yAxisPos- ((config.animationLeftToRight) ? 1 : 1*currentAnimPc.mainVal) * statData[i][0].lminvalue_offset), xPosRight : statData[i][data.datasets[i].data.length-1].xPos, yPosTop : Math.min(statData[i][0].yAxisPos, statData[i][0].yAxisPos - ((config.animationLeftToRight) ? 1 : 1*currentAnimPc.mainVal) * statData[i][0].lmaxvalue_offset)} );
						ctx.fill();
					}
				} 
				ctx.restore();
				if (animPc >= 1) {
					for (j = 0; j < data.datasets[i].data.length; j++) {
						if (!(typeof(data.datasets[i].data[j]) == 'undefined')) {
							currentAnimPc = animationCorrection(animPc, data, config, i, j, true);
							if (currentAnimPc.mainVal > 0 || !config.animationLeftToRight) {
								if(setOptionValue(true,1,"POINTDOT",ctx,data,statData,undefined,config.pointDot,"pointDot",i,j,{nullvalue : null} )) {
									ctx.beginPath();
									ctx.fillStyle=setOptionValue(true,1,"MARKERFILLCOLOR",ctx,data,statData,data.datasets[i].pointColor,config.defaultStrokeColor,"pointColor",i,j,{nullvalue: true} );
									ctx.strokeStyle=setOptionValue(true,1,"MARKERSTROKESTYLE",ctx,data,statData,data.datasets[i].pointStrokeColor,config.defaultStrokeColor,"pointStrokeColor",i,j,{nullvalue: true} );
									ctx.lineWidth=setOptionValue(true,ctx.chartLineScale,"MARKERLINEWIDTH",ctx,data,statData,data.datasets[i].pointDotStrokeWidth,config.pointDotStrokeWidth,"pointDotStrokeWidth",i,j,{nullvalue: true} );
									var markerShape=setOptionValue(true,1,"MARKERSHAPE",ctx,data,statData,data.datasets[i].markerShape,config.markerShape,"markerShape",i,j,{nullvalue: true} );
									var markerRadius=setOptionValue(true,ctx.chartSpaceScale,"MARKERRADIUS",ctx,data,statData,data.datasets[i].pointDotRadius,config.pointDotRadius,"pointDotRadius",i,j,{nullvalue: true} );
									var markerStrokeStyle=setOptionValue(true,1,"MARKERSTROKESTYLE",ctx,data,statData,data.datasets[i].pointDotStrokeStyle,config.pointDotStrokeStyle,"pointDotStrokeStyle",i,j,{nullvalue: true} );
									drawMarker(ctx, statData[i][j].xPos , statData[i][j].yAxisPos - currentAnimPc.mainVal * statData[i][j].yPosOffset, markerShape,markerRadius,markerStrokeStyle);							
								}
        						}
						}
					}
				}

				if (animPc >= config.animationStopValue) {
					for (j = 0; j < data.datasets[i].data.length; j++) {
						if (typeof(data.datasets[i].data[j]) == 'undefined') continue;
						if(!(data.datasets[i].mouseDetection==false))jsGraphAnnotate[ctx.ChartNewId][jsGraphAnnotate[ctx.ChartNewId].length] = ["POINT", i, j, statData,setOptionValue(true,1,"ANNOTATEDISPLAY",ctx,data,statData,data.datasets[i].annotateDisplay,config.annotateDisplay,"annotateDisplay",i,j,{nullValue : true})];
						if (setOptionValue(true,1,"INGRAPHDATASHOW",ctx,data,statData,data.datasets[i].inGraphDataShow,config.inGraphDataShow,"inGraphDataShow",i,j,{nullValue : true})) {
	 						ctx.save();
							ctx.textAlign = setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",i,j,{nullValue: true  });
							ctx.textBaseline = setOptionValue(true,1,"INGRAPHDATAVALIGN",ctx,data,statData,undefined,config.inGraphDataVAlign,"inGraphDataVAlign",i,j,{nullValue : true} );
							ctx.font = setOptionValue(true,1,"INGRAPHDATAFONTSTYLE",ctx,data,statData,undefined,config.inGraphDataFontStyle,"inGraphDataFontStyle",i,j,{nullValue : true} ) + ' ' + setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ) + 'px ' + setOptionValue(true,1,"INGRAPHDATAFONTFAMILY",ctx,data,statData,undefined,config.inGraphDataFontFamily,"inGraphDataFontFamily",i,j,{nullValue : true} );
							ctx.fillStyle = setOptionValue(true,1,"INGRAPHDATAFONTCOLOR",ctx,data,statData,undefined,config.inGraphDataFontColor,"inGraphDataFontColor",i,j,{nullValue : true} );
							var paddingTextX = setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGX",ctx,data,statData,undefined,config.inGraphDataPaddingX,"inGraphDataPaddingX",i,j,{nullValue : true} ),
							    paddingTextY = setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATAPADDINGY",ctx,data,statData,undefined,config.inGraphDataPaddingY,"inGraphDataPaddingY",i,j,{nullValue : true} );
							var dispString = tmplbis(setOptionValue(true,1,"INGRAPHDATATMPL",ctx,data,statData,undefined,config.inGraphDataTmpl,"inGraphDataTmpl",i,j,{nullValue : true} ), statData[i][j],config);
							ctx.translate(statData[i][j].xPos + paddingTextX, statData[i][j].yAxisPos - currentAnimPc.mainVal * statData[i][j].yPosOffset - paddingTextY);
							var rotateVal=setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",i,j,{nullValue : true} ) * (Math.PI / 180);
							ctx.rotate(rotateVal);
							setTextBordersAndBackground(ctx,dispString,setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),0,0,setOptionValue(true,1,"INGRAPHDATABORDERS",ctx,data,statData,undefined,config.inGraphDataBorders,"inGraphDataBorders",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSCOLOR",ctx,data,statData,undefined,config.inGraphDataBordersColor,"inGraphDataBordersColor",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartLineScale,"INGRAPHDATABORDERSWIDTH",ctx,data,statData,undefined,config.inGraphDataBordersWidth,"inGraphDataBordersWidth",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSXSPACE",ctx,data,statData,undefined,config.inGraphDataBordersXSpace,"inGraphDataBordersXSpace",i,j,{nullValue : true} ),setOptionValue(true,ctx.chartSpaceScale,"INGRAPHDATABORDERSYSPACE",ctx,data,statData,undefined,config.inGraphDataBordersYSpace,"inGraphDataBordersYSpace",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABORDERSSTYLE",ctx,data,statData,undefined,config.inGraphDataBordersStyle,"inGraphDataBordersStyle",i,j,{nullValue : true} ),setOptionValue(true,1,"INGRAPHDATABACKGROUNDCOLOR",ctx,data,statData,undefined,config.inGraphDataBackgroundColor,"inGraphDataBackgroundColor",i,j,{nullValue : true} ),"INGRAPHDATA",config.inGraphDataBordersRadius);
							ctx.fillTextMultiLine(dispString, 0, 0, ctx.textBaseline, setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,j,{nullValue : true} ),true,config.detectMouseOnText,ctx,"INGRAPHDATA_TEXTMOUSE",rotateVal,statData[i][j].xPos + paddingTextX, statData[i][j].yAxisPos - currentAnimPc.mainVal * statData[i][j].yPosOffset - paddingTextY,i,j);
							ctx.restore();
						}
					}
				}
			}
		};

		
		function initbz(pts,xpos,ypos,i) {
			if (setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )==0 && setOptionValue(true,1,"BEZIERCURVE",ctx,data,statData,undefined,config.bezierCurve,"bezierCurve",i,-1,{nullValue : true})) {
				pts.length=0;
				pts.push(xpos);pts.push(ypos);
			}
			prevypos=ypos;
		} ;
		
		function setYposOrigin(i,j,data,statData) {
			yposOrigin="undefined";
			if(typeof statData[i][j].yPosOffsetOrigin != "undefined")yposOrigin= statData[i][j].yAxisPos - currentAnimPc.mainVal * statData[i][j].yPosOffsetOrigin;
			return(yposOrigin);
		};
		
		
		function traceLine(pts,ctx,xpos,ypos,config,data,statData,i,yposOrigin) {
			if (setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )==0 && setOptionValue(true,1,"BEZIERCURVE",ctx,data,statData,undefined,config.bezierCurve,"bezierCurve",i,-1,{nullValue : true})) {
				pts.push(xpos);	pts.push(ypos);
			} else {
				if(setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )==0)ctx.lineTo(xpos,ypos);
				else if(setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )==1){
					if(yposOrigin != "undefined")ctx.moveTo(xpos, yposOrigin);
					else ctx.moveTo(xpos, statData[i][0].xAxisPosY-statData[i][0].zeroY);
//					ctx.moveTo(xpos, statData[i][0].xAxisPosY-statData[i][0].zeroY);
					ctx.lineTo(xpos,ypos);
				} else if (setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )==2 && typeof prevypos!="undefined"){
					ctx.lineTo(xpos,prevypos);
					ctx.lineTo(xpos,ypos);
					prevypos=ypos;
				}
			}
		} ;
		
		function closebz(pts,ctx,config,i){
		
			if(setOptionValue(true,1,"LINKTYPE",ctx,data,statData,data.datasets[i].linkType,config.linkType,"linkType",i,j,{nullvalue : null} )==0 && setOptionValue(true,1,"BEZIERCURVE",ctx,data,statData,undefined,config.bezierCurve,"bezierCurve",i,-1,{nullValue : true})) {
				minimumpos= statData[i][0].xAxisPosY;
				maximumpos= statData[i][0].xAxisPosY - statData[i][0].calculatedScale.steps*statData[i][0].scaleHop;
				drawSpline(ctx,pts,setOptionValue(true,1,"BEZIERCURVETENSION",ctx,data,statData,undefined,config.bezierCurveTension,"bezierCurveTension",i,-1,{nullValue : true}),minimumpos,maximumpos);
				pts.length=0;			
			}
			prevypos=undefined;
		};		

		//Props to Rob Spencer at scaled innovation for his post on splining between points
		//http://scaledinnovation.com/analytics/splines/aboutSplines.html

		function getControlPoints(x0,y0,x1,y1,x2,y2,t){
			//  x0,y0,x1,y1 are the coordinates of the end (knot) pts of this segment
			//  x2,y2 is the next knot -- not connected here but needed to calculate p2
			//  p1 is the control point calculated here, from x1 back toward x0.
			//  p2 is the next control point, calculated here and returned to become the 
			//  next segment's p1.
			//  t is the 'tension' which controls how far the control points spread.
        	
			//  Scaling factors: distances from this knot to the previous and following knots.
			var d01=Math.sqrt(Math.pow(x1-x0,2)+Math.pow(y1-y0,2));
			var d12=Math.sqrt(Math.pow(x2-x1,2)+Math.pow(y2-y1,2));
   
			var fa=t*d01/(d01+d12);
			var fb=t-fa;
  
			var p1x=x1+fa*(x0-x2);
			var p1y=y1+fa*(y0-y2);

			var p2x=x1-fb*(x0-x2);
			var p2y=y1-fb*(y0-y2);  
    
			return [p1x,p1y,p2x,p2y]
		};

		function drawSpline(ctx,pts,t,minimumpos,maximumpos){
			var cp=[];   // array of control points, as x0,y0,x1,y1,...
			var n=pts.length;

			pts.push(2*pts[n-2]-pts[n-4]);
			pts.push(2*pts[n-1]-pts[n-3]);

			if (n==4){
				ctx.moveTo(pts[0],pts[1]);
				ctx.lineTo(pts[2],pts[3]);
				return;
			}
			// Draw an open curve, not connected at the ends
			for(var ti=0;ti<n-2;ti+=2){
				cp=cp.concat(getControlPoints(pts[ti],pts[ti+1],pts[ti+2],pts[ti+3],pts[ti+4],pts[ti+5],t));
        		}    
			//  For first is a simple quadratics.

			ctx.beginPath();
			ctx.strokeStyle=setOptionValue(true,1,"STROKECOLOR",ctx,data,statData,data.datasets[i].strokeColor,config.defaultStrokeColor,"strokeColor",i,j,{nullvalue : null} );
			ctx.lineWidth = Math.ceil(ctx.chartLineScale*setOptionValue(true,1,"LINEWIDTH",ctx,data,statData,data.datasets[i].datasetStrokeWidth,config.datasetStrokeWidth,"datasetStrokeWidth",i,j,{nullvalue : null} ));
			ctx.moveTo(pts[0],pts[1]);
			ctx.quadraticCurveTo(cp[0],Math.max(Math.min(cp[1],minimumpos),maximumpos),pts[2],pts[3]);
			
//        		ctx.setLineDash(lineStyleFn(config.datasetStrokeStyle));
			ctx.setLineDash(lineStyleFn(setOptionValue(true,1,"LINEDASH",ctx,data,statData,data.datasets[i].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",i,j,{nullvalue : null} )));
			for(ti=2;ti<pts.length-4;ti+=2){
				y1=Math.max(Math.min(cp[2*ti-1],minimumpos),maximumpos);
				y2=Math.max(Math.min(cp[2*ti+1],minimumpos),maximumpos);
				ctx.bezierCurveTo(cp[2*ti-2],y1,cp[2*ti],y2,pts[ti+2],pts[ti+3]);
			}
			ctx.stroke();
		};
 		ctx.setLineDash([]);
	};

	function log10(val) {
		return Math.log(val) / Math.LN10;
	};

	function setRect(ctx, config) {
		if (config.clearRect) {
			if (!config.multiGraph) {

				clear(ctx);
				ctx.clearRect(0, 0, width, height);
			}
		} else {
			clear(ctx);
			ctx.clearRect(0, 0, width, height);
	
			ctx.fillStyle = config.savePngBackgroundColor;
			ctx.strokeStyle = config.savePngBackgroundColor;
			ctx.beginPath();
			ctx.moveTo(0, 0);
			ctx.lineTo(0, ctx.canvas.height);
			ctx.lineTo(ctx.canvas.width, ctx.canvas.height);
			ctx.lineTo(ctx.canvas.width, 0);
			ctx.lineTo(0, 0);
			ctx.stroke();
			ctx.fill();
		}
	};


	function defMouse(ctx,data,config) {

		var mousewheelevt=(/Firefox/i.test(navigator.userAgent))? "DOMMouseScroll" : "mousewheel" 


        	// reset mouseAction to check;

        	if(typeof ctx.mouseAction=="undefined") { ctx.mouseAction=[];}
        	else {
        		for(var i=0;i<ctx.mouseAction;i++){
				if(ctx.mouseAction.substring(0,1)!="/")ctx.mouseAction[i]="/"+ctx.mouseAction[i];
			}
		}

        	// liste mouse options;
		// ----------------------------------------------------		
		// - annotateDisplay : true, annotateFunction: "mousemove"
		// ----------------------------------------------------		
		// - saveScreen : savePngFunction: "mousedown right" - savePng=true
		// ----------------------------------------------------		
                // highLight : false, 	highLightMouseFunction : "mousemove",
		// ----------------------------------------------------		
		// - mouse sortir ou entrer dans une pièce => Pas d'influence sur une action de souris. C'est lié à l'action de annotateFunction
		//      	annotateFunctionIn : inBar,
      		//		annotateFunctionOut : outBar,
		// ----------------------------------------------------		
		// - mouseDownRight	mouseDownLeft: null  mouseDownMiddle: null  
		// - mouseMove: null 	mouseWheel : null    mouseOut: null (lorsque la souris sort du canvas) 	
		// ----------------------------------------------------		
		//  mouse sur texte : detectMouseOnText => Pas d'influence sur une action de souris. C'est lié à une autre action;
		// ----------------------------------------------------		

		function setAction(ctx,action){
			if(ctx.mouseAction.indexOf("/"+action)>=0)ctx.mouseAction[ctx.mouseAction.indexOf("/"+action)]=action;
			else if(ctx.mouseAction.indexOf(action)<0)ctx.mouseAction[ctx.mouseAction.length]=action;
		};
		
		function add_listener(ctx,action) {
			if (isIE() < 9 && isIE() != false) ctx.canvas.attachEvent("on" + action.split(' ')[0], function(event) {
				doMouseAction(event,ctx.ChartNewId, action.split(' ')[0]);
			});
			else ctx.canvas.addEventListener(action.split(' ')[0], function(event) {
				doMouseAction(event,ctx, action.split(' ')[0]);
			}, false);
		};

		// - saveScreen : savePngFunction: "mousedown right" - savePng=true
		if(config.savePng==true) {
			     if(config.savePngFunction=="mousedown left")setAction(ctx,"mousedown 1");
			else if(config.savePngFunction=="mousedown middle")setAction(ctx,"mousedown 2");
			else if(config.savePngFunction=="mousedown right")setAction(ctx,"mousedown 3");
		}
		

		// - annotateDisplay : true, annotateFunction: "mousemove"
		if(config.annotateDisplay==true) {
			if (cursorDivCreated == false) oCursor = new makeCursorObj('divCursor');
			     if(config.annotateFunction=="mousedown left")setAction(ctx,"mousedown 1");
			else if(config.annotateFunction=="mousedown middle")setAction(ctx,"mousedown 2");
			else if(config.annotateFunction=="mousedown right")setAction(ctx,"mousedown 3");
			else if(config.annotateFunction=="mousemove")setAction(ctx,"mousemove");
		}

                // highLight : false, 	highLightMouseFunction : "mousemove",
		if(config.highLight==true) {
			     if(config.highLightMouseFunction=="mousedown left")setAction(ctx,"mousedown 1");
			else if(config.highLightMouseFunction=="mousedown middle")setAction(ctx,"mousedown 2");
			else if(config.highLightMouseFunction=="mousedown right")setAction(ctx,"mousedown 3");
			else if(config.highLightMouseFunction=="mousemove")setAction(ctx,"mousemove");
		}
		
		// mouse actions;
		if(typeof config.mouseMove=="function") setAction(ctx,"mousemove");
		if(typeof config.mouseDownLeft=="function") setAction(ctx,"mousedown 1");
		if(typeof config.mouseDownMiddle=="function") setAction(ctx,"mousedown 2");
		if(typeof config.mouseDownRight=="function") setAction(ctx,"mousedown 3");
		if(typeof config.mouseWheel=="function") setAction(ctx,mousewheelevt);
		if(typeof config.mouseOut=="function") setAction(ctx,"mouseout");
		if(typeof config.mouseDblClick=="function") setAction(ctx,"dblclick");

		var mouseAction=false;		

		// add mouse event
		if((ctx.mouseAction.indexOf("mousemove")>=0 && ctx.mouseAction.indexOf("/mousemove")<0) || (ctx.mouseAction.indexOf("mouseout")>=0 && ctx.mouseAction.indexOf("/mouseout")<0)) {
			setAction(ctx,"mouseout");
			mouseAction=true;
			add_listener(ctx,"mouseout");
			// mouseOut action;			
		}


		if((ctx.mouseAction.indexOf("mousemove")>=0 && ctx.mouseAction.indexOf("/mousemove")<0) || (ctx.mouseAction.indexOf(mousewheelevt)>=0 && ctx.mouseAction.indexOf("/"+mousewheelevt)<0)) {
			setAction(ctx,mousewheelevt);
			mouseAction=true;
			add_listener(ctx,mousewheelevt);
			// mouseWheel action;			
		}

		if(ctx.mouseAction.indexOf("mousemove")>=0 && ctx.mouseAction.indexOf("/mousemove")<0) {
			mouseAction=true;
			add_listener(ctx,"mousemove");
			// mouseMove action;			
		}

		if(ctx.mouseAction.indexOf("dblclick")>=0 && ctx.mouseAction.indexOf("/dblclick")<0) {
			mouseAction=true;
			add_listener(ctx,"dblclick");
			// mouseMove action;			
		}

		if((ctx.mouseAction.indexOf("mousedown 1")>=0 && ctx.mouseAction.indexOf("/mousedown 1")<0) || (ctx.mouseAction.indexOf("mousedown 2")>=0 && ctx.mouseAction.indexOf("/mousedown 2")<0) || (ctx.mouseAction.indexOf("mousedown 3")>=0 && ctx.mouseAction.indexOf("/mousedown 3")<0)) {
			mouseAction=true;
			add_listener(ctx,"mousedown");
			// mouseDown action;			
		}

		// remove contextMenu if forced with option contextMenu or if mouseDownRight is defined
		if((config.contextMenu==false || ctx.mouseAction.indexOf("mousedown 3")>=0) && ctx.mouseAction.indexOf("/removeContextMenu")<0){
			ctx.mouseAction[ctx.mouseAction.length]="removeContextMenu";
			ctx.canvas.oncontextmenu = function (e) {
    				e.preventDefault();
			};
		}
		
		// initialiser les variables nécessaires pour l'action doMouseAction;
                inMouseAction[ctx.ChartNewId]=false;
		mouseActionData[ctx.ChartNewId]={ data : data, config: config, prevShow : -1 };
	};

};

function animationCorrection(animationValue, data, config, vdata, vsubdata, isline) {
	var animValue=animationValue;
	var animSubValue = 0;
	
	if(vsubdata<0) {
		if(vdata < 1*config.animationStartWithData-1) animValue = 1;
		else {
			switch (config.animationByDataset) {
				case "ByArc" :
					animValue = animationValue;
					break;
				case true:
					animValue = animationValue;
					break;
				case false:
				default:
					animValue = animationValue;
					break;
			}
		}
	} else {
		var startVal=Math.max(1,config.animationStartWithDataset);
		var startSubVal=Math.max(1,config.animationStartWithData);
	
		if(vdata < 1*config.animationStartWithDataset-1) animValue = 1;
		else if(vsubdata <1*config.animationStartWithData-1) animValue = 1;
		else {
		        var maxData=0;
			if(config.animationLeftToRight) {
				for(var i=0;i<data.datasets.length;i++)	maxData=Math.max(maxData,data.datasets[i].data.length)
			}
			switch (config.animationByDataset) {
				case true:
					switch(config.animationLeftToRight) {
						case true: 
							var curpos=(vdata-(startVal-1))*(maxData-(startSubVal-1))+(vsubdata-(startSubVal-1));
							maxData=(maxData-(startSubVal-1))*(data.datasets.length-(startVal-1));
							if(animationValue < (curpos/maxData)) {
								animValue=0;
								if(isline && vsubdata+1==startSubVal)animValue=1;
							} else if(animationValue < ((curpos+1)/maxData)) {
								animValue=(animationValue-(curpos/maxData))*maxData;
								if(isline){
									animSubValue=animValue;
									animValue=1;
								}
							} else animValue=1;
							break;
						case false:
						default:
							if(animationValue < (vdata-(startVal-1))/(data.datasets.length-(startVal-1))) {
								animValue=0.001;
							} else if(animationValue < (vdata-(startVal-1)+1)/(data.datasets.length-(startVal-1))) {
								animValue=(data.datasets.length-(startVal-1))*(animationValue-(vdata-(startVal-1))/(data.datasets.length-(startVal-1)));
							} else animValue=1;
							break;
					}
					break;
				case false:
				default:
					switch(config.animationLeftToRight) {
						case true: 
							var curpos=(vsubdata-(startSubVal-1));
							maxData=(maxData-(startSubVal-1));
							if(animationValue < (curpos/maxData)) animValue=0;
							else if(animationValue < ((curpos+1)/maxData)) {
								animValue=(animationValue-(curpos/maxData))*maxData;
								if(isline){
									animSubValue=animValue;
									animValue=1;
								}
							} else animValue=1;
							break;
						case false:
						default:
							animValue = animationValue;
							break;
					}
					break;
			}
		}
	}
	return {
		mainVal: animValue,
		subVal: animSubValue,
		animVal: animValue + animSubValue
	};

};


function showLabels(ctx,data,config,i) {
	var doShowLabels=setOptionValue(true,1,"SHOWLABEL",ctx,data,undefined,undefined,config.showXLabels,"showXLabels",i,-1,undefined,{labelValue: fmtChartJS(config, data.labels[i], config.fmtXLabel), unformatedLabelValue:data.labels[i]});
	if(typeof doShowLabels=="number") {
		if(i>=config.firstLabelToShow-1)doShowLabels=((i+config.firstLabelToShow-1) % parseInt(doShowLabels) ==0 ? true : false);
		else doShowLabels=false;
	}
	return doShowLabels;
};

function showYLabels(ctx,data,config,i,text) {
	var doShowLabels=setOptionValue(true,1,"SHOWYLABEL",ctx,data,undefined,undefined,config.showYLabels,"showYLabels",-1,i,undefined,{labelValue: text});
	if(typeof doShowLabels=="number") {
		if(i>=config.firstYLabelToShow-1)doShowLabels=((i+config.firstYLabelToShow-1) % parseInt(doShowLabels) ==0 ? true : false);
		else doShowLabels=false;
	}
	return doShowLabels;
};


function drawLegend(legendMsr,data,config,ctx,typegraph) {
	var lgtxt;
	if (config.legendBorders == true) {
		ctx.save();
		ctx.setLineDash(lineStyleFn(config.legendBordersStyle));
		ctx.beginPath();
		ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.legendBordersWidth);
		ctx.strokeStyle = config.legendBordersColors;
		ctx.moveTo(legendMsr.xLegendBorderPos, legendMsr.yLegendBorderPos);
		ctx.lineTo(legendMsr.xLegendBorderPos, legendMsr.yLegendBorderPos + legendMsr.legendBorderHeight);
		ctx.lineTo(legendMsr.xLegendBorderPos + legendMsr.legendBorderWidth, legendMsr.yLegendBorderPos + legendMsr.legendBorderHeight);
		ctx.lineTo(legendMsr.xLegendBorderPos + legendMsr.legendBorderWidth, legendMsr.yLegendBorderPos);
		ctx.lineTo(legendMsr.xLegendBorderPos, legendMsr.yLegendBorderPos);
		//ctx.lineTo(legendMsr.xLegendBorderPos + legendMsr.legendBorderWidth, legendMsr.yLegendBorderPos);
		//ctx.lineTo(legendMsr.xLegendBorderPos, legendMsr.yLegendBorderPos);
		//ctx.lineTo(legendMsr.xLegendBorderPos, legendMsr.yLegendBorderPos + legendMsr.legendBorderHeight);

					
		ctx.stroke();
		ctx.closePath();
		ctx.setLineDash([]);
		
		ctx.fillStyle = "rgba(0,0,0,0)"; // config.legendFillColor;
		ctx.fillStyle = config.legendFillColor;
		ctx.fill();
		ctx.restore();
	}
	nbcols = legendMsr.nbLegendCols - 1;
	ypos = legendMsr.yFirstLegendTextPos - ((Math.ceil(ctx.chartTextScale*config.legendFontSize)) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextVertical));
	xpos = 0;
	if (legendMsr.drawLegendOnData) fromi = data.datasets.length;
	else fromi = data.length;
	for (var i = fromi - 1; i >= 0; i--) {
		orderi = i;
		if (legendMsr.reverseLegend) {
			if (legendMsr.drawLegendOnData) orderi = data.datasets.length - i - 1;
			else orderi = data.length - i - 1;
		}
		if (legendMsr.drawLegendOnData) tpof = typeof(data.datasets[orderi].title);
		else tpof = typeof(data[orderi].title)
		if (tpof == "string") {
			if (legendMsr.drawLegendOnData) lgtxt = fmtChartJS(config, data.datasets[orderi].title, config.fmtLegend).trim();
			else lgtxt = fmtChartJS(config, data[orderi].title, config.fmtLegend).trim();
			if (lgtxt != "") {
				nbcols++;
				if (nbcols == legendMsr.nbLegendCols) {
					nbcols = 0;
					xpos = legendMsr.xFirstLegendTextPos;
					ypos += (Math.ceil(ctx.chartTextScale*config.legendFontSize)) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextVertical);
				} else {
					xpos += legendMsr.widestLegend + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenTextHorizontal);
				}
				ctx.save();
				ctx.beginPath();
				var lgdbox=legendMsr.legendBox;
				if(ctx.tpchart=="Bar" || ctx.tpchart=="StackedBar") if (data.datasets[orderi].type=="Line" && (!config.datasetFill || setOptionValue(false,1,"LINKTYPE",ctx,data,undefined,data.datasets[orderi].linkType,config.linkType,"linkType",orderi,-1,{nullvalue : null} )==1)) lgdbox=false;
				if (lgdbox) {
					if (legendMsr.drawLegendOnData) {
	 					ctx.lineWidth = Math.ceil(ctx.chartLineScale*setOptionValue(false,1,"LINEWIDTH",ctx,data,undefined,data.datasets[orderi].datasetStrokeWidth,config.datasetStrokeWidth,"datasetStrokeWidth",orderi,-1,{nullvalue : null} ));
					} else {
						ctx.lineWidth = Math.ceil(ctx.chartLineScale*config.datasetStrokeWidth);
					}
					ctx.beginPath();
					if (legendMsr.drawLegendOnData) {
						ctx.strokeStyle=setOptionValue(false,1,"LEGENDSTROKECOLOR",ctx,data,undefined,data.datasets[orderi].strokeColor,config.defaultFillColor,"strokeColor",orderi,-1,{animationValue: 1, xPosLeft : xpos, yPosBottom : ypos, xPosRight : xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), yPosTop : ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize))} );
						ctx.setLineDash(lineStyleFn(setOptionValue(false,1,"LEGENDLINEDASH",ctx,data,undefined,data.datasets[orderi].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",orderi,-1,{animationValue: 1, xPosLeft : xpos, yPosBottom : ypos, xPosRight : xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), yPosTop : ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize))} )));

					} else {
						ctx.strokeStyle=setOptionValue(false,1,"LEGENDSTROKECOLOR",ctx,data,undefined,data[orderi].strokeColor,config.defaultFillColor,"strokeColor",orderi,-1,{animationValue: 1, xPosLeft : xpos, yPosBottom : ypos, xPosRight : xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), yPosTop : ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize))} );
						ctx.setLineDash(lineStyleFn(setOptionValue(false,1,"LEGENDSEGMENTTROKESTYLE",ctx,data,undefined,data[orderi].segmentStrokeStyle,config.segmentStrokeStyle,"segmentStrokeStyle",orderi,-1,{animationValue: 1, xPosLeft : xpos, yPosBottom : ypos, xPosRight : xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), yPosTop : ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize))} )));
					}
					ctx.moveTo(xpos, ypos);
					ctx.lineTo(xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), ypos);
					ctx.lineTo(xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize)));
					ctx.lineTo(xpos, ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize)));
					ctx.lineTo(xpos, ypos);
					ctx.stroke();
					ctx.closePath();
					if (legendMsr.drawLegendOnData) {
						ctx.fillStyle=setOptionValue(false,1,"LEGENDFILLCOLOR",ctx,data,undefined,data.datasets[orderi].fillColor,config.defaultFillColor,"fillColor",orderi,-1,{animationValue: 1, xPosLeft : xpos, yPosBottom : ypos, xPosRight : xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), yPosTop : ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize))} );
					} else {
						ctx.fillStyle=setOptionValue(false,1,"LEGENDFILLCOLOR",ctx,data,undefined,data[orderi].color,config.defaultFillColor,"color",orderi,-1,{animationValue: 1, xPosLeft : xpos, yPosBottom : ypos, xPosRight : xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), yPosTop : ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize))} );
					}
					ctx.fill();
				} else {
					ctx.lineWidth = config.legendColorIndicatorStrokeWidth ?
						config.legendColorIndicatorStrokeWidth : Math.ceil(ctx.chartLineScale*setOptionValue(false,1,"LINEWIDTH",ctx,data,undefined,data.datasets[orderi].datasetStrokeWidth,config.datasetStrokeWidth,"datasetStrokeWidth",orderi,-1,{nullvalue : null} ));
					if (config.legendColorIndicatorStrokeWidth && config.legendColorIndicatorStrokeWidth > (Math.ceil(ctx.chartTextScale*config.legendFontSize))) {
						ctx.lineWidth = (Math.ceil(ctx.chartTextScale*config.legendFontSize));
					}
					if (legendMsr.drawLegendOnData) {
						ctx.strokeStyle=setOptionValue(false,1,"LEGENDSTROKECOLOR",ctx,data,undefined,data.datasets[orderi].strokeColor,config.defaultFillColor,"strokeColor",orderi,-1,{animationValue: 1, xPosLeft : xpos, yPosBottom : ypos, xPosRight : xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), yPosTop : ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize))} );
						ctx.setLineDash(lineStyleFn(setOptionValue(false,1,"LEGENDLINEDASH",ctx,data,undefined,data.datasets[orderi].datasetStrokeStyle,config.datasetStrokeStyle,"datasetStrokeStyle",orderi,-1,{animationValue: 1, xPosLeft : xpos, yPosBottom : ypos, xPosRight : xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), yPosTop : ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize))} )));
					} else {
						ctx.strokeStyle=setOptionValue(false,1,"LEGENDSTROKECOLOR",ctx,data,undefined,data[orderi].strokeColor,config.defaultFillColor,"strokeColor",orderi,-1,{animationValue: 1, xPosLeft : xpos, yPosBottom : ypos, xPosRight : xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), yPosTop : ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize))} );
						ctx.setLineDash(lineStyleFn(setOptionValue(false,1,"LEGENDSEGMENTTROKESTYLE",ctx,data,undefined,data[orderi].segmentStrokeStyle,config.segmentStrokeStyle,"segmentStrokeStyle",orderi,-1,{animationValue: 1, xPosLeft : xpos, yPosBottom : ypos, xPosRight : xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize), yPosTop : ypos - (Math.ceil(ctx.chartTextScale*config.legendFontSize))} )));
					}

					ctx.moveTo(xpos + 2, ypos - ((Math.ceil(ctx.chartTextScale*config.legendFontSize)) / 2));
					ctx.lineTo(xpos + 2 + Math.ceil(ctx.chartTextScale*config.legendBlockSize), ypos - ((Math.ceil(ctx.chartTextScale*config.legendFontSize)) / 2));
					ctx.stroke();

					
					ctx.fill();

					if(config.pointDot) {
						ctx.beginPath();
				 		ctx.fillStyle=setOptionValue(false,1,"LEGENDMARKERFILLCOLOR",ctx,data,undefined,data.datasets[orderi].pointColor,config.defaultStrokeColor,"pointColor",orderi,-1,{nullvalue: true} );
						ctx.strokeStyle=setOptionValue(false,1,"LEGENDMARKERSTROKESTYLE",ctx,data,undefined,data.datasets[orderi].pointStrokeColor,config.defaultStrokeColor,"pointStrokeColor",orderi,-1,{nullvalue: true} );
						ctx.lineWidth=setOptionValue(false,ctx.chartLineScale,"LEGENDMARKERLINEWIDTH",ctx,data,undefined,data.datasets[orderi].pointDotStrokeWidth,config.pointDotStrokeWidth,"pointDotStrokeWidth",orderi,-1,{nullvalue: true} );
                        	
						var markerShape=setOptionValue(false,1,"LEGENDMARKERSHAPE",ctx,data,undefined,data.datasets[orderi].markerShape,config.markerShape,"markerShape",orderi,-1,{nullvalue: true} );
						var markerRadius=setOptionValue(false,ctx.chartSpaceScale,"LEGENDMARKERRADIUS",ctx,data,undefined,data.datasets[orderi].pointDotRadius,config.pointDotRadius,"pointDotRadius",orderi,-1,{nullvalue: true} );
						var markerStrokeStyle=setOptionValue(false,1,"LEGENDMARKERSTROKESTYLE",ctx,data,undefined,data.datasets[orderi].pointDotStrokeStyle,config.pointDotStrokeStyle,"pointDotStrokeStyle",orderi,-1,{nullvalue: true} );
						drawMarker(ctx,xpos + 2 + Math.ceil(ctx.chartTextScale*config.legendBlockSize)/2, ypos - ((Math.ceil(ctx.chartTextScale*config.legendFontSize)) / 2), markerShape,markerRadius,markerStrokeStyle);							
					}
					ctx.fill();

				}
				ctx.restore();
				ctx.save();
				ctx.beginPath();
				ctx.font = config.legendFontStyle + " " + (Math.ceil(ctx.chartTextScale*config.legendFontSize)).toString() + "px " + config.legendFontFamily;
				ctx.fillStyle = setOptionValue(false,1,"LEGENDFONTCOLOR",ctx,data,undefined,undefined,config.legendFontColor,"legendFontColor",orderi,-1,{nullvalue: true} );
				ctx.textAlign = "left";
				ctx.textBaseline = "bottom";
				ctx.translate(xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenBoxAndText), ypos);
//				ctx.fillText(lgtxt, 0, 0);
				ctx.fillTextMultiLine(lgtxt, 0, 0, ctx.textBaseline, Math.ceil(ctx.chartTextScale*config.legendFontSize), true,config.detectMouseOnText,ctx,"LEGEND_TEXTMOUSE",0,xpos + Math.ceil(ctx.chartTextScale*config.legendBlockSize) + Math.ceil(ctx.chartSpaceScale*config.legendSpaceBetweenBoxAndText), ypos,orderi,-1);

				ctx.restore();
			}
		}
	}
};

function drawMarker(ctx,xpos,ypos,marker,markersize,markerStrokeStyle) {
	ctx.setLineDash(lineStyleFn(markerStrokeStyle));
	switch (marker) {
		case "square":
			ctx.rect(xpos-markersize,ypos-markersize,2*markersize,2*markersize);
			ctx.stroke();
			ctx.fill();
			ctx.setLineDash([]);
			break;
		case "triangle":
			pointA_x=0;
			pointA_y=2/3*markersize;
			ctx.moveTo(xpos,ypos-pointA_y);
			ctx.lineTo(xpos+pointA_y*Math.sin(4/3),ypos+pointA_y*Math.cos(4/3));
			ctx.lineTo(xpos-pointA_y*Math.sin(4/3),ypos+pointA_y*Math.cos(4/3));
			ctx.lineTo(xpos,ypos-pointA_y);
			ctx.stroke();
			ctx.fill();
			ctx.setLineDash([]);
			break;
		case "diamond":
			ctx.moveTo(xpos, ypos+markersize);
			ctx.lineTo(xpos+markersize, ypos);
			ctx.lineTo(xpos, ypos-markersize);
			ctx.lineTo(xpos-markersize, ypos);
			ctx.lineTo(xpos, ypos+markersize);
			ctx.stroke();
			ctx.fill();
			ctx.setLineDash([]);
			break;
		case "plus":
			ctx.moveTo(xpos, ypos-markersize);
			ctx.lineTo(xpos, ypos+markersize);
			ctx.moveTo(xpos-markersize, ypos);
			ctx.lineTo(xpos+markersize, ypos);
			ctx.stroke();
			ctx.setLineDash([]);
			break;
		case "cross":
			ctx.moveTo(xpos-markersize, ypos-markersize);
			ctx.lineTo(xpos+markersize, ypos+markersize);
			ctx.moveTo(xpos-markersize, ypos+markersize);
			ctx.lineTo(xpos+markersize, ypos-markersize);
			ctx.stroke();
			ctx.setLineDash([]);
			break;
		case "circle":
		default:
			ctx.arc(xpos, ypos, markersize, 0, 2*Math.PI * 1, true);
			ctx.stroke();
			ctx.fill();
			ctx.setLineDash([]);
			break;
	}
};

function initPassVariableData_part1(data,config,ctx) {
var i,j,result, mxvalue ,mnvalue, cumvalue, totvalue,lmaxvalue,lminvalue,lgtxt,lgtxt2,tp,prevpos,firstNotMissingi,lastNotMissingi,firstNotMissingj,lastNotMissingj,grandtotal;
switch(ctx.tpdata) {
	case 1 :

		result=[];
		var segmentAngle,cumulativeAngle,realCumulativeAngle;

		var realAmplitude = (((config.totalAmplitude * (Math.PI / 180) + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI) ; 
		if(realAmplitude <= config.zeroValue)realAmplitude=2*Math.PI;

		cumulativeAngle = (((-config.startAngle * (Math.PI / 180) + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI) ; 
		realCumulativeAngle = (((config.startAngle * (Math.PI / 180) + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI) ; 

		startAngle=cumulativeAngle;
		totvalue = 0;
		notemptyval=0;
		var firstNotMissing = -1;
		var lastNotMissing = -1;
		var prevNotMissing = -1;
		mxvalue=-Number.MAX_VALUE;
		mnvalue=Number.MAX_VALUE;
		for (i = 0; i < data.length; i++) {
			if(ctx.tpchart != "PolarArea" && 1*data[i].value<0)continue;
			if (!(typeof(data[i].value) == 'undefined')) { 
				if(firstNotMissing==-1)firstNotMissing=i;
				mxvalue=Math.max(mxvalue,1*data[i].value);
				mnvalue=Math.min(mnvalue,1*data[i].value);
				notemptyval++; 
				totvalue += 1 * data[i].value;
				lastNotMissing=i;
			}
		}
	
		cumvalue=0;
		var prevMissing=-1;
		for(i=0;i<data.length;i++) {
			if (typeof(data[i].title) == "string") lgtxt = data[i].title.trim();
			else lgtxt = "";
			if (!(typeof(data[i].value) == 'undefined') && (ctx.tpchart == "PolarArea" || 1*data[i].value>=0)) {
//				if(ctx.tpchart=="PolarArea") { if(notemptyval>0)segmentAngle= (Math.PI *2)/notemptyval; else segmentAngle=0; }
//				else segmentAngle = (1 * data[i].value / totvalue) * (Math.PI * 2);
				if(ctx.tpchart=="PolarArea") { if(notemptyval>0)segmentAngle= realAmplitude/notemptyval; else segmentAngle=0; }
				else segmentAngle = (1 * data[i].value / totvalue) * realAmplitude;
				if (segmentAngle >= Math.PI * 2) segmentAngle = Math.PI * 2 - 0.001; // bug on Android when segmentAngle is >= 2*PI;
				cumvalue += 1 * data[i].value;
				result[i]= {
					config: config,
					v1: fmtChartJS(config, lgtxt, config.fmtV1),
					v2: fmtChartJS(config, 1 * data[i].value, config.fmtV2),
					v3: fmtChartJS(config, cumvalue, config.fmtV3),
					v4: fmtChartJS(config, totvalue, config.fmtV4),
					v5: fmtChartJS(config, segmentAngle, config.fmtV5),
					v6: roundToWithThousands(config, fmtChartJS(config, 100 * data[i].value / totvalue, config.fmtV6), config.roundPct),
					v7 : 0,
					v8 : 0,
					v9 : 0,
					v10 : 0,
					v11: fmtChartJS(config, cumulativeAngle - segmentAngle, config.fmtV11),
					v12: fmtChartJS(config, cumulativeAngle, config.fmtV12),
					v13: fmtChartJS(config, i, config.fmtV13),
					lgtxt: lgtxt,
					datavalue: 1 * data[i].value,
					cumvalue: cumvalue,
					totvalue: totvalue,
					segmentAngle: segmentAngle,
					firstAngle : startAngle,
					pctvalue: 100 * data[i].value / totvalue,
					startAngle: cumulativeAngle,
					realStartAngle : realCumulativeAngle,
					endAngle: cumulativeAngle+segmentAngle,
					maxvalue : mxvalue,
					minvalue : mnvalue,
					i: i,
					firstNotMissing : firstNotMissing,
					lastNotMissing : lastNotMissing,
					prevNotMissing : prevNotMissing,
					prevMissing : prevMissing,
					nextNotMissing : -1,
				        radiusOffset : 0,
					midPosX : 0,
					midPosY : 0,
					int_radius : 0,
					ext_radius : 0,
					data: data
				};   
				cumulativeAngle += segmentAngle;
				realCumulativeAngle -= segmentAngle;
				if(prevNotMissing != -1) result[prevNotMissing].nextNotMissing=i;
				prevNotMissing = i;
        		} else {
				result[i]={ 
					v1:lgtxt,
					maxvalue : mxvalue,
					minvalue : mnvalue,
					i: i,
					firstNotMissing : firstNotMissing,
					lastNotMissing : lastNotMissing,
					prevNotMissing : prevNotMissing
				 }; 
				 prevMissing=i;
			}
		}
		break;
	case 0:
	default : 
		var axis;
		result=[];
		mxvalue=[];
		mxvalue[0]=[];
		mxvalue[1]=[];
		mnvalue=[];
		mnvalue[0]=[];
		mnvalue[1]=[];
		cumvalue=[];
		cumvalue[0]=[];
		cumvalue[1]=[];
		totvalue=[];
		totvalue[0]=[];
		totvalue[1]=[];
		lmaxvalue=[];
		lmaxvalue[0]=[];
		lmaxvalue[1]=[];
		lminvalue=[];
		lminvalue[0]=[];
		lminvalue[1]=[];
		prevpos=[];
		firstNotMissingi=[];
		lastNotMissingi=[];
		firstNotMissingj=[];
		lastNotMissingj=[];
		prevpos[0]=[];
		prevpos[1]=[];
		grandtotal=0;

		for (i = 0; i < data.datasets.length; i++) {
			// BUG when all data are missing !
			if (typeof data.datasets[i].xPos != "undefined" && tpdraw(ctx,data.datasets[i])=="Line") {
				for(j=data.datasets[i].data.length;j<data.datasets[i].xPos.length;j++)data.datasets[i].data.push(undefined);
			} else {
				for(j=data.datasets[i].data.length;j<data.labels.length;j++)data.datasets[i].data.push(undefined);
			}
				

			if(data.datasets[i].axis == 2) axis=0;else axis=1;
			result[i]=[];
			lmaxvalue[0][i]=-Number.MAX_VALUE;
			lmaxvalue[1][i]=-Number.MAX_VALUE;
			lminvalue[0][i]=Number.MAX_VALUE;
			lminvalue[1][i]=Number.MAX_VALUE;
			firstNotMissingi[i]=-1;
			lastNotMissingi[i]=-1;
			for (j = 0; j < data.datasets[i].data.length; j++) {

				if(typeof firstNotMissingj[j]== "undefined"){
					firstNotMissingj[j]=-1;
					lastNotMissingj[j]=-1;
					totvalue[0][j] = 0; 
					mxvalue[0][j]=-Number.MAX_VALUE;
					mnvalue[0][j]=Number.MAX_VALUE;
					totvalue[1][j] = 0; 
					mxvalue[1][j]=-Number.MAX_VALUE;
					mnvalue[1][j]=Number.MAX_VALUE;
				}
				if (!(typeof data.datasets[i].data[j] == 'undefined')) {
					grandtotal += 1 * data.datasets[i].data[j];
					if(firstNotMissingi[i]==-1)firstNotMissingi[i]=j;
					lastNotMissingi[i]=j;
					if(firstNotMissingj[j]==-1)firstNotMissingj[j]=i;
					lastNotMissingj[j]=i;
					totvalue[axis][j] += 1 * data.datasets[i].data[j]; 
					mxvalue[axis][j] =Math.max(mxvalue[axis][j],1 * data.datasets[i].data[j]);
					mnvalue[axis][j] =Math.min(mnvalue[axis][j],1 * data.datasets[i].data[j]);
					lmaxvalue[axis][i] =Math.max(lmaxvalue[axis][i],1 * data.datasets[i].data[j]);
					lminvalue[axis][i] =Math.min(lminvalue[axis][i],1 * data.datasets[i].data[j]);
				}
			}
		}
		
		for (i = 0; i < data.datasets.length; i++) {
			if(data.datasets[i].axis == 2) axis=0;else axis=1;
			if (typeof(data.datasets[i].title) == "string") lgtxt = data.datasets[i].title.trim();
			else lgtxt = "";
			var prevnotemptyj=-1;
			var prevemptyj=-1;
			for (j = 0; j < data.datasets[i].data.length; j++) {
			
				if(typeof cumvalue[0][j]== "undefined"){cumvalue[0][j] = 0; prevpos[0][j]=-1;cumvalue[1][j] = 0; prevpos[1][j]=-1; }
				lgtxt2 = "";
				if (typeof data.datasets[i].xPos != "undefined") {
					if (!(typeof data.datasets[i].xPos[j] == "undefined")) lgtxt2 = data.datasets[i].xPos[j];
				}
				if (lgtxt2 == "" && !(typeof(data.labels[j]) == "undefined")) lgtxt2 = data.labels[j];
				if (typeof lgtxt2 == "string") lgtxt2 = lgtxt2.trim();

//				if (!(typeof(data.datasets[i].data[j]) == 'undefined') && data.datasets[i].data[j] != 0) {
				if (!(typeof(data.datasets[i].data[j]) == 'undefined') ) {
					cumvalue[axis][j]+=1*data.datasets[i].data[j];
					switch(tpdraw(ctx,data.datasets[i]))  {
						case "Bar" :
						case "StackedBar" :
						case "HorizontalBar" :
						case "HorizontalStackedBar" :
							result[i][j]= {
								config: config,
								v1: fmtChartJS(config, lgtxt, config.fmtV1),
								v2: fmtChartJS(config, lgtxt2, config.fmtV2),
								v3: fmtChartJS(config, 1 * data.datasets[i].data[j], config.fmtV3),
								v4: fmtChartJS(config, cumvalue[axis][j], config.fmtV4),
								v5: fmtChartJS(config, totvalue[axis][j], config.fmtV5),
								v6: roundToWithThousands(config, fmtChartJS(config, 100 * data.datasets[i].data[j] / totvalue[axis][j], config.fmtV6), config.roundPct),
								v6T: roundToWithThousands(config, fmtChartJS(config, 100 * data.datasets[i].data[j] / grandtotal, config.fmtV6T), config.roundPct),
								v11: fmtChartJS(config, i, config.fmtV11),
								v12: fmtChartJS(config, j, config.fmtV12),
								lgtxt: lgtxt,
								lgtxt2: lgtxt2,
								datavalue: 1 * data.datasets[i].data[j],
								cumvalue: cumvalue[axis][j],
								totvalue: totvalue[axis][j],
								pctvalue: 100 * data.datasets[i].data[j] / totvalue[axis][j],
								pctvalueT: 100 * data.datasets[i].data[j] / grandtotal,
								maxvalue : mxvalue[axis][j],
								minvalue : mnvalue[axis][j],
								lmaxvalue : lmaxvalue[axis][i],
								lminvalue : lminvalue[axis][i],
								grandtotal : grandtotal,
								firstNotMissing : firstNotMissingj[j],
								lastNotMissing : lastNotMissingj[j],
								prevNotMissing : prevnotemptyj,
								prevMissing : prevemptyj,
								nextNotMissing : -1,
								j: j,
								i: i,
								data: data
							};
							if(1 * data.datasets[i].data[j]==0 && (tpdraw(ctx,data.datasets[i])=="HorizontalStackedBar" || tpdraw(ctx,data.datasets[i])=="StackedBar"))result[i][j].v3="";
							break;
						case "Line" :
						case "Radar" :
							result[i][j]= {
								config: config,
								v1: fmtChartJS(config, lgtxt, config.fmtV1),
								v2: fmtChartJS(config, lgtxt2, config.fmtV2),
								v3: fmtChartJS(config, 1 * data.datasets[i].data[j], config.fmtV3),
								v5: fmtChartJS(config, 1 * data.datasets[i].data[j], config.fmtV5),
								v6: fmtChartJS(config, mxvalue[axis][j], config.fmtV6),
								v7: fmtChartJS(config, totvalue[axis][j], config.fmtV7),
								v8: roundToWithThousands(config, fmtChartJS(config, 100 * data.datasets[i].data[j] / totvalue[axis][j], config.fmtV8), config.roundPct),
								v8T: roundToWithThousands(config, fmtChartJS(config, 100 * data.datasets[i].data[j] / grandtotal, config.fmtV8T), config.roundPct),
								v11: fmtChartJS(config, i, config.fmtV11),
								v12: fmtChartJS(config, j, config.fmtV12),
								lgtxt: lgtxt,
								lgtxt2: lgtxt2,
								datavalue: 1 * data.datasets[i].data[j],
								diffnext: 1 * data.datasets[i].data[j],
								pctvalue: 100 * data.datasets[i].data[j] / totvalue[axis][j],
								pctvalueT: 100 * data.datasets[i].data[j] / grandtotal,
								totvalue : totvalue[axis][j],
								cumvalue: cumvalue[axis][j],
								maxvalue : mxvalue[axis][j],
								minvalue : mnvalue[axis][j],
								lmaxvalue : lmaxvalue[axis][i],
								lminvalue : lminvalue[axis][i],
								grandtotal : grandtotal,
								firstNotMissing : firstNotMissingi[i],
								lastNotMissing : lastNotMissingi[i],
								prevNotMissing : prevnotemptyj,
								prevMissing : prevemptyj,
								nextNotMissing : -1,
								j: j,
								i: i,
								data: data
							};
							if(prevpos[axis][j]>=0){
								result[i][j].v4=fmtChartJS(config, (prevpos[axis][j] != -1 ? 1 * data.datasets[i].data[j]-result[prevpos[axis][j]][j].datavalue : 1 * data.datasets[i].data[j]), config.fmtV4);
								result[i][j].diffprev=(prevpos[axis][j] != -1 ? 1 * data.datasets[i].data[j]-result[prevpos[axis][j]][j].datavalue : 1 * data.datasets[i].data[j]);
								result[prevpos[axis][j]][j].diffnext=data.datasets[prevpos[axis][j]].data[j] - data.datasets[i].data[j];
								result[prevpos[axis][j]][j].v5=result[prevpos[axis][j]][j].diffnext;
							} else {
								result[i][j].v4=1 * data.datasets[i].data[j];
								
							}
							prevpos[axis][j]=i;
							break;
						default:
							break;
					}
					if(!(typeof(data.datasets[i].data[j]) == 'undefined')) {
						if(prevnotemptyj!= -1) {for(k=prevnotemptyj;k<j;k++) result[i][k].nextNotMissing=j;}	
						prevnotemptyj=j;
					}
				} else {
					prevemptyj=j; 
					switch(tpdraw(ctx,data.datasets[i]))  {
						case "Bar" :
						case "StackedBar" :
						case "HorizontalBar" :
						case "HorizontalStackedBar" :
							result[i][j] ={ 
								v1:lgtxt,
								lmaxvalue : lmaxvalue[axis][i],
								lminvalue : lminvalue[axis][i],
								firstNotMissing : firstNotMissingj[j],
								lastNotMissing : lastNotMissingj[j],
								prevNotMissing : prevnotemptyj,
								prevMissing : prevemptyj,
								grandtotal : grandtotal
								 }; 
							break;
						case "Line" :
						case "Radar" :
							result[i][j] ={ 
								v1:lgtxt,
								lmaxvalue : lmaxvalue[axis][i],
								lminvalue : lminvalue[axis][i],
								firstNotMissing : firstNotMissingi[i],
								lastNotMissing : lastNotMissingi[i],
								prevNotMissing : prevnotemptyj,
								prevMissing : prevemptyj,
								grandtotal : grandtotal
								 }; 
							break;
						}
				}
			}
		}
		break;
	}

	
return result;

};

function initPassVariableData_part2(statData,data,config,ctx,othervars) {
var realbars=0;
var i,j;
switch(ctx.tpdata) {
	case 1 :
		for(i=0;i<data.length;i++) {
			statData[i].v7= fmtChartJS(config, othervars.midPosX, config.fmtV7);
			statData[i].v8= fmtChartJS(config, othervars.midPosY, config.fmtV8),
			statData[i].v9= fmtChartJS(config, othervars.int_radius, config.fmtV9);
			statData[i].v10= fmtChartJS(config, othervars.ext_radius, config.fmtV10);
			if(ctx.tpchart=="PolarArea") {
				statData[i].radiusOffset= calculateOffset(config.logarithmic, 1 * data[i].value, othervars.calculatedScale, othervars.scaleHop);
				statData[i].v10= fmtChartJS(config, statData[i].radiusOffset, config.fmtV10); 
			}
			else {
				statData[i].v10= fmtChartJS(config, othervars.ext_radius, config.fmtV10); 
				statData[i].radiusOffset=othervars.ext_radius;
			}
			statData[i].outerVal= othervars.outerVal;
			statData[i].midPosX= othervars.midPosX;
			statData[i].midPosY= othervars.midPosY;
			statData[i].calculatedScale=othervars.calculatedScale;
			statData[i].scaleHop=othervars.scaleHop;
			statData[i].int_radius= othervars.int_radius;
			statData[i].ext_radius= othervars.ext_radius;
		}
		break;
	case 0:
	default :
		var tempp = new Array(data.datasets.length);
		var tempn = new Array(data.datasets.length);
		for (i = 0; i < data.datasets.length; i++) {
			switch(tpdraw(ctx,data.datasets[i])) {
				case "Line" :
					for (j = 0; j < data.datasets[i].data.length; j++) {
						statData[i][j].xAxisPosY = othervars.xAxisPosY;
						statData[i][j].yAxisPosX = othervars.yAxisPosX;
						statData[i][j].valueHop = othervars.valueHop;
						statData[i][j].nbValueHop = othervars.nbValueHop;
						if (data.datasets[i].axis == 2) {
							statData[i][j].scaleHop = othervars.scaleHop2;
							statData[i][j].zeroY = othervars.zeroY2;
							statData[i][j].calculatedScale = othervars.calculatedScale2;
							statData[i][j].logarithmic = othervars.logarithmic2;
						} else {
							statData[i][j].scaleHop = othervars.scaleHop;
							statData[i][j].zeroY = othervars.zeroY;
							statData[i][j].calculatedScale = othervars.calculatedScale;
							statData[i][j].logarithmic  = othervars.logarithmic;
						}
						statData[i][j].xPos=xPos(i,j,data,othervars.yAxisPosX,othervars.valueHop,othervars.nbValueHop);
						statData[i][j].yAxisPos=othervars.xAxisPosY - statData[i][j].zeroY;
						if(ctx.tpchart=="Bar" || ctx.tpchart=="StackedBar") {
							statData[i][j].xPos+=(othervars.valueHop/2);
							statData[i][j].yAxisPosX += (othervars.valueHop/2);
						}			
						if(j==0) {
							statData[i][j].lmaxvalue_offset=calculateOffset(statData[i][j].logarithmic, statData[i][j].lmaxvalue, statData[i][j].calculatedScale, statData[i][j].scaleHop) - statData[i][j].zeroY;
							statData[i][j].lminvalue_offset=calculateOffset(statData[i][j].logarithmic, statData[i][j].lminvalue, statData[i][j].calculatedScale, statData[i][j].scaleHop) - statData[i][j].zeroY;
						} else {
							statData[i][j].lmaxvalue_offset=statData[i][0].lmaxvalue_offset;
							statData[i][j].lminvalue_offset=statData[i][0].lminvalue_offset;
						}
						
						if (!(typeof(data.datasets[i].data[j]) == 'undefined')) {
							statData[i][j].yPosOffset= calculateOffset(statData[i][j].logarithmic, data.datasets[i].data[j], statData[i][j].calculatedScale, statData[i][j].scaleHop) - statData[i][j].zeroY;
							statData[i][j].posY=statData[i][j].yAxisPos - statData[i][j].yPosOffset;
						}
						if (typeof(data.datasets[i].origin) == 'object') {
							if (!(typeof(data.datasets[i].origin[j]) == 'undefined')) {
								statData[i][j].yPosOffsetOrigin= calculateOffset(statData[i][j].logarithmic, data.datasets[i].origin[j], statData[i][j].calculatedScale, statData[i][j].scaleHop) - statData[i][j].zeroY;
								statData[i][j].posYOrigin=statData[i][j].yAxisPos - statData[i][j].yPosOffsetOrigin;
							}
						}
						statData[i][j].posX=statData[i][j].xPos;
						
						statData[i][j].v9= statData[i][j].xPos;
						statData[i][j].v10=statData[i][j].posY;

						statData[i][j].annotateStartPosX = statData[i][j].xPos;
						statData[i][j].annotateEndPosX = statData[i][j].xPos;
						statData[i][j].annotateStartPosY = othervars.xAxisPosY;
						statData[i][j].annotateEndPosY = othervars.xAxisPosY-othervars.msr.availableHeight;
						statData[i][j].D1A=undefined;
						statData[i][j].D1B=undefined;
					}
					break;
				case "Radar" :
					var rotationDegree = (2 * Math.PI) / data.datasets[0].data.length;
					for (j = 0; j < data.datasets[i].data.length; j++) {
						statData[i][j].midPosX =  othervars.midPosX;
						statData[i][j].midPosY =  othervars.midPosY;
						statData[i][j].int_radius= 0;
						statData[i][j].ext_radius= othervars.maxSize;
						statData[i][j].radiusOffset= othervars.maxSize;
						statData[i][j].calculatedScale= othervars.calculatedScale;
						statData[i][j].scaleHop= othervars.scaleHop;
						statData[i][j].calculated_offset= calculateOffset(config.logarithmic, data.datasets[i].data[j], othervars.calculatedScale, othervars.scaleHop);
						statData[i][j].offsetX=Math.cos(config.startAngle * Math.PI / 180 - j * rotationDegree) * statData[i][j].calculated_offset;
						statData[i][j].offsetY=Math.sin(config.startAngle * Math.PI / 180 - j * rotationDegree) * statData[i][j].calculated_offset;
						statData[i][j].v9=statData[i][j].midPosX + statData[i][j].offsetX;
						statData[i][j].v10=statData[i][j].midPosY - statData[i][j].offsetY;
						statData[i][j].posX=statData[i][j].midPosX + statData[i][j].offsetX;
						statData[i][j].posY=statData[i][j].midPosY - statData[i][j].offsetY;
						if(j==0)statData[i][j].calculated_offset_max=calculateOffset(config.logarithmic, statData[i][j].lmaxvalue, othervars.calculatedScale, othervars.scaleHop);
						else    statData[i][j].calculated_offset_max=statData[0][j].calculated_offset_max;
						statData[i][j].annotateStartPosX = othervars.midPosX;
						statData[i][j].annotateEndPosX = othervars.midPosX+Math.cos(config.startAngle * Math.PI / 180 - j * rotationDegree) * othervars.maxSize;
						statData[i][j].annotateStartPosY = othervars.midPosY;
						statData[i][j].annotateEndPosY = othervars.midPosY-Math.sin(config.startAngle * Math.PI / 180 - j * rotationDegree) * othervars.maxSize;
						if(Math.abs(statData[i][j].annotateStartPosX-statData[i][j].annotateEndPosX)<config.zeroValue) {
							statData[i][j].D1A=undefined;
							statData[i][j].D1B=undefined;
							statData[i][j].D2A=0;
						} else {
							statData[i][j].D1A=(statData[i][j].annotateStartPosY-statData[i][j].annotateEndPosY)/(statData[i][j].annotateStartPosX-statData[i][j].annotateEndPosX);
							statData[i][j].D1B=-statData[i][j].D1A*statData[i][j].annotateStartPosX+statData[i][j].annotateStartPosY;
							if(Math.abs(statData[i][j].D1A)>=config.zeroValue)statData[i][j].D2A=-(1/statData[i][j].D1A);
							else statData[i][j].D2A=undefined;
						}

				        }
					break;
				case "Bar" :
					for (j = 0; j < data.datasets[i].data.length; j++) {

						statData[i][j].xAxisPosY = othervars.xAxisPosY;
						statData[i][j].yAxisPosX = othervars.yAxisPosX;
						statData[i][j].valueHop = othervars.valueHop;
						statData[i][j].barWidth = othervars.barWidth;
						statData[i][j].additionalSpaceBetweenBars= othervars.additionalSpaceBetweenBars;
						statData[i][j].nbValueHop = othervars.nbValueHop;
						statData[i][j].calculatedScale = othervars.calculatedScale;
						statData[i][j].scaleHop = othervars.scaleHop;
			
						statData[i][j].xPosLeft= othervars.yAxisPosX + Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) + othervars.valueHop * j + othervars.additionalSpaceBetweenBars+othervars.barWidth * realbars + Math.ceil(ctx.chartSpaceScale*config.barDatasetSpacing) * realbars + Math.ceil(ctx.chartLineScale*config.barStrokeWidth) * realbars;
						statData[i][j].xPosRight = statData[i][j].xPosLeft + othervars.barWidth;
						statData[i][j].yPosBottom =othervars.xAxisPosY - othervars.zeroY
						statData[i][j].barHeight=calculateOffset(config.logarithmic, 1 * data.datasets[i].data[j], othervars.calculatedScale, othervars.scaleHop) - othervars.zeroY;
						if (data.datasets[i].axis == 2) {
							statData[i][j].yPosBottom =othervars.xAxisPosY - othervars.zeroY2;
							statData[i][j].barHeight=calculateOffset(config.logarithmic2, 1 * data.datasets[i].data[j], othervars.calculatedScale2, othervars.scaleHop2) - othervars.zeroY2;
						} else {
							statData[i][j].yPosBottom =othervars.xAxisPosY - othervars.zeroY
							statData[i][j].barHeight=calculateOffset(config.logarithmic, 1 * data.datasets[i].data[j], othervars.calculatedScale, othervars.scaleHop) - othervars.zeroY;
						}
						statData[i][j].yPosTop = statData[i][j].yPosBottom - statData[i][j].barHeight + (Math.ceil(ctx.chartLineScale*config.barStrokeWidth) / 2);
						statData[i][j].v7=statData[i][j].xPosLeft;
						statData[i][j].v8=statData[i][j].yPosBottom;
						statData[i][j].v9=statData[i][j].xPosRight;
						statData[i][j].v10=statData[i][j].yPosTop;

					}
					realbars++;
					break;			
				case "StackedBar" :
					for (j = 0; j < data.datasets[i].data.length; j++) {
						statData[i][j].xAxisPosY = othervars.xAxisPosY;
						statData[i][j].yAxisPosX = othervars.yAxisPosX;
						statData[i][j].valueHop = othervars.valueHop;
						statData[i][j].barWidth = othervars.barWidth;
						statData[i][j].additionalSpaceBetweenBars= othervars.additionalSpaceBetweenBars;
						statData[i][j].nbValueHop = othervars.nbValueHop;
						statData[i][j].calculatedScale = othervars.calculatedScale;
						statData[i][j].scaleHop = othervars.scaleHop;
//						statData[i][j].nbValueHop = othervars.nbValueHop;
			
						if (typeof tempp[j]=="undefined") {
							tempp[j]=0;
							tempn[j]=0;
							zeroY=  calculateOffset(config.logarithmic, 0 , othervars.calculatedScale, othervars.scaleHop);
						}
						if ((typeof data.datasets[i].data[j] == 'undefined')) continue;
						statData[i][j].xPosLeft= othervars.yAxisPosX + Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) + othervars.valueHop * j+othervars.additionalSpaceBetweenBars;
						if (1*data.datasets[i].data[j]<0) {
							statData[i][j].botval=tempn[j];
							statData[i][j].topval=tempn[j]+1*data.datasets[i].data[j] ;
							tempn[j]=tempn[j]+1*data.datasets[i].data[j] ;
						} else {
							statData[i][j].botval=tempp[j];
							statData[i][j].topval=tempp[j]+1*data.datasets[i].data[j] ;
							tempp[j]=tempp[j]+1*data.datasets[i].data[j] ;
						}
						statData[i][j].xPosRight = statData[i][j].xPosLeft + othervars.barWidth;
						statData[i][j].botOffset = calculateOffset(config.logarithmic, statData[i][j].botval , othervars.calculatedScale, othervars.scaleHop);
						statData[i][j].topOffset = calculateOffset(config.logarithmic, statData[i][j].topval , othervars.calculatedScale, othervars.scaleHop);
						statData[i][j].yPosBottom =othervars.xAxisPosY - statData[i][j].botOffset;
						statData[i][j].yPosTop = othervars.xAxisPosY - statData[i][j].topOffset;
						// treat spaceBetweenBar 
						if(Math.ceil(ctx.chartSpaceScale*config.spaceBetweenBar) > 0)
						{
							if(1*data.datasets[i].data[j]<0) {
								statData[i][j].yPosBottom+=Math.ceil(ctx.chartSpaceScale*config.spaceBetweenBar);
								if(tempn[j]==1*data.datasets[i].data[j])statData[i][j].yPosBottom-=(Math.ceil(ctx.chartSpaceScale*config.spaceBetweenBar)/2);	
								if(statData[i][j].yPosTop<statData[i][j].yPosBottom)statData[i][j].yPosBottom=statData[i][j].yPosTop;
							} else if (1*data.datasets[i].data[j]>0) {
								statData[i][j].yPosBottom-=Math.ceil(ctx.chartSpaceScale*config.spaceBetweenBar);	
								if(tempp[j]==1*data.datasets[i].data[j])statData[i][j].yPosBottom+=(Math.ceil(ctx.chartSpaceScale*config.spaceBetweenBar)/2);	
								if(statData[i][j].yPosTop>statData[i][j].yPosBottom)statData[i][j].yPosBottom=statData[i][j].yPosTop;
							}
						}
						statData[i][j].v7=statData[i][j].xPosLeft;
						statData[i][j].v8=statData[i][j].yPosBottom;
						statData[i][j].v9=statData[i][j].xPosRight;
						statData[i][j].v10=statData[i][j].yPosTop;
					}
					break;			
				case "HorizontalBar" :
					for (j = 0; j < data.datasets[i].data.length; j++) {

						statData[i][j].xAxisPosY = othervars.xAxisPosY;
						statData[i][j].yAxisPosX = othervars.yAxisPosX;
						statData[i][j].valueHop = othervars.valueHop;
						statData[i][j].barWidth = othervars.barWidth;
						statData[i][j].additionalSpaceBetweenBars= othervars.additionalSpaceBetweenBars;
						statData[i][j].nbValueHop = othervars.nbValueHop;
						statData[i][j].calculatedScale = othervars.calculatedScale;
						statData[i][j].scaleHop = othervars.scaleHop;


						statData[i][j].xPosLeft= othervars.yAxisPosX + othervars.zeroY;
						statData[i][j].yPosTop=othervars.xAxisPosY + Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) - othervars.scaleHop * (j + 1) + othervars.additionalSpaceBetweenBars + othervars.barWidth * i + Math.ceil(ctx.chartSpaceScale*config.barDatasetSpacing) * i + Math.ceil(ctx.chartLineScale*config.barStrokeWidth) * i;
						statData[i][j].yPosBottom=statData[i][j].yPosTop+othervars.barWidth;
						statData[i][j].barWidth = calculateOffset(config.logarithmic, 1 * data.datasets[i].data[j], othervars.calculatedScale, othervars.valueHop) - othervars.zeroY;
						statData[i][j].xPosRight = statData[i][j].xPosLeft + statData[i][j].barWidth;

						statData[i][j].v7=statData[i][j].xPosLeft;
						statData[i][j].v8=statData[i][j].yPosBottom;
						statData[i][j].v9=statData[i][j].xPosRight;
						statData[i][j].v10=statData[i][j].yPosTop;
					}
					break;			
				case "HorizontalStackedBar" :
					for (j = 0; j < data.datasets[i].data.length; j++) {
						statData[i][j].xAxisPosY = othervars.xAxisPosY;
						statData[i][j].yAxisPosX = othervars.yAxisPosX;
						statData[i][j].valueHop = othervars.valueHop;
						statData[i][j].barWidth = othervars.barWidth;
						statData[i][j].additionalSpaceBetweenBars= othervars.additionalSpaceBetweenBars;
						statData[i][j].nbValueHop = othervars.nbValueHop;
						statData[i][j].calculatedScale = othervars.calculatedScale;
						statData[i][j].scaleHop = othervars.scaleHop;

						if (i == 0) {
							tempp[j]=0;
							tempn[j]=0;
						}
						if (typeof(data.datasets[i].data[j]) == 'undefined')  continue;
//						if ((typeof(data.datasets[i].data[j]) == 'undefined') || 1*data.datasets[i].data[j] == 0 ) continue;

						statData[i][j].xPosLeft= othervars.yAxisPosX + Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) + othervars.valueHop * j;
						if (1*data.datasets[i].data[j]<0) {
							statData[i][j].leftval=tempn[j];
							statData[i][j].rightval=tempn[j]+1*data.datasets[i].data[j] ;
							tempn[j]=tempn[j]+1*data.datasets[i].data[j] ;
						} else {
							statData[i][j].leftval=tempp[j];
							statData[i][j].rightval=tempp[j]+1*data.datasets[i].data[j] ;
							tempp[j]=tempp[j]+1*data.datasets[i].data[j] ;
						}
						statData[i][j].rightOffset = HorizontalCalculateOffset(statData[i][j].rightval , othervars.calculatedScale, othervars.valueHop);
						statData[i][j].leftOffset  = HorizontalCalculateOffset(statData[i][j].leftval , othervars.calculatedScale, othervars.valueHop);
						statData[i][j].xPosRight = othervars.yAxisPosX + statData[i][j].rightOffset;
						statData[i][j].xPosLeft  = othervars.yAxisPosX + statData[i][j].leftOffset;
						statData[i][j].yPosTop =othervars.xAxisPosY + Math.ceil(ctx.chartSpaceScale*config.barValueSpacing) - othervars.scaleHop * (j + 1) + othervars.additionalSpaceBetweenBars;
						statData[i][j].yPosBottom = statData[i][j].yPosTop+othervars.barWidth;
						// treat spaceBetweenBar 
						if(Math.ceil(ctx.chartSpaceScale*config.spaceBetweenBar) > 0)
						{
							if(1*data.datasets[i].data[j]<0) {
								statData[i][j].xPosLeft-=Math.ceil(ctx.chartSpaceScale*config.spaceBetweenBar);	
								if(tempn[j]==1*data.datasets[i].data[j])statData[i][j].xPosLeft+=(Math.ceil(ctx.chartSpaceScale*config.spaceBetweenBar)/2);	
								if(statData[i][j].xPosLeft<statData[i][j].xPosRight)statData[i][j].xPosLeft=statData[i][j].xPosRight;
							} else if (1*data.datasets[i].data[j]>0) {
								statData[i][j].xPosLeft+=Math.ceil(ctx.chartSpaceScale*config.spaceBetweenBar);	
								if(tempp[j]==1*data.datasets[i].data[j])statData[i][j].xPosLeft-=(Math.ceil(ctx.chartSpaceScale*config.spaceBetweenBar)/2);	
								if(statData[i][j].xPosLeft>statData[i][j].xPosRight)statData[i][j].xPosLeft=statData[i][j].xPosRight;
							}
						}

						statData[i][j].v7=statData[i][j].xPosLeft;
						statData[i][j].v8=statData[i][j].yPosBottom;
						statData[i][j].v9=statData[i][j].xPosRight;
						statData[i][j].v10=statData[i][j].yPosTop;
					}
					break;
				default : 
					break;
			}
	}
	
} ;



	function xPos(ival, iteration, data,yAxisPosX,valueHop,nbValueHop) {
//nbValueHop=8;
		if (typeof data.datasets[ival].xPos == "object") {
			if (!(typeof data.datasets[ival].xPos[Math.floor(iteration + config.zeroValue)] == "undefined")) {
				var width = valueHop * nbValueHop;

				var deb = (typeof data.xBegin != "undefined") ? data.xBegin : 1 * data.labels[0];
				var fin = (typeof data.xEnd != "undefined") ? data.xEnd : 1 * data.labels[data.labels.length - 1];
				if (fin <= deb) fin = deb + 100;
				if (1 * data.datasets[ival].xPos[Math.floor(iteration + config.zeroValue)] >= deb && data.datasets[ival].xPos[Math.floor(iteration + config.zeroValue)] <= fin) {
					var p1 = yAxisPosX + width * ((1 * data.datasets[ival].xPos[Math.floor(iteration + config.zeroValue)] - deb) / (fin - deb));
					var p2 = p1;
					if (Math.abs(iteration - Math.floor(iteration + config.zeroValue)) > config.zeroValue) {
						p2 = xPos(ival, Math.ceil(iteration - config.zeroValue), data);
					}
					return p1 + (iteration - Math.floor(iteration + config.zeroValue)) * (p2 - p1);
				}
			}
		}
		return yAxisPosX + (valueHop * iteration);
	};


	function calculateOrderOfMagnitude(val) {
		return Math.floor(Math.log(val) / Math.LN10);
	};

	function calculateOffset(logarithmic, val, calculatedScale, scaleHop) {
		if (!logarithmic) { // no logarithmic scale
			var outerValue = calculatedScale.steps * calculatedScale.stepValue;
			var adjustedValue = val - calculatedScale.graphMin;
			var scalingFactor = CapValue(adjustedValue / outerValue, 1, 0);
			return (scaleHop * calculatedScale.steps) * scalingFactor;
		} else { // logarithmic scale
//			return CapValue(log10(val) * scaleHop - calculateOrderOfMagnitude(calculatedScale.graphMin) * scaleHop, undefined, 0);
			return CapValue(log10(val) * scaleHop - log10(calculatedScale.graphMin) * scaleHop, undefined, 0);
		}
	};

	function HorizontalCalculateOffset(val, calculatedScale, scaleHop) {
		var outerValue = calculatedScale.steps * calculatedScale.stepValue;
		var adjustedValue = val - calculatedScale.graphMin;
		var scalingFactor = CapValue(adjustedValue / outerValue, 1, 0);
		return (scaleHop * calculatedScale.steps) * scalingFactor;
	};

	//Apply cap a value at a high or low number
	function CapValue(valueToCap, maxValue, minValue) {
		if (isNumber(maxValue)) {
			if (valueToCap > maxValue) {
				return maxValue;
			}
		}
		if (isNumber(minValue)) {
			if (valueToCap < minValue) {
				return minValue;
			}
		}
		return valueToCap;
	};
	function log10(val) {
		return Math.log(val) / Math.LN10;
	};
};

function isBooleanOptionTrue(optionVar,defaultvalue) {
	var j;
	if(typeof optionvar == "undefined") {
		if(typeof defaultvalue=="function") return true;
		else if(typeof defaultvalue == "object") { 
			for(j=0;j<defaultvalue.length;j++) if (defaultvalue[j])return true;
			return false;
		}
		else return defaultvalue;
	}
	if(typeof optionvar=="function") return true;
	else if(typeof optionvar == "object") {
			for(j=0;j<optionvar.length;j++) if (optionvar[j])return true;
			return false;
	} else return optionvar;
};

function setOptionValue(treat_special,rescale,reference,ctx,data,statData,optionvar,defaultvalue,varname,posi,posj,othervars) {
	var rv;     
	if(treat_special == true) {
		if(ctx.tpdata==0) {
			if(typeof (data.special)=="object") {
//				for(var t=0;t<data.special.length;t++){
				for(var t=data.special.length-1;t>=0;t--){
					if(typeof data.special[t].posi == "undefined" || typeof data.special[t].posj == "undefined") continue;
					if(data.special[t].posi!=posi || data.special[t].posj != posj || posi==-1 || posj==-1) continue;
					if(typeof data.special[t][varname]!="undefined") {
						return data.special[t][varname];
					}
					
				}
			}
		} else {
			if(typeof (data[0].special)=="object") {
				for(var t=0;t<data[0].special.length;t++){
					if(typeof data[0].special[t].posi == "undefined" ) continue;
					if(data[0].special[t].posi!=posi || posi==-1) continue;
					if(typeof data[0].special[t][varname]!="undefined") {
						return data[0].special[t][varname];
					}
					
				}
			}
		}
	}
	if(typeof optionvar == "undefined") {
		if(typeof defaultvalue=="function") return defaultvalue(reference,ctx,data,statData,posi,posj,othervars);
		else if(typeof defaultvalue == "object") {     
				rv=defaultvalue[Math.min(defaultvalue.length-1,Math.max(0,posi))];
		}
		else { 
			rv=defaultvalue;
		}
		if(rescale!=1)rv=Math.ceil(rv*rescale);
		return rv;
	}
	if(typeof optionvar=="function") rv=optionvar(reference,ctx,data,statData,posi,posj,othervars);
	else if(typeof optionvar == "object") {
		if (posj==-1) rv=optionvar[Math.min(optionvar.length-1,Math.max(0,posi))];
		else rv=optionvar[Math.min(optionvar.length-1,Math.max(0,posj))];
	}
	else rv=optionvar;
	if(rescale!=1)rv=Math.ceil(rv*rescale);
	return rv;

};

function tpdraw(ctx,dataval) {
	switch(ctx.tpchart)  {
		case "Bar" :
		case "StackedBar" :
			if (dataval.type=="Line") { tp="Line";} 	
			else {tp=ctx.tpchart;}
			break;
		default : 
			tp=ctx.tpchart;
			break;
	}
	return tp;
};

function setTextBordersAndBackground(ctx,text,fontsize,xpos,ypos,borders,borderscolor,borderswidth,bordersxspace,bordersyspace,bordersstyle,backgroundcolor,optiongroup,BordersRadius) {
	var textHeight,textWidth;
	// compute text width and text height;
	if(typeof text != "string") {
		var txt=text.toString();
		textHeight= fontsize * (txt.split("\n").length || 1);
		textWidth = ctx.measureText(txt).width;
	} else {
		textHeight= fontsize * (text.split("\n").length || 1);
		textWidth = ctx.measureText(text).width;
	}
	
	
	// compute xright, xleft, ytop, ybot;

        var xright, xleft, ytop, ybot;
	if(ctx.textAlign=="center") {
		xright=-textWidth/2;
		xleft=textWidth/2;
	} else if(ctx.textAlign=="left") {
		xright=0;
		xleft=textWidth;
	} else if(ctx.textAlign=="right") {
		xright=-textWidth;
		xleft=0;
	}
	
	if(ctx.textBaseline=="top") {
		ytop=0;
		ybottom=textHeight;	
	} else if (ctx.textBaseline=="center" || ctx.textBaseline=="middle") {
		ytop=-textHeight/2;
		ybottom=textHeight/2;	
	} else if (ctx.textBaseline=="bottom") {
		ytop=-textHeight;
		ybottom=0;	
	}

	ctx.save();
	ctx.beginPath();
 	ctx.translate(xpos,ypos);

	if(backgroundcolor != "none") {

		ctx.save();
		ctx.fillStyle=backgroundcolor;
//		ctx.fillRect(xright-bordersxspace,ybottom+bordersyspace,xleft-xright+2*bordersxspace,ytop-ybottom-2*bordersyspace);
//		ctx.stroke();
		ctx.drawRectangle({x:xright-bordersxspace,y:ybottom+bordersyspace,width:xleft-xright+2*bordersxspace,height:ytop-ybottom-2*bordersyspace,borderRadius:BordersRadius,fill:true,stroke:false})
		ctx.restore();
		ctx.fillStyle="black";
	}	

	// draw border;
	if (borders) {
		ctx.save();
		ctx.lineWidth = borderswidth;	
		ctx.strokeStyle= borderscolor;
		ctx.fillStyle= borderscolor;
		ctx.setLineDash(lineStyleFn(bordersstyle));
		ctx.drawRectangle({x:xright-bordersxspace,y:ybottom+bordersyspace,width:xleft-xright+2*bordersxspace,height:ytop-ybottom-2*bordersyspace,borderRadius:BordersRadius,fill:false,stroke:true})
//		ctx.rect(xright-borderswidth/2-bordersxspace,ytop-borderswidth/2-bordersyspace,xleft-xright+borderswidth+2*bordersxspace,ybottom-ytop+borderswidth+2*bordersyspace); 
//		ctx.stroke();
		ctx.setLineDash([]);
		ctx.restore();
	}
	
	ctx.restore();
};

function calculatePieDrawingSize(ctx,msr,config,data,statData) {

	var realCumulativeAngle = (((config.startAngle * (Math.PI / 180) + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI) ; 
	var realAmplitude = (((config.totalAmplitude * (Math.PI / 180) + 2 * Math.PI) % (2 * Math.PI)) + 2* Math.PI) % (2* Math.PI) ; 
	if(realAmplitude <= config.zeroValue)realAmplitude=2*Math.PI;
			
	var debAngle=((realCumulativeAngle-realAmplitude)+4*Math.PI)%(2*Math.PI);
	var finAngle=debAngle+realAmplitude;
			
	var qposdeb=Math.floor(((debAngle+config.zeroValue)/(Math.PI/2))%4);
	var qposfin=Math.floor(((finAngle-config.zeroValue)/(Math.PI/2))%4);
	var q=[0,0,0,0];
	if(qposdeb<=qposfin)for(var i=qposdeb;i<=qposfin;i++)q[i]=1;
	else {
		for(var i=qposdeb;i<4;i++)q[i]=1;
		for(var i=0;i<=qposfin;i++)q[i]=1;
	}

	if(q[0]==0 && q[1]==0) {
		midPieY = msr.topNotUsableSize+5;
		doughnutRadius = msr.availableHeight-5;
	} else if(q[2]==0 && q[3]==0) {
		midPieY = msr.topNotUsableSize + msr.availableHeight;
		doughnutRadius = msr.availableHeight-5;
	}else {
		midPieY = msr.topNotUsableSize + (msr.availableHeight / 2);
		doughnutRadius = msr.availableHeight/2-5;
	}
	var realAvailableWidth;
	if(q[0]==0 && q[3]==0) {
		midPieX = msr.leftNotUsableSize + msr.availableWidth-5 ;
		doughnutRadius = Math.min(doughnutRadius, msr.availableWidth -5);
		realAvailableWidth=msr.availableWidth -5
		
	} else if(q[1]==0 && q[2]==0) {
		midPieX = msr.leftNotUsableSize+5 ;
		doughnutRadius = Math.min(doughnutRadius, msr.availableWidth -5);
		realAvailableWidth=msr.availableWidth -5
	} else {
		midPieX = msr.leftNotUsableSize + (msr.availableWidth / 2);
		doughnutRadius = Math.min(doughnutRadius, (msr.availableWidth/2) -5);
		realAvailableWidth=(msr.availableWidth/2) -5
	}

	// Computerange Pie Radius
	if (isBooleanOptionTrue(undefined,config.inGraphDataShow) && setOptionValue(true,1,"INGRAPHDATARADIUSPOSITION",ctx,data,statData,undefined,config.inGraphDataRadiusPosition,"inGraphDataRadiusPosition",0,-1,{nullValue : true} ) == 3 && setOptionValue(true,1,"INGRAPHDATAALIGN",ctx,data,statData,undefined,config.inGraphDataAlign,"inGraphDataAlign",0,-1,{nullValue: true  }) == "off-center" && setOptionValue(true,1,"INGRAPHDATAROTATE",ctx,data,statData,undefined,config.inGraphDataRotate,"inGraphDataRotate",0,-1,{nullValue : true} ) == 0) {
		doughnutRadius = doughnutRadius - setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",0,-1,{nullValue : true} ) - setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",0,-1,{nullValue: true} ) - 5;
		var posAngle;
		for (var i = 0; i < data.length; i++) {
			if (!(typeof(data[i].value) == 'undefined') && 1*data[i].value>=0) {
				ctx.font = setOptionValue(true,1,"INGRAPHDATAFONTSTYLE",ctx,data,statData,undefined,config.inGraphDataFontStyle,"inGraphDataFontStyle",i,-1,{nullValue : true} ) + ' ' + setOptionValue(true,ctx.chartTextScale,"INGRAPHDATAFONTSIZE",ctx,data,statData,undefined,config.inGraphDataFontSize,"inGraphDataFontSize",i,-1,{nullValue : true} ) + 'px ' + setOptionValue(true,1,"INGRAPHDATAFONTFAMILY",ctx,data,statData,undefined,config.inGraphDataFontFamily,"inGraphDataFontFamily",i,-1,{nullValue : true} );
				if (setOptionValue(true,1,"INGRAPHDATAANGLEPOSITION",ctx,data,statData,undefined,config.inGraphDataAnglePosition,"inGraphDataAnglePosition",i,-1,{nullValue : true} ) == 1) posAngle = realCumulativeAngle + setOptionValue(true,1,"INGRAPHDATAPADDINANGLE",ctx,data,statData,undefined,config.inGraphDataPaddingAngle,"inGraphDataPaddingAngle",i,-1,{nullValue: true  }) * (Math.PI / 180);
				else if (setOptionValue(true,1,"INGRAPHDATAANGLEPOSITION",ctx,data,statData,undefined,config.inGraphDataAnglePosition,"inGraphDataAnglePosition",i,-1,{nullValue : true} ) == 2) posAngle = realCumulativeAngle - statData[i].segmentAngle / 2 + setOptionValue(true,1,"INGRAPHDATAPADDINANGLE",ctx,data,statData,undefined,config.inGraphDataPaddingAngle,"inGraphDataPaddingAngle",i,-1,{nullValue: true  }) * (Math.PI / 180);
				else if (setOptionValue(true,1,"INGRAPHDATAANGLEPOSITION",ctx,data,statData,undefined,config.inGraphDataAnglePosition,"inGraphDataAnglePosition",i,-1,{nullValue : true} ) == 3) posAngle = realCumulativeAngle - statData[i].segmentAngle + setOptionValue(true,1,"INGRAPHDATAPADDINANGLE",ctx,data,statData,undefined,config.inGraphDataPaddingAngle,"inGraphDataPaddingAngle",i,-1,{nullValue: true  }) * (Math.PI / 180);
				realCumulativeAngle -= statData[i].segmentAngle;
				var dispString = tmplbis(setOptionValue(true,1,"INGRAPHDATATMPL",ctx,data,statData,undefined,config.inGraphDataTmpl,"inGraphDataTmpl",i,-1,{nullValue : true} ), statData[i],config);
				var textMeasurement = ctx.measureTextMultiLine(dispString).textWidth;
				var MaxRadiusX = Math.abs((realAvailableWidth - textMeasurement) / Math.cos(posAngle)) - setOptionValue(true,1,"INGRAPHDATAPADDINGRADIUS",ctx,data,statData,undefined,config.inGraphDataPaddingRadius,"inGraphDataPaddingRadius",i,-1,{nullValue: true} ) - 5;
				if (MaxRadiusX < doughnutRadius) doughnutRadius = MaxRadiusX;
			}
		}
	}
	doughnutRadius = doughnutRadius * config.radiusScale;
	return {
		radius : doughnutRadius,
		midPieX : midPieX,
		midPieY : midPieY
	};
};
