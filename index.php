
<?php
//Connects to the DB
$conn = new mysqli("localhost", "root", "mysql", "final"); //login
$result = $conn->query("
    SELECT jobs.*, saved_jobs.status AS saved_status, saved_jobs.notes AS saved_notes
    FROM jobs
    LEFT JOIN saved_jobs ON jobs.id = saved_jobs.job_id
");



echo "<h2>Job Listings</h2><table border='1'>";
echo "<tr><th>Title</th>
	<th>Company</th>
	<th>Location</th>
	<th>Status</th>
	<th>Notes</th>
	<th>Actions</th>
	</tr>";
	//Creates the table.

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td><a href='{$row['url']}' target='_blank'>{$row['title']}</a></td>";
    echo "<td>{$row['company']}</td>";
	echo "<td>{$row['location']}</td>";
    echo "<td>{$row['saved_status']}</td>";
	echo "<td>{$row['saved_notes']}</td>";
// Returns the job info

	// does the stuff for actions and notes
    echo "<td>
        <form method='POST' action='update.php'>
            <input type='hidden' name='job_id' value='{$row['id']}'>

            <select name='status'>
                <option>saved</option>
                <option>applied</option>
                <option>interview</option>
                <option>rejected</option>
                <option>offer</option>
            </select>
            <input type='text' name='notes' placeholder='Notes' />
            <input type='submit' value='Update'>
        </form>
    </td>";
    echo "</tr>";
}
echo "</table>";



?>
