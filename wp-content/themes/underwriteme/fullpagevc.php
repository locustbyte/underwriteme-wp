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
<div class="ume-background">
  <div class="content">
        <div id="large-header" class="large-header">
          <canvas id="demo-canvas" style="    zoom: 3;left: -700px; position: absolute;top: -300px;"></canvas>
        </div>
      </div>
  <svg width="1000" height="1000"></svg>
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

<script type="text/javascript" charset="utf-8">

      var w = 960, h = 500;

      var labelDistance = 0;

      var vis = d3.select("svg").attr("width", $(window).width()).attr("height", $(window).height());
      var force;
      var nodes = [];
      var labelAnchors = [];
      var labelAnchorLinks = [];
      var links = [];
      var node;

function startnew(){
  $('svg').empty()
  nodes = [];
  labelAnchors = [];
  labelAnchorLinks = [];
  links = [];

      for(var i = 0; i < 30; i++) {
        node = {
          label : ""
        };
        nodes.push(node);
        labelAnchors.push({
          node : node
        });
        labelAnchors.push({
          node : node
        });
      };

      for(var i = 0; i < nodes.length; i++) {
        for(var j = 0; j < i; j++) {
          if(Math.random() > .95)
            links.push({
              source : i,
              target : j,
              weight : Math.random()
            });
        }
        labelAnchorLinks.push({
          source : i * 2,
          target : i * 2 + 1,
          weight : 1
        });
      };

      var rndtan = Math.floor(Math.random() * -3000) + -2000  
      console.log(rndtan)

      force = d3.layout.force().size([w, h]).nodes(nodes).links(links).gravity(1).linkDistance(50).charge(-3000).linkStrength(function(x) {
        return x.weight * 10
      });


      force.start();

      var force2 = d3.layout.force().nodes(labelAnchors).links(labelAnchorLinks).gravity(0).linkDistance(0).linkStrength(8).charge(-100).size([w, h]);
      force2.start();

      var link = vis.selectAll("line.link").data(links).enter().append("svg:line").attr("class", "link").style("stroke", "#272E34");

      var node = vis.selectAll("g.node").data(force.nodes()).enter().append("svg:g").attr("class", "node");
      node.append("svg:circle").attr("r", 5).style("fill", "#272E34").style("stroke", "#272E34").style("stroke-width", 3);
      node.call(force.drag);


      var anchorLink = vis.selectAll("line.anchorLink").data(labelAnchorLinks)//.enter().append("svg:line").attr("class", "anchorLink").style("stroke", "#999");

      var anchorNode = vis.selectAll("g.anchorNode").data(force2.nodes()).enter().append("svg:g").attr("class", "anchorNode");
      anchorNode.append("svg:circle").attr("r", 0).style("fill", "#272E34");
        anchorNode.append("svg:text").text(function(d, i) {
        return i % 2 == 0 ? "" : d.node.label
      }).style("fill", "#555").style("font-family", "Arial").style("font-size", 12);

      var updateLink = function() {
        this.attr("x1", function(d) {
          return d.source.x;
        }).attr("y1", function(d) {
          return d.source.y;
        }).attr("x2", function(d) {
          return d.target.x;
        }).attr("y2", function(d) {
          return d.target.y;
        });

      }

      var updateNode = function() {
        this.attr("transform", function(d) {
          return "translate(" + d.x + "," + d.y + ")";
        });

      }


      force.on("tick", function() {

        force2.start();

        node.call(updateNode);

        anchorNode.each(function(d, i) {
          if(i % 2 == 0) {
            d.x = d.node.x;
            d.y = d.node.y;
          } else {
            var b = this.childNodes[1].getBBox();

            var diffX = d.x - d.node.x;
            var diffY = d.y - d.node.y;

            var dist = Math.sqrt(diffX * diffX + diffY * diffY);

            var shiftX = b.width * (diffX - dist) / (dist * 2);
            shiftX = Math.max(-b.width, Math.min(0, shiftX));
            var shiftY = 5;
            this.childNodes[1].setAttribute("transform", "translate(" + shiftX + "," + shiftY + ")");
          }
        });


        anchorNode.call(updateNode);

        link.call(updateLink);
        anchorLink.call(updateLink);

      });
}

  startnew()
      

      

     

    </script>

    <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script><script src='//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js'></script><script src='/assets/libs/traceur-runtime.js'></script><script src='http://tympanus.net/Development/AnimatedHeaderBackgrounds/js/TweenLite.min.js'></script><script src='http://tympanus.net/Development/AnimatedHeaderBackgrounds/js/EasePack.min.js'></script>
<script>'use strict';
(function () {
    var width, height, largeHeader, canvas, ctx, points, target, animateHeader = true;
    initHeader();
    initAnimation();
    addListeners();
    function initHeader() {
        width = window.innerWidth;
        height = window.innerHeight;
        target = {
            x: width / 2,
            y: height / 2
        };
        largeHeader = document.getElementById('large-header');
        largeHeader.style.height = height + 'px';
        canvas = document.getElementById('demo-canvas');
        canvas.width = width;
        canvas.height = height;
        ctx = canvas.getContext('2d');
        points = [];
        for (var x = 0; x < width; x = x + width / 20) {
            for (var y = 0; y < height; y = y + height / 20) {
                var px = x + Math.random() * width / 90;
                var py = y + Math.random() * height / 90;
                var p = {
                    x: px,
                    originX: px,
                    y: py,
                    originY: py
                };
                points.push(p);
            }
        }
        for (var i = 0; i < points.length; i++) {
            var closest = [];
            var p1 = points[i];
            for (var j = 0; j < points.length; j++) {
                var p2 = points[j];
                if (!(p1 == p2)) {
                    var placed = false;
                    for (var k = 0; k < 5; k++) {
                        if (!placed) {
                            if (closest[k] == undefined) {
                                closest[k] = p2;
                                placed = true;
                            }
                        }
                    }
                    for (var k = 0; k < 5; k++) {
                        if (!placed) {
                            if (getDistance(p1, p2) < getDistance(p1, closest[k])) {
                                closest[k] = p2;
                                placed = true;
                            }
                        }
                    }
                }
            }
            p1.closest = closest;
        }
        for (var i in points) {
            var c = new Circle(points[i], 2 + Math.random() * 2, '#313b43');
            points[i].circle = c;
        }
    }
    function addListeners() {
        if (!('ontouchstart' in window)) {
            window.addEventListener('mousemove', mouseMove);
        }
        window.addEventListener('scroll', scrollCheck);
        window.addEventListener('resize', resize);
    }
    function mouseMove(e) {
        var posx = posy = 0;
        if (e.pageX || e.pageY) {
            posx = e.pageX;
            posy = e.pageY;
        } else if (e.clientX || e.clientY) {
            posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
            posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
        }
        target.x = posx;
        target.y = posy;
    }
    function scrollCheck() {
        if (document.body.scrollTop > height)
            animateHeader = false;
        else
            animateHeader = true;
    }
    function resize() {
        width = window.innerWidth;
        height = window.innerHeight;
        largeHeader.style.height = height + 'px';
        canvas.width = width;
        canvas.height = height;
    }
    function initAnimation() {
        animate();
        for (var i in points) {
            shiftPoint(points[i]);
        }
    }
    function animate() {
        if (animateHeader) {
            ctx.clearRect(0, 0, width, height);
            for (var i in points) {
                if (Math.abs(getDistance(target, points[i])) < 4000) {
                    points[i].active = 0.3;
                    points[i].circle.active = 0.6;
                } else if (Math.abs(getDistance(target, points[i])) < 20000) {
                    points[i].active = 0.1;
                    points[i].circle.active = 0.3;
                } else if (Math.abs(getDistance(target, points[i])) < 40000) {
                    points[i].active = 0.02;
                    points[i].circle.active = 0.1;
                } else {
                    points[i].active = 0;
                    points[i].circle.active = 0;
                }
                drawLines(points[i]);
                points[i].circle.draw();
            }
        }
        requestAnimationFrame(animate);
    }
    function shiftPoint(p) {
        TweenLite.to(p, 1 + 1 * Math.random(), {
            x: p.originX - 50 + Math.random() * 100,
            y: p.originY - 50 + Math.random() * 100,
            ease: Circ.easeInOut,
            onComplete: function () {
                shiftPoint(p);
            }
        });
    }
    function drawLines(p) {
        if (!p.active)
            return;
        for (var i in p.closest) {
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(p.closest[i].x, p.closest[i].y);
            ctx.strokeStyle = 'rgba(156,217,249,' + p.active + ')';
            ctx.stroke();
        }
    }
    function Circle(pos, rad, color) {
        var _this = this;
        (function () {
            _this.pos = pos || null;
            _this.radius = rad || null;
            _this.color = color || null;
        }());
        this.draw = function () {
            if (!_this.active)
                return;
            ctx.beginPath();
            ctx.arc(_this.pos.x, _this.pos.y, _this.radius, 0, 2 * Math.PI, false);
            ctx.fillStyle = 'rgba(156,217,249,' + _this.active + ')';
            ctx.fill();
        };
    }
    function getDistance(p1, p2) {
        return Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2);
    }
}());//@ sourceURL=pen.js
</script>
</body>
</html>
