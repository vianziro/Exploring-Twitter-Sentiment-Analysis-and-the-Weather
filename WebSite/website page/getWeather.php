<!-- Author: Hao DUAN<548771> Yu SUN <629341> -->
<!-- Date: 30 Oct 2015 -->
<!-- web server file that grabs different data from api in different cities. -->
<?php
  $city=$_GET["city"];

  $handle = fopen("http://127.0.0.1:5984/weatherdb/_design/mapreduce/_view/weathersearch?key=\"$city\"","rb");
  $content = "";
  while(!feof($handle)){
    $content.=fread($handle,100000);
  }
  fclose($handle);
  $content=json_decode($content,true);
  $size=sizeOf($content["rows"]);

  $handle1 = fopen("http://127.0.0.1:5984/citysummary/_design/mapreduce/_view/cityDatePNN?key=\"$city\"","rb");
  $content1 = "";
  while(!feof($handle1)){
    $content1.=fread($handle1,100000);
  }
  fclose($handle1);
  $content1=json_decode($content1,true);
  $size1=sizeOf($content1["rows"]);

  $today = date('d M Y');

    echo "<div style = \"margin-top: 6%;\">";

    echo "<span style=\"font-size:22px;\">Weather Details &nbsp;&nbsp;</span>
    <select onchange=\"selectDate(this.value)\">";

    for($i=0; $i<$size; $i++){
      if($content["rows"][$i]["value"][0] == $today){
        echo "<option value =\"".$content["rows"][$i]["value"][0]."@".$city."\" selected>".$content["rows"][$i]["value"][0]."</option>";

        $Weather = $content["rows"][$i]["value"][3];
        $Temperature1 = $content["rows"][$i]["value"][2];
        $Temperature2 = $content["rows"][$i]["value"][1];
        $Date = $content["rows"][$i]["value"][0];
      }else{
        echo "<option value =\"".$content["rows"][$i]["value"][0]."@".$city."\">".$content["rows"][$i]["value"][0]."</option>";
      }
    }
    
    echo "</select>";

    echo "
            <table class=\"table\">
              <tr class=\"info\">
                <th>City:</th>
                <td>".$city."</td>
              </tr>
              <tr class=\"danger\">
                <th>Weather:</th>
                <td>".$Weather."</td>
              </tr>
              <tr class=\"success\">
                <th>Temperature:</th>
                <td>".$Temperature1."°C ~".$Temperature2."°C</td>
              </tr>
              <tr class=\"info\">
                <th>Date:</th>
                <td>".$Date."</td>
              </tr>
            </table>
    ";

    for($i=0; $i<$size1; $i++){
      if($content1["rows"][$i]["value"][0] == $today){
        $Positive = $content1["rows"][$i]["value"][1];
        $Natural = $content1["rows"][$i]["value"][2];
        $Negative = $content1["rows"][$i]["value"][3];
      }
    }

$max = $Positive;
if($max < $Natural){
  $max = $Natural;
  if($max < $Negative){
    echo "
            <table class=\"table\">
              <tr class=\"info\">
                <th>Positive(%):</th>
                <th>Natural(%):</th>
                <th>Negative(%):</th>
              </tr>
              <tr class=\"danger\">
                <td>".$Positive."</td>
                <td>".$Natural."</td>
                <td><span style=\"color: red;\">".$Negative."</span></td>
              </tr>
            </table>
    ";
  }else{
    echo "
            <table class=\"table\">
              <tr class=\"info\">
                <th>Positive(%):</th>
                <th>Natural(%):</th>
                <th>Negative(%):</th>
              </tr>
              <tr class=\"danger\">
                <td>".$Positive."</td>
                <td>".$Negative."</td>
                <td><span style=\"color: red;\">".$Natural."</span></td>
              </tr>
            </table>
    ";
  }
}elseif($max < $Negative){
    echo "
            <table class=\"table\">
              <tr class=\"info\">
                <th>Positive(%):</th>
                <th>Natural(%):</th>
                <th>Negative(%):</th>
              </tr>
              <tr class=\"danger\">
                <td>".$Positive."</td>
                <td>".$Natural."</td>
                <td><span style=\"color: red;\">".$Negative."</span></td>
              </tr>
            </table>
    ";
  }else{
    echo "
            <table class=\"table\">
              <tr class=\"info\">
                <th>Positive(%):</th>
                <th>Natural(%):</th>
                <th>Negative(%):</th>
              </tr>
              <tr class=\"danger\">
                <td>".$Negative."</td>
                <td>".$Natural."</td>
                <td><span style=\"color: red;\">".$Positive."</span></td>
              </tr>
            </table>
    ";
  }

    echo "</div>";
?>

