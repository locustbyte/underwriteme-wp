<?php
    /**
    * Template name: FullPageVC
     */


/**
 * Full Page Empty Page Template
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 */
?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="https://daneden.github.io/animate.css/animate.min.css">
<script src="wp-content/themes/underwriteme/js/ume-animations.min.js"></script>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script type="text/javascript" src="http://mbostock.github.com/d3/d3.js?2.6.0"></script>
    <script type="text/javascript" src="http://mbostock.github.com/d3/d3.layout.js?2.6.0"></script>
    <script type="text/javascript" src="http://mbostock.github.com/d3/d3.geom.js?2.6.0"></script>

<?php
  // Include meta box
  require_once dirname( __FILE__ ) . '/mcw_fullpage_template_lib.php';

  // Buffer head output
  ob_start();
  wp_head();
  $mcw_fp_t_output = ob_get_contents();
  ob_end_clean();
  echo MCW_FullPage_Template_Lib::removeJS($mcw_fp_t_output);
?>
</head>
<body <?php body_class(); ?>>
<style>

.link {
  stroke: #313b43 !important;
  stroke-opacity: 1;
}

.node circle{
  stroke: #313b43 !important;
  fill: #313b43 !important;
  stroke-width: 1.5px;
  opacity: 1;
}

</style>
<div class="ume-logo">
  <img src="images/ume-logo.png" />
</div>

<!-- <div class="blk-background"></div> -->
<div class="ume-background">
  <canvas id="canvas"></canvas>
</div>
<div id="fullpage_wrapper">
<?php
// start the loop
while ( have_posts() )
{
  the_post();
  the_content();
}
?>
</div><!-- #fullpage_wrapper -->
<?php
  // Buffer head output
  ob_start();
  wp_footer();
  $mcw_fp_t_output = ob_get_contents();
  ob_end_clean();
  echo MCW_FullPage_Template_Lib::removeJS($mcw_fp_t_output);
?>
<script> 
  /* twitter.com/pixelia_me */

"use strict";

window.requestAnimFrame = (function() {
    return  window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame ||
        function( callback ) { window.setTimeout(callback, 1000 / 60 ); }
})();

(function() {
  var settings = {
    NUM_PARTICLES : 80,
    DISTANCE_T    : 100,
    RADIUS        : 5,
    OPACITY       : 0.5,
    SPEED_X       : 3,
    SPEED_Y       : 0.5,
    AMPLITUDE     : 10
  };
  
  var COLOURS    = ["#3FB8AF", "#FF3D7F", "#EBE54D"],
      bounds     = {},
      particles  = [],
      random     = Math.random,
      sqrt       = Math.sqrt,
      PI         = Math.PI,
      ctx, W, H, stats;
  
  function Particle() {
    this.x      = random() * bounds.right;
    this.y      = random() * bounds.bottom;
    this.xspeed = random() * settings.SPEED_X;
    this.yspeed = random() * settings.SPEED_Y;
    this.radius = settings.RADIUS;
    this.colour = COLOURS[ ~~(random() * COLOURS.length)];
  }
  
  var bindEvents = function() {
    window.addEventListener('resize', resize, false);
  };
  
  var resize = function() {
    W = window.innerWidth;
    H = window.innerHeight;
    ctx.canvas.width  = W;
    ctx.canvas.height = H;
    bounds.top      = 100;
    bounds.right    = W - 100;
    bounds.bottom   = H - 100;
    bounds.left     = 100;
  };
  
  var draw = function() {
    render();
    requestAnimFrame(draw);
  };
  
  var update = function (p) {
    p.x += p.xspeed;
    p.y += p.yspeed;
    
    if (p.x > bounds.right) {
      p.x = bounds.right;
      p.xspeed *= -1;
    }
    if (p.x < bounds.left) {
      p.x = bounds.left;
      p.xspeed *= -1;
    }
    if (p.y > bounds.bottom) {
      p.y = bounds.bottom;
      p.yspeed *= -1;
    }
    if (p.y < bounds.top) {
      p.y = bounds.top;
      p.yspeed *= -1;
    }
  };
  
  var render = function() {
    ctx.beginPath();
    ctx.globalCompositeOperation = "source-over";
    ctx.rect(0, 0 , W, H);
    ctx.fillStyle = "#111111";
    ctx.fill();
    ctx.closePath();
    
    ctx.globalCompositeOperation = "lighter";
        
    for (var i = 0; i < particles.length; i += 1) {
      var p = particles[i];
      
      ctx.beginPath();
      ctx.globalAlpha = settings.OPACITY;
      ctx.fillStyle = p.colour;
      ctx.arc(p.x, p.y, p.radius, PI * 2, false);
      ctx.fill();
      ctx.closePath();
      
      for (var j = 0; j < particles.length; j += 1) {
        var pp = particles[j],
            yd = pp.y - p.y,
            xd = pp.x - p.x,
            d  = sqrt(xd * xd + yd * yd);
        
        if ( d < settings.DISTANCE_T ) {
          ctx.beginPath();
          ctx.globalAlpha = (settings.DISTANCE_T - d) / (settings.DISTANCE_T - 0);
          ctx.lineWidth = 1;
          ctx.moveTo(p.x, p.y);
          
          if ( settings.AMPLITUDE ) {
            ctx.bezierCurveTo(
              p.x,
              p.y - random() * settings.AMPLITUDE,
              pp.x,
              pp.y + random() * settings.AMPLITUDE,
              pp.x,
              pp.y
            );
          } else {
            ctx.lineTo(pp.x, pp.y);
          }
          
          ctx.strokeStyle = p.colour;
          ctx.lineCap = "round";
          ctx.stroke();
          ctx.closePath();
          
        }
      }
      
      update(p);
      
    }
  };
  
  var updateSpeed = function( x, y, attr ) {
    var speed = arguments[0] ? x : y;
    for (var i = 0; i < settings.NUM_PARTICLES; i += 1) {
      var ns = random() * speed;
      particles[i][attr] = particles[i][attr] > 0 ? ns : -ns;
    }
  };
  
  var updateRadius = function( value ) {
    for (var i = 0; i < settings.NUM_PARTICLES; i += 1) {
      particles[i].radius = value;
    }
  };
  
  var init = function() {
    ctx = document.getElementsByTagName('canvas')[0].getContext('2d');
  
    bindEvents();
    resize();
    
    for (var i = 0; i < settings.NUM_PARTICLES; i += 1) {
      particles.push( new Particle() );
    }
    
    draw();
  };
  
  window.onload = init;
  
  var GUI = new dat.GUI();
  
  GUI.add(settings, 'NUM_PARTICLES')
    .min(2)
    .max(200)
    .step(1)
    .name("# Particles")
    .onFinishChange(function( num ){
      var l = particles.length;
      if ( num < l ) {
        var diff = (l - num);
        particles.splice( 1, diff );
      }
    
      if ( num > l ) {
        var diff = (num - l);
        for (var i = 0; i < diff; i += 1) {
          particles.push( new Particle() );
        }
      }
    });
  
  GUI.add(settings, 'DISTANCE_T').min(0)
    .max(200)
    .step(10)
    .name("Distance");
  
  GUI.add(settings, 'RADIUS')
    .min(0)
    .max(10)
    .step(1)
    .name("Radius")
    .onFinishChange(function( value ) {
      updateRadius( value );
    });
  
  GUI.add(settings, 'SPEED_X')
    .min(0)
    .max(20)
    .name("X speed")
    .onFinishChange(function( value ) {
      updateSpeed( value, false, "xspeed");
    });
  
  GUI.add(settings, 'SPEED_Y')
    .min(0)
    .max(20)
    .name("Y speed")
    .onFinishChange(function( value ) {
      updateSpeed( false, value, "yspeed");
    });
  
  GUI.add(settings, 'AMPLITUDE')
    .min(0)
    .max(20)
    .step(1)
    .name("Amplitude");
  
  GUI.close();
  
})();
</script>
</body>
</html>
