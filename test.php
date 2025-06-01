<?php

<?php phpinfo(); ?>


echo password_hash("admin12345", PASSWORD_DEFAULT);


$plain = 'admin12345';
$hash = '$2y$10$TDdjMBN2xMNq5.9J185V4.4mG0DnXGtrvuSrpKG7KORs0LjPmlb8q'; // paste actual DB hash

if (password_verify($plain, $hash)) {
    echo "✅ Match";
} else {
    echo "❌ No match";
}


?>
