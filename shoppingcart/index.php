<?php
include('book_sc_fns.php');

// The shopping cart needs sessions, so start one
session_start();
do_html_header('Welcome to Book-O-Rama');

echo '<p>Please choose a category:</p>';

$cat_array = get_categories();
display_categories($cat_array);

if(isset($_SESSION['admin_user'])) {
    display_button('admin.php', 'admin-menu', 'Admin Menu');
}
do_html_footer();
