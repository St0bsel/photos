<?php
$Farray = array("1","2","3","4","5");
for ($i=0; $i < count($Farray); $i++) {
  if ($Farray[$i] == 3) {
    array_splice($Farray, $i, 1);
  }
}
print_r($Farray);
 ?>
