<?php

include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM pubs";
    
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $output = '';

        while ($product = mysqli_fetch_assoc($result)) {
            $output .= "
                <div class='product'>
                    <img src='uploads/{$product['imgname']}' alt='Product Image'>
                    <h3>{$product['name']}</h3>
                    <h5>ID: {$product['id']}</h5>
                    <p>Description: {$product['description']}</p>
                    <p>Price: \${$product['price']}</p>
                    <form action='operation.php' method='post'>
                        <input type='text' name='idd' value='{$product['id']}' style='display: none;'>
                    </form>
                </div>
            ";
        }

        echo $output;
    } else {
        echo "Error in the SQL query: " . mysqli_error($conn);
    }
} else {
    switch(true) { 
        case isset($_POST['add']):
            $name = mysqli_real_escape_string($conn, $_POST['name']);
		$description = mysqli_real_escape_string($conn, $_POST['desc']);
		$price = mysqli_real_escape_string($conn, $_POST['price']);
	
		// File upload logic
		$uploadResult = handleFileUpload();
	
		if ($uploadResult['success']) {
			// Insert product details into the database
			$sql = "INSERT INTO pubs (name, description, price, imgname) VALUES ('$name', '$description', '$price', '{$uploadResult['file']}')";
	
			$result = mysqli_query($conn, $sql);
	
			if ($result) {
				echo "Record inserted successfully";
			} else {
				echo "Error inserting record: " . mysqli_error($conn);
			}
		} else {
			echo $uploadResult['message'];
		}

            break;
        
        case isset($_POST['Edit']):
            $name = mysqli_real_escape_string($conn, $_POST['namee']);
		$description = mysqli_real_escape_string($conn, $_POST['desce']);
		$price = mysqli_real_escape_string($conn, $_POST['pricee']);
		$id = mysqli_real_escape_string($conn, $_POST['id']);
	
		$sql = "UPDATE pubs SET name='$name', description='$description', price='$price' WHERE id=$id";
	
		$result = mysqli_query($conn, $sql);
	
		if ($result) {
			echo "Record updated successfully";
		} else {
			echo "Error updating record: " . mysqli_error($conn);
		}
            break;
    
        case isset($_POST['delete']):
			$idd = mysqli_real_escape_string($conn, $_POST['idd']);
			$sql = "DELETE FROM pubs WHERE id =$idd";
			$result = mysqli_query($conn, $sql);
	
			if ($result) {
				echo "Deleted successfully";
			} else {
				echo "Error deleting record: " . mysqli_error($conn);
			}
            break;
    
        default:
            break;
    }
	
	
	
	
	
	function handleFileUpload() {
		$targetDirectory = "uploads/";
		$targetFile = $targetDirectory . basename($_FILES["photo"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
	
		if (isset($_POST["add"])) {
			$check = getimagesize($_FILES["photo"]["tmp_name"]);
			if ($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				return ['success' => false, 'message' => "File is not an image."];
			}
		}
	
		// Check file size
		if ($_FILES["photo"]["size"] > 500000) {
			return ['success' => false, 'message' => "Sorry, your file is too large."];
		}
	
		// Allow certain file formats
		$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
		if (!in_array($imageFileType, $allowedExtensions)) {
			return ['success' => false, 'message' => "Sorry, only JPG, JPEG, PNG, GIF files are allowed."];
		}
	
	
		if ($uploadOk == 0) {
			return ['success' => false, 'message' => "Sorry, your file was not uploaded."];
		} else {
			if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
				return ['success' => true, 'file' => htmlspecialchars(basename($_FILES["photo"]["name"]))];
			} else {
				return ['success' => false, 'message' => "Sorry, there was an error uploading your file."];
			}
		}
	}
	
}


?>
