<?php
// Include session check to ensure the user is logged in
include 'session_checkup.php';

// Include database configuration file for database connection
require_once '../common/config.php';

// Handle book insertion when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_book'])) {
    // Retrieve form data
    $title = $_POST['title'];
    $authors = $_POST['authors'];
    $edition = $_POST['edition'];
    $publisher = $_POST['publisher'];

    // Retrieve uploaded file names
    $imageName = $_FILES['image']['name'];
    $sourceName = $_FILES['source']['name'];

    // Define paths for storing uploaded files
    $imagePath = '../media/' . basename($imageName);
    $sourcePath = '../media/' . basename($sourceName);

    // Move uploaded files to the specified paths
    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    move_uploaded_file($_FILES['source']['tmp_name'], $sourcePath);

    // Insert book details into the database
    $query = "INSERT INTO books (title, authors, edition, publisher, image, source) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $title, $authors, $edition, $publisher, $imageName, $sourceName);
    $stmt->execute();

    // Redirect to the same page after successful insertion
    header("Location: index.php");
    exit;
}

// Handle book deletion when the delete link is clicked
if (isset($_GET['delete'])) {
    $bookId = $_GET['delete'];

    // Delete the book record from the database
    $conn->query("DELETE FROM books WHERE accession_number = $bookId");

    // Redirect to the same page after deletion
    header("Location: index.php");
    exit;
}

// Fetch all books from the database to display in the table
$books = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin - Book Management</title>
    <style>
        /* Styling for buttons */
        a.button,
        button.button {
            padding: 8px 14px;
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            /* Blue color */
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        /* Hover effect for buttons */
        a.button:hover,
        button.button:hover {
            background-color: #0056b3;
        }

        /* Styling for delete buttons */
        a.button.delete {
            background-color: #dc3545;
            /* Red color */
        }

        /* Hover effect for delete buttons */
        a.button.delete:hover {
            background-color: #a71d2a;
        }

        /* General body styling */
        body {
            font-family: Arial;
            margin: 20px;
        }

        /* Styling for form inputs and buttons */
        input,
        select,
        button {
            padding: 8px;
            margin: 5px;
        }

        /* Styling for the form container */
        form {
            background: #f2f2f2;
            padding: 15px;
            border-radius: 8px;
        }

        /* Styling for the table */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        /* Styling for table headers and cells */
        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        /* Styling for table headers */
        th {
            background-color: #eee;
        }

        /* Styling for the header section */
        .header {
            width: 100%;
            height: 70px;
            background-color: #333;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            box-sizing: border-box;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        /* Styling for the logo in the header */
        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        /* Styling for the logout button */
        .logout .button {
            background-color: #f39c12;
            /* Orange color */
        }

        /* Hover effect for the logout button */
        .logout .button:hover {
            background-color: #c87f0a;
        }
    </style>
    <script>
        // Validate the form to ensure all required fields are filled
        function validateForm() {
            let required = ['title', 'authors', 'edition', 'publisher', 'image', 'source'];
            for (let id of required) {
                let el = document.getElementById(id);
                if (!el.value) {
                    alert("Please fill in " + id);
                    return false;
                }
            }
            return true;
        }
    </script>
</head>

<body>
    <!-- Header section -->
    <div class="header">
        <div class="logo"> ReadTogether</div>
        <div class="logout">
            <a href="logout.php" class="button">Logout</a>
        </div>
    </div>

    <!-- Form to add a new book -->
    <h2>📚 Add New Book</h2>
    <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm();">
        <input type="text" id="title" name="title" placeholder="Title">
        <input type="text" id="authors" name="authors" placeholder="Authors">
        <input type="text" id="edition" name="edition" placeholder="Edition">
        <input type="text" id="publisher" name="publisher" placeholder="Publisher">
        <input type="file" id="image" name="image" accept="image/*">
        <input type="file" id="source" name="source" accept=".pdf">
        <button type="submit" name="add_book">Add Book</button>
    </form>

    <!-- Table to display the list of books -->
    <h2>📖 Book List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Authors</th>
            <th>Edition</th>
            <th>Publisher</th>
            <th>Image</th>
            <th>Source</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $books->fetch_assoc()): ?>
            <tr>
                <td><?= $row['accession_number'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['authors'] ?></td>
                <td><?= $row['edition'] ?></td>
                <td><?= $row['publisher'] ?></td>
                <td>
                    <!-- Display book image or fallback to a default image -->
                    <img src="../media/<?= $row['image'] ?>" width="60" onerror="this.onerror=null; this.src='../media/unknown.jpg';">
                </td>
                <td>
                    <!-- Link to download the book source file -->
                    <a href="../media/<?= $row['source'] ?>" class="button" target="_blank">Download</a>
                </td>
                <td>
                    <!-- Link to delete the book -->
                    <a href="?delete=<?= $row['accession_number'] ?>" class="button delete" onclick="return confirm('Delete this book?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>

</html>