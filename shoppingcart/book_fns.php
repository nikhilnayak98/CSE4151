<?php
function calculate_shipping_cost() {
    // as we are shipping products all over the world
    return 20.00;
}

function get_categories() {
    $conn= db_connect();
    $query = "select catid, catname from categories;";
    $result = @$conn->query($query);
    if(!$result) {
        return false;
    }
    $num_cats = @$result->num_rows;
    if($num_cats == 0) {
        return false;
    }
    $result = db_result_to_array($result);
    return $result;
}


function get_category_name($catid) {
    $conn = db_connect();
    $query = "select catname from categories where catid = '".$catid."'";
    $result = @$conn->query($query);
    if(!$result) {
        return false;
    }
    $num_cats = @$result->num_rows;
    if($num_cats == 0) {
        return false;
    }
    $row = $result->fetch_object();
    return $row->catname;
}

function get_books($catid) {
    // query database for the books in a category
    if((!$catid) || ($catid == '')) {
        return false;
    }
    $conn = db_connect();
    $query = "select * from books where catid = '".$catid."'";
    $result = @$conn->query($query);
    if(!$result) {
        return false;
    }
    $result = db_result_to_array($result);
    return $result;
}

function get_book_details($isbn) {
    // query database for all details for a particular book
    if((!$isbn) || ($isbn == '')) {
        return false;
    }
    $conn = db_connect();
    $query = "select * from books where isbn='".$isbn."'";
    $result = @$conn->query($query);
    if(!$result) {
        return false;
    }
    $result = @$result->fetch_assoc();
    return $result;
}

function calculate_price($cart) {
    // sum total price for all items in shopping cart
    $price = 0.0;
    if(is_array($cart)) {
        $conn = db_connect();
        foreach($cart as $isbn=>$qty) {
            $query = "select price from books where isbn = '".$isbn."'";
            $result = $conn->query($query);
            if($result) {
                $item = $result->fetch_object();
                $item_price = $item->price;
                $price += $item_price*$qty;
            }
        }
    }
    return $price;
}

function calculate_items($cart) {
    // sum total items in shopping cart
    $items = 0;
    if(is_array($cart)) {
        foreach($cart as $isbn=>$qty) {
            $items += $qty;
        }
    }
    return $items;
}

