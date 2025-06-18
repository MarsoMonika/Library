// DOM references
const form = document.getElementById('bookForm');
const titleInput = document.getElementById('title');
const authorInput = document.getElementById('author');
const yearInput = document.getElementById('publishYear');
const availableInput = document.getElementById('isAvailable');
const submitBtn = document.getElementById('submitBtn');
const cancelBtn = document.getElementById('cancelBtn');
const addBookPrompt = document.getElementById('addBookPrompt');
const showFormBtn = document.getElementById('showFormBtn');
const searchInput = document.getElementById('searchInput');
const booksGrid = document.getElementById('booksGrid');

let editingBookId = null;




 // Show the form for adding or editing a book, isEditing - If true, the form is in edit mode

function showForm(isEditing = false) {
    form.style.display = 'block';
    cancelBtn.style.display = 'inline-block';
    addBookPrompt.style.display = 'none';
    searchInput.style.display = 'none';
    submitBtn.textContent = isEditing ? 'Save' : 'Add Book';
}

//Hide the form

function hideForm() {
    form.reset();
    form.style.display = 'none';
    cancelBtn.style.display = 'none';
    addBookPrompt.style.display = 'block';
    searchInput.style.display = 'block';
    submitBtn.textContent = 'Add Book';
    editingBookId = null;
}

//necessary event listener for correct working
document.addEventListener('DOMContentLoaded', fetchBooks);
searchInput.addEventListener('input', handleSearch);

showFormBtn.addEventListener('click', () => {
    showForm(false);
});

cancelBtn.addEventListener('click', hideForm);

form.addEventListener('submit', handleFormSubmit);

booksGrid.addEventListener('click', (e) => {
    if (e.target.matches('button.edit')) {
        const id = e.target.dataset.id;
        editBook(id);
    } else if (e.target.matches('button.delete')) {
        const id = e.target.dataset.id;
        deleteBook(id);
    }
});

 //Fetch and render all books

async function fetchBooks() {
    try {
        const res = await fetch('./api/books.php');
        const books = await res.json();
        renderBooks(books);
    } catch (err) {
        alert('Could not fetch books');
    }
}

// listing books

function renderBooks(books) {
    booksGrid.innerHTML = '';

    books.forEach(book => {
        const card = document.createElement('div');
        card.className = 'book-card';
        card.innerHTML = `
            <div class="book-title">${book.title}</div>
            <div class="book-author">by ${book.author}</div>
            <div class="book-meta">${book.publishYear} • ${book.isAvailable ? 'Available' : 'Checked out'}</div>
            <div class="book-actions">
                <button class="edit" data-id="${book.id}">Edit</button>
                <button class="delete" data-id="${book.id}">Delete</button>
            </div>
        `;
        booksGrid.appendChild(card);
    });
}


 // Handle form for adding or editing a book

async function handleFormSubmit(e) {
    e.preventDefault();

    const book = {
        title: titleInput.value,
        author: authorInput.value,
        publishYear: yearInput.value,
        isAvailable: availableInput.checked
    };

    const method = editingBookId ? 'PUT' : 'POST';
    if (editingBookId) book.id = editingBookId;

    try {
        const res = await fetch('./api/books.php', {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(book)
        });

        if (!res.ok) throw new Error('Failed to save book');
        await fetchBooks();
        hideForm();
    } catch (err) {
        alert('Could not save book');
    }
}


 //Load a book by ID and fill the form with the current data for editing

async function editBook(id) {
    try {
        const res = await fetch(`./api/books.php?id=${id}`);
        if (!res.ok) throw new Error('Failed to fetch book');
        const book = await res.json();

        titleInput.value = book.title;
        authorInput.value = book.author;
        yearInput.value = book.publishYear;
        availableInput.checked = book.isAvailable;
        editingBookId = book.id;

        showForm(true);
    } catch (err) {
        alert('Could not fetch book data');
    }
}

 //Delete a book by ID

async function deleteBook(id) {
    if (!confirm('Are you sure you want to delete this book?')) return;

    try {
        const res = await fetch(`./api/books.php?id=${id}`, {
            method: 'DELETE'
        });

        if (!res.ok) throw new Error('Failed to delete book');
        await fetchBooks();
    } catch (err) {
        alert('Could not delete book');
    }
}

//Filter books by search input

async function handleSearch() {
    const value = searchInput.value.trim();
    const url = value
        ? `./api/books.php?search=${encodeURIComponent(value)}`
        : './api/books.php';

    try {
        const res = await fetch(url);
        const books = await res.json();
        renderBooks(books);
    } catch (err) {
        alert('Search failed');
    }
}