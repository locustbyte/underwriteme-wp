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
  <canvas id="canvas">  </canvas>  
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
  /*
 * requestAnimationFrame pollyfill
 */
if (!window.requestAnimationFrame) {
  window.requestAnimationFrame = (window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.msRequestAnimationFrame || window.oRequestAnimationFrame || function (callback) {
    return window.setTimeout(callback, 1000 / 60);
  });
}

// Init Stats
// var stats = new Stats();
// stats.setMode(0);
// stats.domElement.style.position = 'absolute';
// stats.domElement.style.left = '0px';
// stats.domElement.style.top = '0px';
// document.body.appendChild(stats.domElement);


/*!
 * Mantis.js / jQuery / Zepto.js plugin for Constellation
 * @version 1.2.2
 * @author Acauã Montiel <contato@acauamontiel.com.br>
 * @license http://acaua.mit-license.org/
 */
 function randomStarColor(){
      return Math.round(Math.random()*1) + 1
    }
(function ($, window) {
  /**
   * Makes a nice constellation on canvas
   * @constructor Constellation
   */
  function Constellation (canvas, options) {
    var $canvas = $(canvas),
      context = canvas.getContext('2d'),
      defaults = {
        star: {
          color: '#2a333a',
          width: 1,
          randomWidth: true
        },
        line: {
          color: 'rgba(0, 0, 0, 1)',
          width: 0.2
        },
        position: {
          x: 0, // This value will be overwritten at startup
          y: 0 // This value will be overwritten at startup
        },
        width: window.innerWidth,
        height: window.innerHeight,
        velocity: 0.1,
        length: 250,
        distance: 120,
        radius: 150,
        stars: []
      },
      config = $.extend(true, {}, defaults, options);


    

    function Star () {
      this.x = Math.random() * canvas.width;
      this.y = Math.random() * canvas.height;

      this.vx = (config.velocity - (Math.random() * 0.5));
      this.vy = (config.velocity - (Math.random() * 0.5));

      this.radius = config.star.randomWidth ? (Math.random() * config.star.width) : config.star.width;
    }

    Star.prototype = {
      create: function(){
        context.beginPath();
        context.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
        context.fill();
      },

      animate: function(){
        var i;
        for (i = 0; i < config.length; i++) {

          var star = config.stars[i];

          if (star.y < 0 || star.y > canvas.height) {
            star.vx = star.vx;
            star.vy = - star.vy;
          } else if (star.x < 0 || star.x > canvas.width) {
            star.vx = - star.vx;
            star.vy = star.vy;
          }

          star.x += star.vx;
          star.y += star.vy;
        }
      },

      line: function(){
        var length = config.length,
          iStar,
          jStar,
          i,
          j;

        for (i = 0; i < length; i++) {
          for (j = 0; j < length; j++) {
            iStar = config.stars[i];
            jStar = config.stars[j];

            if (
              (iStar.x - jStar.x) < config.distance &&
              (iStar.y - jStar.y) < config.distance &&
              (iStar.x - jStar.x) > - config.distance &&
              (iStar.y - jStar.y) > - config.distance
            ) {
              if (
                (iStar.x - config.position.x) < config.radius &&
                (iStar.y - config.position.y) < config.radius &&
                (iStar.x - config.position.x) > - config.radius &&
                (iStar.y - config.position.y) > - config.radius
              ) {
                context.beginPath();
                context.moveTo(iStar.x, iStar.y);
                context.lineTo(jStar.x, jStar.y);
                context.stroke();
                context.closePath();
              }
            }
          }
        }
      }
    };

    this.createStars = function () {
      var length = config.length,
        star,
        i;

      context.clearRect(0, 0, canvas.width, canvas.height);

      for (i = 0; i < length; i++) {
        config.stars.push(new Star());
        star = config.stars[i];

        star.create();
      }

      star.line();
      star.animate();
    };

    this.setCanvas = function () {
      canvas.width = config.width;
      canvas.height = config.height;
    };

    this.setContext = function () {

      
      context.fillStyle = config.star.color;
      context.strokeStyle = config.line.color;
      context.lineWidth = config.line.width;
    };

    this.setInitialPosition = function () {
      if (!options || !options.hasOwnProperty('position')) {
        config.position = {
          x: canvas.width * 0.5,
          y: canvas.height * 0.5
        };
      }
    };

    this.loop = function (callback) {
      callback();

      window.requestAnimationFrame(function () {
        //stats.begin(); // Only for Stats
        this.loop(callback);
        //stats.end(); // Only for Stats
      }.bind(this));
    };

    this.bind = function () {
      $(window).on('mousemove', function(e){
        config.position.x = e.pageX - $canvas.offset().left;
        config.position.y = e.pageY - $canvas.offset().top;
      });
    };

    this.init = function () {
      this.setCanvas();
      this.setContext();
      this.setInitialPosition();
      this.loop(this.createStars);
      this.bind();
    };
  }

  $.fn.constellation = function (options) {
    return this.each(function () {
      var c = new Constellation(this, options);
      c.init();
    });
  };
})($, window);

// Init plugin
$('canvas').constellation({
  star: {
    width: 3
  },
  line: {
    color: '#2a333a'
  },
  radius: 250
});


</script>
</body>
</html>
