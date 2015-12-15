		<div class="contentview">
				<ul>
					<li><a href="/lifestylelinking/me/index.php?metext=1">Magazine</a></li>
				  <li><a href="/lifestylelinking/me/index.php?metext=2">Charts</a></li>        
            
            <li> </li><li> </li>
            
            
            <li><a href="/lifestylelinking/me/index.php?filtext=2">
            
            <?php
              if ($_GET[filtext]  == 2 || $_SESSION[filtext]  == 2 )  {  ?>
          
             <FONT style="COLOR: white; font-weight: bold; BACKGROUND-COLOR: rgb(238,77,133) ">Filtered </FONT></a></li>
    
          <?php
          }
          else {
              ?>
            Filtered</a></li>
                
<?php       }
?>
            <li><a href="/lifestylelinking/me/index.php?filtext=1">


    <?php      
            if ($_GET[filtext]  == 1  )  {  ?>
            <FONT style="COLOR: white; font-weight: bold; BACKGROUND-COLOR: rgb(238,77,133) ">All posts </FONT></a></li>
            <?php
            }
          else {
              ?>
            All posts</a></li>
                

<?php       }
            
           ?> 
          
    

            <!--  <li class=""><a href="/lifestylelinking/me/#">lifestyle</a></li> -->
			     <!-- <li><a href="/lifestylelinking/me/#">website preferences</a></li>	 -->
        </ul>
		</div>
    
    