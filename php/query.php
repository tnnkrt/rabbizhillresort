<?php
	$count;
	$countcheck;
	$datatouse=array(array());
	$datatocheck=array(array());
	$servername = "localhost";
	$username = "root";
	$password = "1234";
	$dbname = "mydb";
	$conn;
	$next;
	$sum=0;
	$last_id=0;
	$idroomtype;
	$nameroomtype;
	$takeprice;
	$all;
	function getidroomtype($roomtype){
		global $idroomtype;
		global $conn;
		$sql="SELECT * FROM roomtype WHERE roomname='".$roomtype."'";
		$result=$conn->query($sql);
		$row=$result->fetch_assoc();
		$idroomtype=$row['id'];
	}
	function getnameroomtype($id){
		global $nameroomtype;
		global $conn;
		$sql="SELECT * FROM roomtype WHERE id=".$id;
		$result=$conn->query($sql);
				// echo"<script language=\"JavaScript\">";
				// echo"alert('".$sql."')";
				// echo"</script>";
		$row=$result->fetch_assoc();
		$nameroomtype=$row['roomname'];
	}
	function getprice($id){
		global $takeprice;
		global $conn;
		$sql="SELECT * FROM roomtype WHERE id=".$id;
		$result=$conn->query($sql);
		$row=$result->fetch_assoc();
		$takeprice=$row['price'];
	}
	function insertdatainreservation($rtype,$reserv_date,$amount,$price){
		global $conn;
		$sql = "INSERT INTO reservation (roomtype,reserv_date,amount,price)
		VALUES (".$rtype.", '".$reserv_date."', '".$amount."','".$price."')";
		//echo $rtype.",".$reserv_date.",".$amount.",".$price;
		if (mysqli_query($conn, $sql)) {
			$last_id = mysqli_insert_id($conn);
			//print "New record created successfully. Last inserted ID is: " . $last_id;
		} else {
			print "Error: " . $sql . "<br>" . mysqli_error($conn);
		}		
	}
	function create_database($name){
		global $conn;
		$conn = mysqli_connect($servername, $username, $password);
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		// Create database
		$sql = "CREATE DATABASE ".$name;
		if (mysqli_query($conn, $sql)) {
			print "Database created successfully";
		} else {
			print "Error creating database: " . mysqli_error($conn);
		}
	}
	function create_table($name){
		global $conn;
		// sql to create table DEFAULT '...'
		startserver();
		if ($name==="roomtype")
			{//print "roomtype";
			$sql = "CREATE TABLE ".$name." (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
					roomname VARCHAR(30) NOT NULL,
					price INT(6) NOT NULL,
					amount INT(1),
					textbox INT(50),
					reg_date TIMESTAMP
					)";
			}
		else if ($name==="reservation"){//print "reservation";
				$sql = "CREATE TABLE ".$name." (
						id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
						roomtype INT(6) UNSIGNED,
						reserv_date DATE,
						amount INT(1),
						price INT(6) NOT NULL,
						reg_date TIMESTAMP,
						INDEX fk_table (roomtype),
						CONSTRAINT fk_table FOREIGN KEY (roomtype) REFERENCES roomtype(id)
						)";
				}
		else if ($name==="customerdata"){
			$sql = "CREATE TABLE ".$name." (
						id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
						name VARCHAR(30) NOT NULL,
						email VARCHAR(30),
						room VARCHAR(30),
						reserv_date DATE,
						to_date DATE,
						person INT(1),
						reg_date TIMESTAMP
						)";
		}
		else if ($name==="transaction"){
			$sql = "CREATE TABLE ".$name." (
						id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
						tranid INT(6) UNSIGNED,
						reservid INT(6) UNSIGNED,
						reg_date TIMESTAMP,
						INDEX fk_tran (tranid),
						CONSTRAINT fk_tran FOREIGN KEY (tranid) REFERENCES customerdata(id),
						INDEX fk_reserv (reservid),
						CONSTRAINT fk_reserv FOREIGN KEY (reservid) REFERENCES reservation(id)
						)";
		}
		if (mysqli_query($conn, $sql)) {
			print "Table ".$name." created successfully";
		} else {
			print "Error creating table: " . mysqli_error($conn);
		}
		stopserver();
	}
	function groupdata($i,$price,$room,$amount,$reserv){
		global $conn;
		global $datatouse;
		global $count;
		$newdata=array(
			$price,
			$room,
			$i,
			$i,
			$amount,
			$reserv
		);
		if ($count==0){
			$datatouse[$count]=$newdata;
			$count++;
		}
		else {
			if ($datatouse[$count-1][0]==$newdata[0]&&$datatouse[$count-1][1]==$newdata[1]&&
				$datatouse[$count-1][4]==$newdata[4]&&$datatouse[$count-1][5]==$newdata[5]){
				$datatouse[$count-1][3]=$i;
				}
			else {
				$datatouse[$count]=$newdata;
				$count++;
			}
		}		
	}
	if (isset($_GET['query'])){
		global $next;
		global $conn;
		global $count;
		global $all;
		global $idroomtype;
		$price=$_GET['price'];
		$room=$_GET['room'];
		$from=$_GET['from'];
		$to=$_GET['to'];
		$amount=$_GET['amount'];
		startserver();
		$count=0;
		$all=-1;
		//echo "transform price=".$price."room=".$room."from=".$from."to=".$to."amount=".$amount;
		//--
		if ($from!='-1' && $to=='-1'){
			$to=$from;
		}
		if ($from=='-1' && $to=='-1'){
			$from=date('Ymd',time());
			$to=$from;
		}
		//--
		//transform
		if ($from>$to){
				echo "DATE ERROR";
				return;
			}
		//echo "transform price=".$price."room=".$room."from=".$from."to=".$to."amount=".$amount;
		if ($room=="Any"){
			$sql="SELECT * FROM roomtype";
			$result_name=$conn->query($sql);
			$gprice=$price;
			$gamount=$amount;
			while ($row_name=$result_name->fetch_assoc()){
				$price=$gprice;
				$amount=$gamount;
				//echo "---".$amount."---";
				$room=$row_name['roomname'];
				getidroomtype($room);
				$sql="SELECT * FROM roomtype WHERE roomname='".$room."'";
				$result_roomtype=$conn->query($sql);
				$row_roomtype=$result_roomtype->fetch_assoc();
				$sql="SELECT * FROM reservation WHERE reserv_date=".$from." AND roomtype=".$idroomtype;
				// $result_reserv=$conn->query($sql);
				// $row_reserv=$result_reserv->fetch_assoc();
				if ($price!='-1'){
					$sql=$sql." AND price=".$price;
				}
				if ($amount!='-1'){
					$sql=$sql." AND amount=".$amount;
				}
				//echo $sql;
				for ($i=$from;$i<$to;$i=$next){
					//loop at here + replace
					$result=$conn->query($sql);
					if ($result->num_rows>0){
						$row=$result->fetch_assoc();
						$sql_reserv="SELECT * FROM transaction WHERE reservid=".$row['id'];
						$result_tran=$conn->query($sql_reserv);
						if ($result_tran->num_rows>0){
							$reserv=$result_tran->num_rows;
						}
						else $reserv=0;
						groupdata($i,$row['price'],$room,$row['amount'],$reserv);
					}
					else {
						groupdata($i,$row_roomtype['price'],$room,$row_roomtype['amount'],0);
						}
					nextday($i);
					$too=$next;
					$sql=str_replace($i,$too,$sql);
					//
				}
				//loop at here + replace
				$result=$conn->query($sql);
				if ($result->num_rows>0){
					$row=$result->fetch_assoc();
					$sql_reserv="SELECT * FROM transaction WHERE reservid=".$row['id'];
					$result_reserv=$conn->query($sql_reserv);
					if ($result_reserv->num_rows>0){
						$reserv=$result_reserv->num_rows;
					}
					else $reserv=0;
					groupdata($i,$row['price'],$room,$row['amount'],$reserv);
				}
				else groupdata($i,$row_roomtype['price'],$room,$row_roomtype['amount'],0);
				//
			}
		}
		else {
				getidroomtype($room);
				$sql="SELECT * FROM roomtype WHERE roomname='".$room."'";
				$result_roomtype=$conn->query($sql);
				$row_roomtype=$result_roomtype->fetch_assoc();
				$sql="SELECT * FROM reservation WHERE reserv_date=".$from." AND roomtype=".$idroomtype;
				// $result_reserv=$conn->query($sql);
				// $row_reserv=$result_reserv->fetch_assoc();
				if ($price!='-1'){
					$sql=$sql." AND price=".$price;
				}
				if ($amount!='-1'){
					$sql=$sql." AND amount=".$amount;
				}
				//echo $sql;
				for ($i=$from;$i<$to;$i=$next){
					//loop at here + replace
					$result=$conn->query($sql);
					if ($result->num_rows>0){
						$row=$result->fetch_assoc();
						$sql_reserv="SELECT * FROM transaction WHERE reservid=".$row['id'];
						$result_tran=$conn->query($sql_reserv);
						if ($result_tran->num_rows>0){
							$reserv=$result_tran->num_rows;
						}
						else $reserv=0;
						groupdata($i,$row['price'],$room,$row['amount'],$reserv);
					}
					else {
						groupdata($i,$row_roomtype['price'],$room,$row_roomtype['amount'],0);
						}
					nextday($i);
					$too=$next;
					$sql=str_replace($i,$too,$sql);
					//
				}
				//loop at here + replace
				$result=$conn->query($sql);
				if ($result->num_rows>0){
					$row=$result->fetch_assoc();
					$sql_reserv="SELECT * FROM transaction WHERE reservid=".$row['id'];
					$result_reserv=$conn->query($sql_reserv);
					if ($result_reserv->num_rows>0){
						$reserv=$result_reserv->num_rows;
					}
					else $reserv=0;
					groupdata($i,$row['price'],$room,$row['amount'],$reserv);
				}
				else groupdata($i,$row_roomtype['price'],$room,$row_roomtype['amount'],0);
				//
		}
		showdata();
		stopserver();
	}
	function showdata(){
		global $count;
		global $datatouse;
		global $nameroomtype;
		$i=0;
		for ($i=0;$i<$count;$i++){
			$tf=(string)$datatouse[$i][2];
			$yf=$tf[0].$tf[1].$tf[2].$tf[3];
			$mf=$tf[4].$tf[5];
			$df=$tf[6].$tf[7];
			$tt=(string)$datatouse[$i][3];
			$yt=$tt[0].$tt[1].$tt[2].$tt[3];
			$mt=$tt[4].$tt[5];
			$dt=$tt[6].$tt[7];
				echo '<tr>  <td><input type="text" class="form-control" id="inputPrice'.$i.'" value="'.$datatouse[$i][0].'" style="width:100px;"/></td>
							<td><input disabled type="text" class="form-control" id="roomtype'.$i.'" value="'.$datatouse[$i][1].
							'" style="width:100px;"/></td>
							<td><div class="input-daterange form-group" >
								<span id="from'.$i.'" value='.$datatouse[$i][2].'>From '.$df."/".$mf."/".$yf.'</span>
								<span id="to'.$i.'" value='.$datatouse[$i][3].'>To '.$dt.'/'.$mt.'/'.$yt.'</span>
								</div>
					 </td>
							<td><input type="text" class="form-control" id="inputAlotment'.$i.'" value="'.$datatouse[$i][4].
							'" placeholder="'.$datatouse[$i][4].'" style="width:100px;"/></td>
							<td><input disabled type="text" class="form-control" id="reserv'.$i.'" value="'.$datatouse[$i][5].'" style="width:50px;"/></td><td></td>
						</tr>
						';
		}
	}
	function update_reserv($price,$roomtype,$from,$to,$amount){
		global $conn;
		global $idroomtype;
		startserver();
		$i=$from;
		getidroomtype($roomtype);
		$roomtype=$idroomtype;
			// echo"<script language=\"JavaScript\">";
			// echo"alert('".$roomtype."')";
			// echo"</script>";
		//for ($i=$from;$i<=$to;$i++){
		$sql = "SELECT * FROM reservation WHERE reserv_date='".$i."' AND roomtype='".$roomtype."'";
		$result=$conn->query($sql);
		if ($result->num_rows>0){
			if ($amount==0||$amount=='0'||$amount===0||$amount==='0')
				{
				$sql = "DELETE FROM reservation WHERE reserv_date=".$i." AND roomtype='".$roomtype."'";
				}
			else {
				$sql = "UPDATE reservation SET amount='".$amount."' , price='".$price."' WHERE reserv_date=".$i." AND roomtype='".$roomtype."'";
				}
			if ($conn->query($sql) === TRUE){}
		}
		else if ($amount==0||$amount=='0'||$amount===0||$amount==='0'){
				$sql = "DELETE FROM reservation WHERE reserv_date=".$i." AND roomtype='".$roomtype."'";
				if ($conn->query($sql) === TRUE){}
				}
				//echo 'insert';
		else {
			insertdatainreservation($roomtype,$i,$amount,$price);
			}
		//}
		stopserver();
	}
	if (isset($_GET['update'])){
		global $next;
		$price=$_GET['price'];
		$room=$_GET['room'];
		$from=$_GET['from'];
		$from=$from[11].$from[12].$from[13].$from[14].$from[8].$from[9].$from[5].$from[6];
		$to=$_GET['to'];
		$to=$to[9].$to[10].$to[11].$to[12].$to[6].$to[7].$to[3].$to[4];
		$amount=$_GET['amount'];
		//echo "Success Update".$price.$room.$from.$to.$amount;
		for ($i=$from;$i<$to;$i=$next){
			update_reserv($price,$room,$i,$i,$amount);
			nextday($i);
			}
		update_reserv($price,$room,$i,$i,$amount);
	}
	function startserver(){
		global $servername;
		global $username;
		global $password;
		global $dbname;
		// Create connection
		global $conn;
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
	}
	function stopserver(){
		global $conn;
		mysqli_close($conn);
	}
	function nextday($today){
		global $next;
		$day=array(31,28,31,30,31,30,31,31,30,31,30,31);
		$y;
		$m;
		$d;
		$d=$today%100;
		$today=$today/100;
		$m=$today%100;
		$today=$today/100;
		$y=$today-($m*0.01)-($d*0.0001);
		//echo "d = ".$d." m = ".$m." y = ".$y;
		if (($y%4)==0)
			$day[1]=29;
		else $day[1]=28;
		if ($m==12&&$d==31)
			$y++;
		if ($day[$m-1]==$d){
			$m=($m+1)%13;
			if ($m==0)$m=1;
			$d=01;
			}
		else $d++;
		$next=($y*10000)+($m*100)+$d;
		//echo " next= >".$y." >".$m." >".$d;
	}
	function group_check($i){
		global $datatocheck;
		global $countcheck;
		global $next;
		$newcheck=array($i,$i);
		if ($countcheck==0){
			$datatocheck[$countcheck]=$newcheck;
			$countcheck++;
		}
		else {
			nextday($datatocheck[$countcheck-1][1]);
			//echo $datatocheck[$countcheck-1][1].">>".$next."=".$i.",";
			if ($next==$i)
				$datatocheck[$countcheck-1][1]=$i;
			else {
				$datatocheck[$countcheck]=$newcheck;
				$countcheck++;
				}
		}
	}
	function query_check($room,$from,$to,$amount){
		global $conn;
		global $countcheck;
		global $sum;
		global $idroomtype;
		global $next;
		global $datatocheck;
		startserver();
		$fromret;
		$toret;
		$check=0;
		$sum=0;
		$countcheck=0;
		getidroomtype($room);
		for ($i=$from;$i<$to;$i=$next){
			//echo $i;
			$sql="SELECT * FROM reservation WHERE reserv_date=".$i." AND roomtype=".$idroomtype;
			$result=$conn->query($sql);
			if ($result->num_rows>0){
				$row=$result->fetch_assoc();
				$sql2="SELECT * FROM transaction WHERE id=".$row['id'];
				$result2=$conn->query($sql2);
				if ($result2->num_rows==$row['amount']){
					group_check($i);
					$check=1;
				}
				else {
					$sum+=$row['price'];
				}
			}
			else {
				group_check($i);
				$check=1;
			}
			nextday($i);
		}
		$sql="SELECT * FROM reservation WHERE reserv_date=".$i." AND roomtype=".$idroomtype;
			$result=$conn->query($sql);
			if ($result->num_rows>0){
				$row=$result->fetch_assoc();
				$sql2="SELECT * FROM transaction WHERE id=".$row['id'];
				$result2=$conn->query($sql2);
				if ($result2->num_rows==$row['amount']){
					group_check($i);
					$check=1;
				}
				else {
					$sum+=$row['price'];
				}
			}
			else {
				group_check($i);
				$check=1;
			}
		if ($check==0){
			echo "Can".$sum;
		}
		else {
			for ($i=0;$i<$countcheck;$i++){
				echo date('d/m/Y',strtotime($datatocheck[$i][0]))."-".date('d/m/Y',strtotime($datatocheck[$i][1]))." ";
			}
		}
		stopserver();
	}
	if (isset($_GET['query_check'])){
		query_check($_GET['room'],$_GET['from'],$_GET['to'],$_GET['amount']);
	}
	function reserv($room,$too){
		global $conn;
		global $sum;
		global $last_id;
		global $idroomtype;
		startserver();
		getidroomtype($room);
		$room=$idroomtype;
		$sql = "SELECT * FROM reservation WHERE reserv_date='".$too."' AND roomtype='".$room."'";
		$result = $conn->query($sql);
		$row=$result->fetch_assoc();
		$id=$row['id'];
		// echo "test = ".$amount."row = ".$row['amount'];
		// $sql="UPDATE reservation SET amount='".$amount."' WHERE reserv_date=".$too." AND roomtype='".$room."' ";
		// $result=$conn->query($sql);
		//echo "last_id=".$last_id."id=".$id;
		$sql = "INSERT INTO transaction (tranid,reservid) VALUES (".$last_id.",".$id.")";
		$result = $conn->query($sql);
		stopserver();
	}
	function customerdata($email,$name,$from,$to,$roomtype,$person){
		global $conn;
		global $last_id;
		startserver();
		$sql="INSERT INTO customerdata (name,email,room,reserv_date,to_date,person) VALUES ('".$name."','".$email."','".$roomtype
			."','".$from."','".$to."',".$person.")";
		if (mysqli_query($conn, $sql)) {
			$last_id = mysqli_insert_id($conn);
			//print "New record created successfully. Last inserted ID is: " . $last_id;
		} else {
			//print "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		stopserver();
		echo $last_id;
	}
	if (isset($_GET['reserv'])){
		global $next;
		$email=$_GET['email'];
		$name=$_GET['name'];
		$room=$_GET['room'];
		$person=$_GET['person'];
		$from=$_GET['from'];
		$to=$_GET['to'];
		customerdata($email,$name,$from,$to,$room,$person);
		reserv($room,$from);
		for ($i=$from;$i<=$to;$i=$next){
			nextday($i);
			$too=$next;
			//echo '>>'.$too;
			reserv($room,$too);
			}
		//echo "Success ".$email.$name.$from.$to.$room.$person;
	}
	function returnroom($room,$too){
		global $conn;
		global $idroomtype;
		getidroomtype($room);
		$room=$idroomtype;
		$sql="SELECT * FROM reservation WHERE roomtype='".$room."' AND reserv_date='".$too."'";
		$result=$conn->query($sql);
		if ($result->num_rows>0){
			while($row=$result->fetch_assoc()){
				$sql="UPDATE reservation SET";
			}
		}
		else {
			insertdatainreservation($room,$too,1,$price);
		}
	}
	if (isset($_GET['delete'])){
		global $conn;
		global $next;
		$id=$_GET['id'];
		startserver();
		$sql="SELECT * FROM customerdata WHERE id=".$id;
		$result=$conn->query($sql);
		$row=$result->fetch_assoc();
		$from=$row['reserv_date'];
		$to=$row['to_date'];
		$room=$row['room'];
		returnroom($room,$from);
		for ($i=$from;$i<=$to;$i=$next){
			nextday($i);
			$too=$next;
			returnroom($room,$too);
			}
		stopserver();
	}
	if (isset($_GET['init'])){
		global $conn;
		echo "<OPTION>Any</OPTION>";
		startserver();
		$sql ="SELECT * FROM roomtype";
		$result =$conn->query($sql);
		while($row=$result->fetch_assoc()){
			echo "<OPTION>".$row['roomname']."</OPTION>";
		}		
		stopserver();
	}
	//----------------------------------------------------------------------------//
	// Create connection
	//startserver();
	//stopserver();
	//create_database("mydb");
	// create_table("roomtype");
	 // create_table("reservation");
	 // create_table("customerdata");
	 // create_table("transaction");
	//insertdataintable("Hodjung","Jubujubu","HJ@gmail.com");
	//insertdatainreservation("Sweet","20141113","3","100$");
	//select(2);
	//delete(3);	
	//update_reserv(200,"delux",20141112,20141115,3);
	// for ($i=0;$i<$count;$i++){
		// for ($j=0;$j<=4;$j++){
			// echo $datatouse[$i][$j]." ";
			// //echo $i,$j;
		// }
		// echo "<br>";
	// }
	//header_reservation();
	//data("10","Delux","1/2","3/2","3");
	// echo "<form action=\"index.php?save=true\" method=POST>";
	// select_reserv(100,"Sweet",20141113,20141113,3);
	// ptd ("<input type='submit' value='Save'>");
	// ptd ("<input type='hidden' name='count'value=".$count." >");
	// for ($i=0;$i<$count;$i++){
		// foreach($datatouse[$i] as $names){
		  // echo '<input type="hidden" id="members'.$i.'[]" value="'. $names. '">';
		// }
	// }
	// echo "</form>";
	
	//mysqli_close($conn);
?>