<?php if (!empty($errors)) { ?>
<div class="error">

    <?php
    foreach($errors as $error){
        echo "$error<br>";
    }
    ?>

</div>
<?php } ?>
