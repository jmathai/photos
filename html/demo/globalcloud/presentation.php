<html lang="en">

  <head>
    <link href="extra/styles.css" media="screen" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="extra/prototype.lite.js"></script>
    <script type="text/javascript" src="extra/moo.fx.js"></script>
    
    <!-- PTG -->
    <script type="text/javascript" id="__PTG" src="http://www.photagious.com/js/api.js"></script>
    <script type="text/javascript">
      var ptg = new PTG("10fbbb7d87826e9301c7323cb9c5ddbc");
    </script>
    
    <script type="text/javascript">
      function loadPresentation()
      {
        $('presentation').innerHTML = ptg.slideshow.embed({'TAGS':'<?php echo htmlspecialchars($_GET['tags']); ?>'});
      }
    </script>
    
  </head>
  
  <body onload="loadPresentation();">
    <div id="container">
      <div class="f_12 bold"><a href="./">Main</a> / Presentation</div>
      <br/>
      <div id="presentation">
      </div>
    </div>
  </body>
</html>