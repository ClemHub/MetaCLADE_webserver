<!DOCTYPE html>
<html>
<head>
<script src="./includes/jquery.js"></script>
  <meta charset="utf-8">
  <title>Test if jQuery is loaded</title>
</head>
<body>
<p>Click me!</p>
</body>
<script> $("p").bind("click", function(){
   $( "This is a click Event").appendTo( "body" );
});

$("p").bind("dblclick", function(){
   $( "This is a double-click Event"  ).appendTo( "body" );
});</script>

</html>