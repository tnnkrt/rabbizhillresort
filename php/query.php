<?php
	$count=0;
	$datatouse=array(array());
	$servername = "192.168.137.98";
	$username = "developers";
	$password = "1234";
	$dbname = "resort";
	$conn;
	$next;
	$sum=0;
	$last_id=0;
	function tr(){
		print "<tr>";
	}
	function trc(){
		print "</tr>";
	}
	function td(){
		print "<td>";
	}
	function tdc(){
		print "</td>";
	}
	function ptd($fname="default") {
		print "<td>";
		print "$fname";
		print "</td>";
	}
	function header_reservation(){
		tr();
		ptd("Price");
		ptd("Room Type");
		ptd("From Date");
		ptd("To Date");
		ptd("Amount");
		ptd("Search");
		trc();
	}
	function data($Price,$Room,$From,$To,$Amount){
		tr();
		ptd("$Price $");
		ptd("$Room");
		ptd("$From");
		ptd("$To");
		ptd("$Amount");
		trc();
	}
	function insertdataintable($rname,$price,$amount,$pageid=0){
		global $conn;
		$sql = "INSERT INTO roomtype (roomname,price,amount,pageid)
		VALUES ('".$rname."', '".$price."', '".$amount."','".$pageid."')";
		if (mysqli_query($conn, $sql)) {
			$last_id = mysqli_insert_id($conn);
			print "New record created successfully. Last inserted ID is: " . $last_id;
		} else {
			print "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	function insertdatainreservation($rtype,$reserv_date,$amount,$price){
		global $conn;
		$sql = "INSERT INTO reservation (roomtype,reserv_date,amount,price)
		VALUES ('".$rtype."', '".$reserv_date."', '".$amount."','".$price."')";
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
						tranid INT(6),
						reservid INT (6),
						reg_date TIMESTAMP,
						INDEX fk_tran (tranid),
						CONSTRAINT fk_tran FOREIGN KEY (tranid) REFERENCES customer(id),
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
	function delete($id=0,$fname="default",$lname="default"){
		global $conn;
		if (id!==0){
			print "id = $id";
			$sql = "DELETE FROM MyGuests WHERE id=".$id.";";
			if ($conn->query($sql) === TRUE) {
				print "Record deleted successfully";
			} else {
				print "Error deleting record: " . $conn->error;
			}
		}
		else print "id = 0";
	}
	function select_reserv($price=100,$roomtype='-1',$from,$to,$amount='-1'){
		global $conn;
		global $datatouse;
		global $count;
		$reserv=-1;
		startserver();
		$firstquery=0;
		if ($from==='-1'){
			$from=date('Ymd',time());
			$to=$from;
		}
		for ($i=$from;$i<=$to;$i++){
			$sql = "SELECT * FROM reservation WHERE reserv_date = ".$i;
			if ($price!=='-1')
				$sql =$sql." AND price='".$price."'";
			if ($roomtype!=='-1')
				$sql=$sql." AND roomtype='".$roomtype."'";
			if ($amount!=='-1'&&$amount!=='0')
				$sql =$sql." AND amount=".$amount;
			//echo $sql." ";
			$result = $conn->query($sql);
			//echo $result->num_rows;
			//echo $amount;
			if ($result->num_rows>0){
				while($row = $result->fetch_assoc()){
						$sql = "SELECT * FROM transaction WHERE reservid=".$row['id'];
						$result2 = $conn->query($sql);
						if ($reserv==-1){
							if ($result2->num_rows>0){
								$reserv=$result2->num_rows;
							}
							else $reserv=0;
						}
						else {
							if ($reserv!=($result2->num_rows)){
								store_row_reserv($sprice,$sroomtype,$sfrom,$sto,$samount,$reserv);
								$sprice=$row["price"];
								$sfrom=$i;
								$sto=$i;
								$sroomtype=$row["roomtype"];
								$samount=$row["amount"];
								$reserv=$result2->num_rows;
							}
						}
						if ($firstquery===0){//first time
							//echo "first time";
							$sprice=$row["price"];
							$sfrom=$i;
							$sto=$i;
							$sroomtype=$row["roomtype"];
							$samount=$row["amount"];
							$firstquery=1;
						}
						else {//new first time
							if ($row["amount"]!==$samount||$row["price"]!==$sprice){//echo $amount."inner met";
								if ($amount==='-1'||($amount==$samount)){
									store_row_reserv($sprice,$sroomtype,$sfrom,$sto,$samount,$reserv);
									}
								$sprice=$row["price"];
								$sfrom=$i;
								$sto=$i;
								$sroomtype=$row["roomtype"];
								$samount=$row["amount"];
							}
							else {
								$sto=$i;
							}
						}
				}
			}
			else {
				if ($firstquery===0){//first time
					//echo "inner not met first time";
					$sprice=100;
					$sfrom=$i;
					$sto=$i;
					$sroomtype=$roomtype;
					$samount=0;
					$firstquery=1;
				}
				else {
					if ($samount!==0){//echo "inner not met";
						if ($amount==='-1'||($amount==$samount)){
							store_row_reserv($sprice,$sroomtype,$sfrom,$sto,$samount,$reserv);
							}
						$sprice=100;
						$sfrom=$i;
						$sto=$i;
						$sroomtype=$roomtype;
						$samount=0;
					}
					else {
						$sto=$i;
					}
				}
			}
		}//echo "outer";
		if ($amount==='-1'||($amount==$samount)){
			store_row_reserv($sprice,$sroomtype,$sfrom,$sto,$samount,$reserv);
			}
		stopserver();
		//echo $count;
		$i=0;
		for ($i=0;$i<$count;$i++){
			$tf=$datatouse[$i][2];
			$yf=$tf[0].$tf[1].$tf[2].$tf[3];
			$mf=$tf[4].$tf[5];
			$df=$tf[6].$tf[7];
			$tt=$datatouse[$i][3];
			$yt=$tt[0].$tt[1].$tt[2].$tt[3];
			$mt=$tt[4].$tt[5];
			$dt=$tt[6].$tt[7];
				echo '<tr>
							<td><input type="text" class="form-control" id="inputPrice'.$i.'" value="'.$datatouse[$i][0].'" style="width:100px;"/></td>
							<td><div id="roomtype'.$i.'">'.$datatouse[$i][1].'</div></td>
							<td><div class="input-daterange form-group" >
								<span id="from'.$i.'" value='.$datatouse[$i][2].'>From '.$df."/".$mf."/".$yf.'</span>
								<span id="to'.$i.'" value='.$datatouse[$i][3].'>To '.$dt."/".$mt."/".$yt.'</span>
								</div>
					 </td>
							<td><input type="text" class="form-control" id="inputAlotment'.$i.'" value="'.$datatouse[$i][4].'" style="width:100px;"/></td>
							<td><input disabled type="text" class="form-control" id="reserv'.$i.'" value="'.$datatouse[$i][5].'" style="width:40px;"/></td><td></td>
						</tr>
						';
		}
	}
	function store_row_reserv($sprice,$sroomtype,$sfrom,$sto,$samount,$reserv){
		global $count;
		global $datatouse;
		if ($reserv==-1)$reserv=0;
		$newdata = array (
						"$sprice",
						"$sroomtype",
						"$sfrom",
						"$sto",
						"$samount",
						"$reserv"
						);
		$datatouse[$count]=$newdata;
		//echo $datatouse[$count][0];
		$count++;
	}
	function update_reserv($price,$roomtype,$from,$to,$amount){
		global $conn;
		startserver();
		$i=$from;
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
		else insertdatainreservation($roomtype,$i,$amount,$price);
		
		//}
		stopserver();
	}
	function update($id=0,$fname="default",$lname="default"){
		global $conn;
		$sql = "UPDATE MyGuests SET lastname='Doe' WHERE id=2";
		if ($conn->query($sql) === TRUE) {
			print "Record updated successfully";
		} else {
			print "Error updating record: " . $conn->error;
		}
	}
	function forupdate(){
		startserver();
		$count=$_POST["count"];
		//echo $_POST["count"]."<br>";
		for ($i=0;$i<$count;$i++){
			$data[$i]=$_POST['members'.$i];
			if ($_POST['edit'.$i]!==''){
				update_reserv($data[$i][0],$data[$i][1],$data[$i][2],$data[$i][3],$_POST['edit'.$i]);
			}
		}
		stopserver();
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
	if (isset($_GET['query'])){
		//echo "test".$_GET['price'].$_GET['room'].$_GET['from'].$_GET['to'].$_GET['amount']."test";
		select_reserv($_GET['price'],$_GET['room'],$_GET['from'],$_GET['to'],$_GET['amount']);
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
	if (isset($_GET['update'])){
		$price=$_GET['price'];
		$room=$_GET['room'];
		$from=$_GET['from'];
		$from=$from[11].$from[12].$from[13].$from[14].$from[8].$from[9].$from[5].$from[6];
		$to=$_GET['to'];
		$to=$to[9].$to[10].$to[11].$to[12].$to[6].$to[7].$to[3].$to[4];
		$amount=$_GET['amount'];
		//echo "Success Update".$price.$room.$from.$to.$amount;
		update_reserv($price,$room,$from,$from,$amount);
		for ($i=$from;$i<=$to;$i=$next){
			nextday($i);
			$too=$next;
			update_reserv($price,$room,$too,$too,$amount);
			}
	}
	function query_check($room,$from,$to,$amount){
		global $conn;
		global $count;
		global $sum;
		startserver();
		$fromret;
		$toret;
		$check=0;
		for ($i=$from;$i<=$to;$i++){
			$sql="SELECT * FROM reservation WHERE reserv_date='".$i."' AND roomtype='".$room."'";
			$result=$conn->query($sql);
			if ($result->num_rows>0){
				$row=$result->fetch_assoc();
				
				$sql = "SELECT * FROM transaction WHERE reservid=".$row['id'];
				$result2 = $conn->query($sql);
				if ($row['amount']-$result2->num_rows>0){
					$sum=$sum+$row['price'];
				}
				else {
					$check=1;
					if ($fromret==$i){
						$fromret=$i;
						$toret=$i;
						}
					else if ($i-$toret==1){
						$toret=$i;
					}
					else if ($fromret==''){
						$fromret=$i;
						$toret=$i;
					}				
					else {
						$shin=$fromret[6].$fromret[7].'/'.$fromret[4].$fromret[5].'/'.$fromret[0].$fromret[1].$fromret[2].$fromret[3];
						$shhin=$toret[6].$toret[7].'/'.$toret[4].$toret[5].'/'.$toret[0].$toret[1].$toret[2].$toret[3];
						echo date("d/m/Y", strtotime($fromret)).'-'.date("d/m/Y", strtotime($toret)).' ';
						$fromret=$i;
						$toret=$i;
					}
				}
				
				}
			else {
				$check=1;
				if ($fromret==$i){
					$fromret=$i;
					$toret=$i;
					}
				else if ($i-$toret==1){
					$toret=$i;
				}
				else if ($fromret==''){
					$fromret=$i;
					$toret=$i;
				}				
				else {
					$shin=$fromret[6].$fromret[7].'/'.$fromret[4].$fromret[5].'/'.$fromret[0].$fromret[1].$fromret[2].$fromret[3];
					$shhin=$toret[6].$toret[7].'/'.$toret[4].$toret[5].'/'.$toret[0].$toret[1].$toret[2].$toret[3];
					echo date("d/m/Y", strtotime($fromret)).'-'.date("d/m/Y", strtotime($toret)).' ';
					$fromret=$i;
					$toret=$i;
				}
			}
		}
		if ($check==0)
			echo "Can ".$sum." ";
		else {
			$shout=$fromret[6].$fromret[7].'/'.$fromret[4].$fromret[5].'/'.$fromret[0].$fromret[1].$fromret[2].$fromret[3];
			$returnout=$toret[6].$toret[7].'/'.$toret[4].$toret[5].'/'.$toret[0].$toret[1].$toret[2].$toret[3];
			echo date("d/m/Y", strtotime($fromret)).'-'.date("d/m/Y", strtotime($toret)).' ';
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
		startserver();
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
	//create_table("roomtype");
	//create_table("reservation");
	//create_table("customerdata");
	//create_table("transaction");
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