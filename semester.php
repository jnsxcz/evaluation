<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cap"; // Replace with your actual database name 'cap'

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add subject request (form submission)
if (isset($_POST['add'])) {
    $semesters = $_POST['semesters'];

    // Insert the class into the database
    $stmt = $conn->prepare("INSERT INTO semester (semesters) VALUES (?)");
    $stmt->bind_param("s", $semesters);
    
    // Execute and check for success
    if ($stmt->execute()) {
        echo "<script>alert('Semester added successfully!');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Handle Edit request
if (isset($_POST['edit'])) {
    $sem_id = $_POST['sem_id'];
    $semesters = $_POST['semesters'];

    // Update the subject in the database
    $stmt = $conn->prepare("UPDATE semester SET semesters=? WHERE sem_id=?");
    $stmt->bind_param("si", $semesters, $sem_id);  // Bind both parameters: semesters and sem_id
    
    // Execute and check for success
    if ($stmt->execute()) {
        echo "<script>alert('Semester updated successfully!');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Initialize $subjectToEdit as null
$semesterToEdit = null;
$result = null; // Initialize $result

// Check if there is a sem_id parameter for editing
if (isset($_GET['sem_id'])) {
    $sem_id = $_GET['sem_id'];

    // Fetch the subject details for editing
    $stmt = $conn->prepare("SELECT * FROM semester WHERE sem_id = ?");
    $stmt->bind_param("i", $sem_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the subject exists, assign it to $subjectToEdit
    if ($result->num_rows > 0) {
        $classToEdit = $result->fetch_assoc();
    } else {
        echo "Semester not found.";
    }

    $stmt->close();
} else {
    // Fetch all subjects when no sub_id is provided
    $stmt = $conn->prepare("SELECT * FROM semester");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Semester</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="containerr">
    <div class="navigation">
    <ul>
                <li>
                    <a href="index.php">
                        <span class="icon"><ion-icon name="school"></ion-icon></span>
                        <span class="title">NEUST</span>
                    </a>
                </li>

                <li id="dashboard">
                    <a href="dashboard.php"><span class="icon"><ion-icon name="home"></ion-icon></span><span class="title">Dashboard</span></a>
                </li>
                <li id="instructor">
                    <a href="instructor.php"><span class="icon"><ion-icon name="person-add"></ion-icon></span><span class="title">Instructor</span></a>
                </li>
                <li id="student">
                    <a href="student.php"><span class="icon"><ion-icon name="person-add"></ion-icon></span><span class="title">Student</span></a>
                </li>
                <li id="department">
                    <a href="department.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Department</span></a>
                </li>
                <li id="subject">
                <a href="subject.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Subject</span></a>
                </li>
                <li id="class">
                    <a href="class.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Class</span></a>
                </li>
                <li id="section">
                    <a href="section.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Section</span></a>
                </li>
                <li id="semester">
                    <a href="semester.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Semester</span></a>
                </li>
                <li id="academic">
                    <a href="acad_year.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Academic Year</span></a>
                </li>
                <li id="question">
                    <a href="question.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Question</span></a>
                </li>
                <li id="rate">
                    <a href="rate.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Rate</span></a>
                </li>
                <li id="evaluation">
                    <a href="evaluation.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Evaluation</span></a>
                </li>
            </ul>

    </div>

    <div class="main">
        <div class="topbar">
            <div class="toggle">
                <ion-icon name="menu"></ion-icon>
            </div>

            <div class="user">
                <div class="dropdown">
                    <button class="dropdown-btn">
                        <img src="/img/admin.jpg" alt="User Profile" class="profile-img">
                    </button>
                    <div class="dropdown-content">
                        <a href="#">Manage Account</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        

<!-- Modal Structure for Adding/Editing -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="form-title"><?php echo isset($classsToEdit) ? 'Edit' : 'Add'; ?> Class</h2>

         

        <form action="semester.php" method="POST">
            <!-- Hidden ID field for editing -->
            <?php if ($semesterToEdit): ?>
                <input type="hidden" name="sem_id" value="<?php echo $semesterToEdit['sem_id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="semesters" class="form-label">Year Level </label>
                <input type="text" name="semesters" id="semesters" class="form-input" 
                       value="<?php echo $semesterToEdit ? htmlspecialchars($semesterToEdit['semesters']) : ''; ?>" 
                       placeholder="Enter semester" required>
            </div>
            <button type="submit" name="add" class="submit-btn"><?php echo isset($semesterToEdit) ? 'Update' : 'Add'; ?> Semester</button>
        </form>
    </div>
</div>



   <!-- Plus icon to add department -->
   <button id="addSubBtn" class="add-btn">
            <ion-icon name="add-circle-outline"></ion-icon> <!-- Plus icon -->
        </button>


        <h2> Semester</h2>
        <!-- Make the table responsive -->
        <div class="table-wrapper">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Semester</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['semesters']; ?></td>
                                <td>
                                    <button class="btn btn-success edit-btn" onclick="openEditModal(<?php echo $row['sem_id']; ?>, '<?php echo $row['semesters']; ?>')">Edit</button>  
                                    
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No semester found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit subject Modal -->
<!-- Edit Subject Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit semester</h2>
        <form action="semester.php" method="POST" class="edit-form">
            <input type="hidden" id="editSemId" name="sem_id" class="form-input">
            
            <div class="form-group">
                <label for="semesters">Semester:</label>
                <input type="text" id="editSemesters" name="semesters" class="form-input" required>
            </div>

            
            
            <button type="submit" name="edit" class="submit-btn">Update Subject</button>
        </form>
    </div>
</div>


<script src="main.js"></script>
<script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
 
<script>
// JavaScript to handle opening and closing the modal
// JavaScript to handle opening and closing the modal
function openEditModal(sem_id, semesters) {
    // Ensure all the inputs are updated with the existing data
    document.getElementById('editSemId').value = sem_id;
    document.getElementById('editSemesters').value = semesters;

    // Display the modal
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Open modal for adding new class
var modal = document.getElementById("myModal");
var btn = document.getElementById("addSubBtn");
var span = document.getElementsByClassName("close")[0];

// Open modal for adding subject
btn.onclick = function() {
    modal.style.display = "block";
}

// Close modal when clicking the close button
span.onclick = function() {
    modal.style.display = "none";
}

        
</script>

</body>
</html>
