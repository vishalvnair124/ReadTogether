<!DOCTYPE html>
<html>

<head>
    <title>Book Gallery</title>
    <style>
        /* General styling for the body */
        body {
            font-family: Arial;
            padding: 20px;
            background: #f8f8f8;
        }

        /* Center the heading */
        h2 {
            text-align: center;
        }

        /* Styling for the search bar */
        #searchBar {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 20px auto;
            max-width: 500px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        /* Styling for the grid layout of book cards */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        /* Styling for individual book cards */
        .book-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.2s;
        }

        /*  enlarge the book card */
        .book-card:hover {
            transform: scale(1.02);
        }

        /* Styling for book cover images */
        .book-card img {
            width: 100%;
            height: 250px;
            /* Set a fixed height for consistency */
            object-fit: cover;
            /* image fits within the container */
        }

        /* Styling for the book title */
        .book-title {
            padding: 10px;
            font-weight: bold;
        }

        /*  View butto */
        .book-actions {
            padding: 10px;
        }

        /* Styling for buttons */
        .button {
            padding: 6px 10px;
            margin-top: 5px;
            background-color: #007BFF;
            /* Blue color */
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        /* Add a hover effect for buttons */
        .button:hover {
            background-color: #0056b3;
        }

        /* Styling for the "no results" message */
        #noResults {
            text-align: center;
            color: red;
            /* Highlight the message in red */
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- Page heading -->
    <h2>📚 Browse Books</h2>

    <!-- Search form for filtering books by title -->
    <form method="GET" action="">
        <input type="text" id="searchBar" name="search" placeholder="Search by title..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    </form>

    <!-- Grid container for displaying book cards -->
    <div class="grid">
        <?php
        // Include the database configuration file
        require_once '../common/config.php';

        // Get the search keyword if provided
        $search = $_GET['search'] ?? '';

        // Prepare SQL query with optional search functionality
        if (!empty($search)) {
            // Use a prepared statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ?");
            $searchTerm = "%" . $search . "%";
            $stmt->bind_param("s", $searchTerm);
        } else {
            // Fetch all books if no search keyword is provided
            $stmt = $conn->prepare("SELECT * FROM books");
        }

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any books are found
        if ($result->num_rows > 0):
            // Loop through each book and display it as a card
            while ($book = $result->fetch_assoc()):
        ?>
                <div class="book-card" data-title="<?= strtolower($book['title']) ?>">
                    <!-- Display the book cover image or a fallback image if not available -->
                    <img src="../media/<?= $book['image'] ?>" onerror="this.src='../media/unknown.jpg';">
                    <!-- Display the book title -->
                    <div class="book-title"><?= $book['title'] ?></div>
                    <!-- Action buttons for the book -->
                    <div class="book-actions">
                        <!-- Link to view the book details -->
                        <a class="button" href="viewBook.php?id=<?= $book['accession_number'] ?>">View</a>
                    </div>
                </div>
        <?php
            endwhile;
        else:
            //no matching books are found
            echo "<p id='noResults'>❌ No matching books found.</p>";
        endif;
        ?>
    </div>

</body>

</html>