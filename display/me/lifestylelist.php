<div class="rightbox">
         <div class="personalizetab">
         Please select lifestyles to Add or Remove
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?> ">

         <div align="center"> <input type="Submit" name="editlifestyle" value="Add"><input type="Submit" name="deletelifestyle" value="Remove"></div>
         <br />
         <br />

        <?php grouplistsin ();
               echo $grouplist; ?>  
                           
          </form>

         </div>  <!-- closes personalizetab-->


       </div>  <!-- closes rightbox -->
