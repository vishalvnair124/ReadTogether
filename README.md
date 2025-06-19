# 📚 ReadTogether – PHP Book Listing App

**ReadTogether** is a lightweight PHP web application that allows users to browse and explore a curated list of books. Built with simplicity and clarity in mind, it’s ideal for personal use, academic demos, or beginner-level projects in PHP.

---

## ✨ Features

- 📖 Displays a list of books from the database  
- 🗂️ Organized folder structure (MVC-style separation)  
- 📁 Media support for book covers or thumbnails  
- ⚙️ Easy to set up and customize  
- 🧱 Built with core PHP (no framework)

---

## 🗂️ Project Structure

```
ReadTogether/
│
├── admin/           # Admin-related functionalities (if any)
├── books/           # Book listing and display logic
├── common/          # Shared components (e.g., header, footer)
├── db/              # Database connection/configuration
├── media/           # Book images or other media assets
├── index.php        # Main landing page
└── README.md        # Project documentation
```

---

## 🚀 How to Run

1. **Clone or download the repository:**

```bash
git clone https://github.com/vishalvnair124/ReadTogether.git
```

2. **Place the folder inside your server root:**

- For **XAMPP**: `htdocs/ReadTogether`
- For **WAMP**: `www/ReadTogether`

3. **Create a database:**

- Import the provided SQL file (if any) in `db/` folder via phpMyAdmin.
- Update `db/connection.php` with your DB credentials.

4. **Open your browser:**

```
http://localhost/ReadTogether/
```

---

## 🔧 Requirements

- PHP 7.x or higher  
- MySQL or MariaDB  
- Apache/Nginx (use XAMPP, WAMP, LAMP, etc.)

---

## 🧠 Future Enhancements

- 🔍 Add search and filter options  
- 📝 Book reviews/comments  
- 👤 User login and personalized reading list  
- 🎨 Improve UI with Bootstrap or Tailwind

---

## 👨‍💻 Made With ❤️ By

**Vishal V Nair**  
🚀 MCA @ TKM | Tech Enthusiast  
🔧 Skilled in PHP | MySQL | Web Dev  
📫 GitHub: [@vishalvnair124](https://github.com/vishalvnair124)
