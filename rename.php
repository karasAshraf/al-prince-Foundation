<?php
$old = 'c:\\xampp\\htdocs\\projectslaravel\\princeFountion-project\\public\\storage';
$new = 'c:\\xampp\\htdocs\\projectslaravel\\princeFountion-project\\public\\storage_backup';
if (rename($old, $new)) {
    echo "Renamed successfully.\n";
} else {
    echo "Failed to rename.\n";
}
