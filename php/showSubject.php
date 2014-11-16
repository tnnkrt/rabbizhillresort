
  <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css">
  <script src = "/js/bootstrap.js"></script>
  <script src = "/js/jquery.min.js"></script>
  <link href="/css/simple-sidebar.css" rel="stylesheet">

<div style="margin-left:15px; margin-top:10px;">
<ul class="nav nav-tabs" role="tablist" id="myTab">
  <li class="active"><a href="#detail" role="tab" data-toggle="tab">รายละเอียดรายวิชา</a></li>
  <li><a href="#report" role="tab" data-toggle="tab">รายงานผล</a></li>
  <li><a href="#check" role="tab" data-toggle="tab">เช็คชื่อ</a></li>
  <li><a href="#export" role="tab" data-toggle="tab">Export</a></li>
  

</ul>


<div class="tab-content">
  <div class="tab-pane active" id="detail">
    <br>
        <dl class="dl-horizontal" style="margin-left:10px">
            <dt>ชื่อรายวิชา </dt>
               <dd>{{$name_subject}}</dd>
            <dt>รหัสวิชา </dt>
               <dd>{{$id}}</dd>
            <dt>เวลาเรียน</dt>
               <dd>{{$time_subject}}</dd>
            <dt>วันที่เรียน</dt>
               <dd>{{$day_subject}}</dd>
            <dt>ห้องเรียน</dt>
               <dd>{{$room_subject}}</dd>   
            <dt>รายละเอียดรายวิชา</dt>
               <dd>{{$detail_subject}}</dd>   
          </dl>
    
  </div>

  <div class="tab-pane" id="check">
    <div stlye="margin-left:20px; margin-top:20px;">
         <div class="table-responsive margin-top:20px; col-md-10">
          <br>
            <?php

              $f = fopen("Digital.csv","r+");
              $fr = fread($f,filesize("Digital.csv"));
              fclose($f);
              $lines = array();
              $lines = explode("\n",$fr); // IMPORTANT the delimiter here just the "new line" \r\n, use what u need instead of... 
              echo "line:".count($lines);
              echo "<table border='1'>";
              for($i=0;$i<count($lines)-1;$i++)
              {
                  echo "<tr>";
                  $cells = array(); 
                  $cells = explode(",",$lines[$i]); // use the cell/row delimiter what u need!
                  for($k=0;$k<count($cells);$k++)
                  {
                    echo "<td>";
                    if($k>0 && $i>1){
                      echo '<input type="checkbox" ';
                      if($cells[$k][0]!="0") echo 'checked';
                      echo '>';
                    }else{
                      echo $cells[$k];
                    }
                    echo "</td>";
                  }
                  // for k end
                  echo "</tr>";
              }
              echo "</table>";
              // for i end
              //http://www.formget.com/php-checkbox/
              ?>
      </div>
    </div>
  </div>

  <div class="tab-pane" id="export">
                 ddsdsd

  </div>
  <div class="tab-pane" id="report">...</div>
  
</div>
			
</div>



<script>
  $(function () {
    $('#myTab a:last').tab('show')
  })
</script>

@stop