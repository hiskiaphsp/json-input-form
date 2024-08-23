<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JSON Form Submission</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div id="app">
        <form id="dynamicForm" enctype="multipart/form-data">
            <!-- Form fields will be inserted here -->
            <input type="file" name="uploaded_file" id="uploaded_file">
        </form>
        <button type="button" onclick="submitForm()">Submit</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch the JSON data and generate form fields
            axios.get('/api/json-data').then(response => {
                const formData = response.data;
                const form = document.getElementById('dynamicForm');

                formData.forEach(entry => {
                    const parsedData = entry.data;
                    Object.keys(parsedData).forEach(key => {
                        const field = document.createElement('input');
                        field.setAttribute('type', 'text');
                        field.setAttribute('name', key);
                        field.setAttribute('value', parsedData[key]);
                        form.appendChild(field);
                    });
                });
            });
        });

        function submitForm() {
            const form = document.getElementById('dynamicForm');
            const formData = new FormData(form);

            // Submit the form data and file
            axios.post('/api/json-data', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                alert('Form submitted successfully');
            }).catch(error => {
                alert('Form submission failed');
            });
        }
    </script>
</body>
</html>
