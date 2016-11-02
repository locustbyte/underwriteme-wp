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
<script>  window.requestAnimFrame = function()
  {
    return (
      window.requestAnimationFrame       || 
      window.webkitRequestAnimationFrame || 
      window.mozRequestAnimationFrame    || 
      window.oRequestAnimationFrame      || 
      window.msRequestAnimationFrame     || 
      function(/* function */ callback){
        window.setTimeout(callback, 1000 / 60);
      }
    );
  }();

    var canvas = document.getElementById('canvas'); 
    
    var context = canvas.getContext('2d');
    
var W = window.innerWidth, H = window.innerHeight;
  canvas.width = W;
  canvas.height = H;

  var particle_count = 80,
    particles = [],
    couleurs   = ["#2a333a", "#3498db", "#2a333a","#2a333a"];
    function Particle()
    {

        this.radius = Math.floor(Math.random() * 4) + 1  
        this.x = Math.floor((Math.random() * canvas.width/2) + this.radius); 
        this.y =  Math.floor((Math.random() * canvas.height/2) + this.radius); 
        this.color = couleurs[Math.round(Math.random()*couleurs.length)];
        this.speedx = Math.round((Math.random()*101)+0)/100;
        this.speedy = Math.round((Math.random()*101)+0)/100;

        switch (Math.round(Math.random()*couleurs.length))
        {
            case 1:
            this.speedx *= 1;
            this.speedy *= 1;
            break;
            case 2:
            this.speedx *= -1;
            this.speedy *= 1;
            break;
            case 3:
            this.speedx *= 1;
            this.speedy *= -1;
            break;
            case 4:
            this.speedx *= -1;
            this.speedy *= -1;
            break;
        }
            
        this.move = function()
        {
            
            context.beginPath();
            context.globalCompositeOperation = 'source-over';
            context.fillStyle   = this.color;
            context.globalAlpha = 1;
            context.arc(this.x, this.y, this.radius, 0, Math.PI*2, false);
            context.fill();
            context.closePath();

            this.x = this.x + this.speedx;
            this.y = this.y + this.speedy;
            
            if(this.x <= 0+this.radius)
            {
                this.speedx *= -1;
            }
            if(this.x >= canvas.width-this.radius)
            {
                this.speedx *= -1;
            }
            if(this.y <= 0+this.radius)
            {
                this.speedy *= -1;
            }
            if(this.y >= canvas.height-this.radius)
            {
                this.speedy *= -1;
            }

            for (var j = 0; j < particle_count; j++)
            {
                var particleActuelle = particles[j],
                    yd = particleActuelle.y - this.y,
                    xd = particleActuelle.x - this.x,
                    d  = Math.sqrt(xd * xd + yd * yd);

                if ( d < 200 )
                {
                    context.beginPath();
                    context.globalAlpha = (200 - d) / (200 - 0);
                    context.globalCompositeOperation = 'destination-over';
                    context.lineWidth = 1;
                    context.moveTo(this.x, this.y);
                    context.lineTo(particleActuelle.x, particleActuelle.y);
                    context.strokeStyle = this.color;
                    context.lineCap = "round";
                    context.stroke();
                    context.closePath();
                }
            }
        };
    };
    for (var i = 0; i < particle_count; i++)
    {
        var particle = new Particle();
        particles.push(particle);
    }


    function animate()
    {
        context.clearRect(0, 0, canvas.width, canvas.height);
        for (var i = 0; i < particle_count; i++)
        {
            particles[i].move();
        }
        requestAnimFrame(animate);
    }
    
    

   
    animate(); 
  </script>
</body>
</html>
