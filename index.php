
<?php include_once('header.php'); ?>

<?php magazinesections () ; ?>
	
<div id="lifestyle_menu">  <!--change class name-->
	    <a href="/index.php?metext=3">Add/Edit lifestyle</a>
	<br/>
	   <!--Users Topics-->

		<?php echo $memenulist; ?>
	
</div> <!-- closes lifestyle_menu-->	
    
<!--from display/me/homstart.php-->
<div id="magtext">
    <h3 class="left"> <?php lifestylesummary(); ?></h3>

<h3 class="right">Last 24hrs
     	<a href="http://75.101.138.19/lifestylelinking/rsscode/index.php?lifestyleid=<?php echo $lifestylelid ?>">
      	<img src="<?php makeurl('display/images/rssicon.gif') ?>" alt="rss icon" title="rssicon"/></a>
      </h3>
      
<?php streamstyle (); ?>					  
		     
<?php displayphotos (); ?>
   
<?php displayvideo (); ?>
        

<?php include_once ("footer.php"); ?> 
        
        
