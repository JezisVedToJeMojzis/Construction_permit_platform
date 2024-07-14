<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Form</title>
    <style>
        .form-section {
            display: none; /* Hide all form sections by default */
            margin-bottom: 20px;
        }
        .form-section.active {
            display: block; /* Show only the active form section */
        }
    </style>
</head>
<body>
<form id="multi_step_form" action="submit_application.php" method="post">
    <!-- Step 1: Select Role -->
    <div id="step_role" class="form-section active">
        <h2>Select Role</h2>
        <label>
            <input type="radio" name="role" value="owner"> Owner
        </label>
        <label>
            <input type="radio" name="role" value="contractor"> Contractor
        </label>
        <br>
        <button type="button" id="next_role">Next</button>
    </div>

    <!-- Step 2: Property Information -->
    <div id="step_property" class="form-section">
        <h2>Property Information</h2>
        <label for="street">Street:</label>
        <input type="text" id="street" name="street" required>
        <br>
        <!-- Add other property fields -->
        <button type="button" id="prev_property">Previous</button>
        <button type="button" id="next_property">Next</button>
    </div>

    <!-- Step 3: Project Information -->
    <div id="step_project" class="form-section">
        <h2>Project Information</h2>
        <label for="project_name">Project Name:</label>
        <input type="text" id="project_name" name="project_name" required>
        <br>
        <!-- Add other project fields -->
        <button type="button" id="prev_project">Previous</button>
        <button type="submit">Submit</button>
    </div>

    <!-- Hidden input to store selected role -->
    <input type="hidden" id="selected_role" name="selected_role" value="">
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('multi_step_form');
        const nextRoleBtn = document.getElementById('next_role');
        const prevPropertyBtn = document.getElementById('prev_property');
        const nextPropertyBtn = document.getElementById('next_property');
        const prevProjectBtn = document.getElementById('prev_project');
        const selectedRoleInput = document.getElementById('selected_role');

        nextRoleBtn.addEventListener('click', function() {
            const selectedRole = document.querySelector('input[name="role"]:checked');
            if (selectedRole) {
                selectedRoleInput.value = selectedRole.value;
                document.getElementById('step_role').classList.remove('active');
                document.getElementById('step_property').classList.add('active');
            } else {
                alert('Please select a role.');
            }
        });

        prevPropertyBtn.addEventListener('click', function() {
            document.getElementById('step_property').classList.remove('active');
            document.getElementById('step_role').classList.add('active');
        });

        nextPropertyBtn.addEventListener('click', function() {
            document.getElementById('step_property').classList.remove('active');
            document.getElementById('step_project').classList.add('active');
        });

        prevProjectBtn.addEventListener('click', function() {
            document.getElementById('step_project').classList.remove('active');
            document.getElementById('step_property').classList.add('active');
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Collect all form data
            const formData = new FormData(form);

            // Example using Fetch API to submit data
            fetch('submit_application.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    // Handle response from server (if needed)
                    console.log(data);
                    // Optionally redirect or show confirmation
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>


</body>
</html>
