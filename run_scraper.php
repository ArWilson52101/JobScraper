<?php
//never did end up working. probably going to finish it another time when i can figure it out.


// Run the Python scraper

$output = shell_exec("python -m C:\Program Files\Ampps\www\Final\job_scraper");  // Adjust the path based on where your script is located

echo shell_exec('echo Hello, World!');
// Redirect back to the main page (optional)
header("Location: index.php");
exit();
?>
