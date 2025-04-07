<?php
// Include the database configuration file to establish a connection
require_once '../common/config.php';

// Check if a book ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "No book ID provided."; // Display an error message if no ID is provided
    exit;
}

// Retrieve the book ID from the URL and sanitize it
$id = intval($_GET['id']);

// Prepare a SQL statement to fetch the book details based on the ID
$stmt = $conn->prepare("SELECT * FROM books WHERE accession_number = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the book exists in the database
if ($result->num_rows === 0) {
    echo "Book not found."; // Display an error message if the book is not found
    exit;
}

// Fetch the book details as an associative array
$book = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Set the page title dynamically based on the book title -->
    <title><?= htmlspecialchars($book['title']) ?> - Book View</title>
    <style>
        /* General styling for the body */
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 30px;
        }

        /* Styling for the main container */
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Styling for the book information section */
        .book-info {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        /* Styling for the book cover image */
        .book-info img {
            width: 200px;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Styling for the book details section */
        .details {
            flex: 1;
        }

        /* Styling for the book title */
        .details h2 {
            margin-top: 0;
        }

        /* Styling for the book details paragraphs */
        .details p {
            margin: 6px 0;
        }

        /* Styling for the embedded PDF viewer */
        iframe {
            margin-top: 30px;
            width: 100%;
            height: 800px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        /* Styling for the back button */
        a.button {
            display: inline-block;
            margin-top: 15px;
            background: #007BFF;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
        }

        /* Hover effect for the back button */
        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Display the book information -->
        <div class="book-info">
            <!-- Display the book cover image or a fallback image if not available -->
            <img src="../media/<?= $book['image'] ?>" onerror="this.src='../media/unknown.jpg';">
            <div class="details">
                <!-- Display the book title -->
                <h2><?= htmlspecialchars($book['title']) ?></h2>
                <!-- Display the book author -->
                <p><strong>Author:</strong> <?= htmlspecialchars($book['authors']) ?></p>
                <!-- Display the book edition -->
                <p><strong>Edition:</strong> <?= htmlspecialchars($book['edition']) ?></p>
                <!-- Display the book publisher -->
                <p><strong>Publisher:</strong> <?= htmlspecialchars($book['publisher']) ?></p>
                <!-- Back button to return to the book list -->
                <a class="button" href="index.php">← Back to List</a>
            </div>
        </div>

        <!-- Display the embedded PDF viewer if a source file is available -->
        <?php if (!empty($book['source'])): ?>
            <iframe src="../media/<?= $book['source'] ?>"></iframe>
        <?php else: ?>
            <!-- Display a message if no PDF is available -->
            <p style="margin-top:30px; color:red;">❌ No PDF available for this book.</p>
        <?php endif; ?>
    </div>

</body>

</html>