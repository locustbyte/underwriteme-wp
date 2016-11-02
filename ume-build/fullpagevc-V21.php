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
  A = {};
A.init = function(){
  
  console.log('init');
  
  A.firstParticle = null;
  A.numParticles = 64;
  A.canvas = document.getElementById('canvas');
  A.canvas.width = window.innerWidth;
  A.canvas.height = window.innerHeight;
  A.ctx = A.canvas.getContext('2d');
  A.deltaTime = 0.5;
  A.gravity = 900;
  A.decay = 0.1;
  
  
  var currentParticle = null;
  
  for( var q = 0; q < A.numParticles; q++ ){
    if( q === 0 ){
      A.firstParticle = A.createParticle();
      currentParticle = A.firstParticle;
    }
    else {
      currentParticle.next = A.createParticle();
      currentParticle = currentParticle.next;
    }
  }
}

A.draw = function(){
  
 A.ctx.clearRect(0,0,window.innerWidth,window.innerHeight);
  
  var cP = A.firstParticle;
  
   while( cP.next ) {
     
     if( cP.xPos > window.innerWidth ){
       A.resetParticle(cP);
     }
     if( cP.xPos < 0 ){
       A.resetParticle(cP);
     }
     if( cP.yPos > window.innerHeight ){
       A.resetParticle(cP);
     }
     if( cP.yPos < 0 ){
       A.resetParticle(cP);
     }
     if( cP.life < 0 ){
       A.resetParticle(cP);
     }
     
     cP.xPos += cP.xVel * A.deltaTime + cP.xAcc * A.deltaTime;
     cP.yPos += cP.yVel * A.deltaTime + cP.yAcc * A.deltaTime;
     

     A.ctx.fillStyle = "#de702e";
     //A.ctx.fillRect (cP.xPos, cP.yPos, 12, 12);
     A.ctx.beginPath();
     A.ctx.arc( cP.xPos, cP.yPos, cP.life*10, 0, 2*Math.PI, true);
     A.ctx.fill();
     A.ctx.closePath();
  
     cP.life -= A.decay * A.deltaTime ;
     cP = cP.next;
  }
}

A.createParticle = function(){
  var p = new Particle( 0, window.innerWidth/2, window.innerHeight/2-100, getRnd(-2,2), getRnd(-2,2), getRnd(-2,2), getRnd(-2,2) );
  return p;
}

A.resetParticle = function(p){   

  p.xPos = window.innerWidth/2;
  p.yPos = window.innerHeight/2;
  p.xAcc = getRnd(-2,2);
  p.yAcc = getRnd(-2,2);
  p.xVel = getRnd(-2,2);
  p.yVel = getRnd(-2,2);
  p.life = getRnd(1,5);
}

function getRnd (min, max) {
    return Math.random() * (max - min) + min;
}

function Particle( id, xPos, yPos, xAcc, yAcc, xVel, yVel ){
  
  this.id = id;
  this.xPos = xPos;
  this.yPos = yPos;
  this.xAcc = xAcc;
  this.yAcc = yAcc;
  this.xVel = xVel;
  this.yVel = yVel;
  this.life = getRnd(1,5);

  this.next = null;

}

A.init();

window.requestAnimFrame = (function(callback) {
  return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame ||
    function(callback) {
      window.setTimeout(callback, 1000 / 60);
    };
})();

(function animloop(){
  requestAnimFrame(animloop);
  A.draw();
})();


</script>
</body>
</html>
