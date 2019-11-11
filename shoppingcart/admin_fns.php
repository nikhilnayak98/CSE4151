<?php

function display_category_form($category = '') {
    /**
     This displays the category form.
     This form can be used for inserting or editing categories.
     To insert, don't pass any parameters.  This will set $edit
     to false, and the form will go to insert_category.php.
     To update, pass an array containing a category.  The
     form will contain the old data and point to update_category.php.
     It will also add a "Delete category" button.
     */
    $edit = is_array($category);
    ?>
    <form method="post" action="<?php echo $edit ? 'edit_category.php': 'insert_category.php'; ?>">
        <table border="0">
            <tr>
                <td>Category Name</td>
                <td><input type="text" name="catname" size="40" maxlength="40"
                    value="<?php echo $edit ? $category['catname']:''; ?>"></td>
            </tr>
            <tr>
                <td <?php if(!$edit) {echo 'colspan=2';} ?> align="center" >
                    <?php
                    if($edit) {
                        echo "<input type='hidden' name='catid' value='".$category['catid']."' />";
                    }
                    ?>
                    <input type="submit" value="<?php echo $edit ? 'Update': 'Add'; ?> Category"></form>
                </td>
                <?php
                    if($edit) {
                        // allow deletion of existing categories
                        echo "<td>
                            <form method='post' action='delete_category.php'>
                                <input type='hidden' name='catid' value='".$category['catid']."'>
                                <input type='submit' value='Delete category'>
                            </form>
                        </td>";
                    }
                ?>
            </tr>
        </table>
    <?php
}

function display_book_form($book = '') {
/**
 * This displays the book form. It is very similar to the category form.
 * This form can be used for inserting or editing books.
 * To insert, don't pass any parameters. This will set $edit
 * to false, and the form will go to insert_book.php.
 * To update, pass an array containing a book.
 * The form will be displayed with old data and point to update_book.php.
 * It will also add a "Delete book"
 */
    $edit = is_array($book);
    /**
     * most of the form is in plain HTML with some optional
     * PHP bits throughout
     */
    ?>
    <form method="post" action="<?php echo $edit ? 'edit_book.php': 'insert_book.php' ?>">
        <table border="0">
            <tr>
                <td>ISBN:</td>
                <td><input type="text" name="isbn" value="<?php echo $edit ? $book['isbn']: '' ?>"></td>
            </tr>
            <tr>
                <td>Book title</td>
                <td><input type="text" name="title" value="<?php echo $edit ? $book['title']: '' ?>"></td>
            </tr>
            <tr>
                <td>Book Author:</td>
                <td><input type="text" name="author" value="<?php echo $edit ? $book['author']: '' ?>"></td>
            </tr>
            <tr>
                <td>Category:</td>
                <td>
                    <select name="catid" id="">
                        <?php
                        // list of possible categories come from database
                        $cat_array = get_categories();
                        foreach($cat_array as $thiscat) {
                            echo "<option value='".$thiscat['catid']."'";
                            // if existing book, put in current category
                            if(($edit) && ($thiscat['catid'] == $book['catid'])) {
                                echo 'selected';
                            }
                            echo ">".$thiscat['catname'].'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Price:</td>
                <td>
                    <input type="text" name="price" value="<?php echo $edit ? $book['price']: '' ?>">
                </td>
            </tr>
            <tr>
                <td>Description:</td>
                <td>
                    <textarea name="description" id="" cols="50" rows="3">
                        <?php
                        echo $edit ? $book['description']: '';
                        ?>
                    </textarea>
                </td>
            </tr>
            <tr>
                <td <?php if(!$edit) {echo "colspan=2";} ?> align="center">
                    <?php
                        if($edit) {
                            // we need the old isbn to find book in database
                            // if the isbn is being updated
                            echo "<input type='hidden' name='oldisbn' value='".$book['isbn']."' />";
                        }
                    ?>
                    <input type="submit" value="<?php echo $edit ? 'Update': 'Add' ?> Book">
                    </form>
                </td>
                <?php
                     if($edit) {
                         echo "<td>
                        <form method='post' action='delete_book.php'>
                        <input type='hidden' name='isbn' value='".$book['isbn']."'>
                        <input type='submit' value='Delete book'>
                        </form>
                        </td>";
                                             }
                ?>
            </tr>
        </table>
    <?php
}

function display_password_form() {
    ?>
    <br>
    <form action="change_password.php" method="post">
        <table width="250" cellpadding="2" cellspacing="0" bgcolor="#cccccc">
            <tr>
                <td>Old password:</td>
                <td><input type="password" name="old_passwd" size="16" maxlength="16"></td>
            </tr>
            <tr>
                <td>New password:</td>
                <td><input type="password" name="new_passwd" size="16" maxlength="16"></td>
            </tr>
            <tr>
                <td>Repeat new password:</td>
                <td><input type="password" name="new_passwd2" size="16" maxlength="16"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Change password">
                </td>
            </tr>
        </table>
    </form>
    <?php
}

function check_result($result) {
    if(!$result) {
        return false;
    } else {
        return true;
    }
}

function insert_category($catname) {
    // insert a new category into the database
    $conn = db_connect();

    // check category does not already exist
    $query = "select * from categories
where catname = '".$catname."'";
    $result = $conn->query($query);
    if((!$result) || ($result->num_rows != 0)) {
        return false;
    }

    // insert new category
    $query = "insert into categories (catname) VALUES 
('".$catname."')";
    $result = $conn->query($query);
    return check_result($result);
}

function insert_book($isbn, $title, $author, $catid, $price, $description) {
    // insert a new book into the database
    $conn = db_connect();

    // check book does not already exist
    $query = "select * from books where isbn='".$isbn."'";
    $result = $conn->query($query);
    if((!$result) || ($result->num_rows > 0)) {
        return false;
    }

    // insert new book
    $query = "insert into books VALUES 
('".$isbn."', '".$author."', '".$title."', '".$catid."', '".$price."', '".$description."')";
    $result = $conn->query($query);
    return check_result($result);
}

function update_category($catid, $catname) {
    // change the name of category with catid in the database
    $conn = db_connect();
    $query = "update categories set catname='".$catname."'
    where catid = '".$catid."'";
    $result = @$conn->query($query);
    check_result($result);
}

function update_book($oldisbn, $isbn, $title, $author, $catid, $price, $description) {
    $conn = db_connect();
    $query = "update books
    set isbn = '".$isbn."'
    title = '".$title."'
    author = '".$author."'
    catid = '".$catid."'
    price ='".$price."'
    descirption = '".$description."'
    where isbn = '".$oldisbn."'";

    $result = @$conn->query($query);
    return check_result($result);
}

function delete_category($catid) {
    $conn = db_connect();

    // check if there are any books in category
    $query = "select * from books where catid='".$catid."'";
    $result = @$conn->query($query);
    if((!$result) || (@$result->num_rows > 0)) {
        return false;
    }

    $query = "delete from categories where catid='".$catid."'";
    $result = @$conn->query($query);
    check_result($result);
}

function delete_book($isbn) {
    $conn = db_connect();

    $query = "delete from books where isbn='".$isbn."'";

    $result = @$conn->query($query);
    check_result($result);
}
