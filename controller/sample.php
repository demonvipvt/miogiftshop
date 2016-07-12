 <?php
	// insert data
		$sql = "INSERT INTO MyGuests (firstname, lastname, email)
		VALUES ('John', 'Doe', 'john@example.com')";
		$sql = "INSERT INTO MyGuests (firstname, lastname, email)
		VALUES ('John', 'Doe', 'john@example.com');";
		$sql .= "INSERT INTO MyGuests (firstname, lastname, email)
		VALUES ('Mary', 'Moe', 'mary@example.com');";
		$sql .= "INSERT INTO MyGuests (firstname, lastname, email)
		VALUES ('Julie', 'Dooley', 'julie@example.com')";

		if ($conn->query($sql) === TRUE) {
			$last_id = $conn->insert_id;
		    echo "New record created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}

	// prepare and bind
		$stmt = $conn->prepare("INSERT INTO MyGuests (firstname, lastname, email) VALUES (?, ?, ?)");
		$stmt->bind_param("sss", $firstname, $lastname, $email);

		// set parameters and execute
		$firstname = "John";
		$lastname = "Doe";
		$email = "john@example.com";
		$stmt->execute();

		$firstname = "Mary";
		$lastname = "Moe";
		$email = "mary@example.com";
		$stmt->execute();

		$firstname = "Julie";
		$lastname = "Dooley";
		$email = "julie@example.com";
		$stmt->execute();

		echo "New records created successfully";

		$stmt->close();

	// select data
		$sql = "SELECT id, firstname, lastname FROM MyGuests";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		        echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
		    }
		} else {
		    echo "0 results";
		}
?> 