jQuery(document).ready(function ($) {
    // The ID of the standard WordPress content editor is 'content'
    // If you use a custom field, change this ID.
    var descriptionField = $('#content');

    if (descriptionField.length) {
        // Create the button HTML
        var buttonHTML = '<div style="margin-top:10px;"><button type="button" id="generate-ai-description" class="button button-primary">✨ Generate Description with AI</button></div>';

        // Insert the button after the description field's container
        descriptionField.after(buttonHTML);

        // Listen for clicks on our new button
        $('body').on('click', '#generate-ai-description', function (e) {
            e.preventDefault();

            var button = $(this);
            var originalButtonText = button.text();

            // Get the project title to give context to the AI
            var projectTitle = $('#title').val();

            if (!projectTitle) {
                alert('Please enter a project title first!');
                return;
            }

            // Show a loading state
            button.text('Generating... Please wait...');
            button.prop('disabled', true);

            // AJAX request to our PHP function
            $.ajax({
                url: ai_generator_data.ajax_url, // The URL passed from PHP
                type: 'POST',
                data: {
                    action: 'generate_project_description', // The PHP action hook
                    nonce: ai_generator_data.nonce,       // The security nonce
                    project_title: projectTitle           // The data we are sending
                },
                success: function (response) {
                    if (response.success) {
                        // The standard editor is a textarea, but if TinyMCE is active, we need to use its API
                        var editor = tinymce.get('content');
                        if (editor) {
                            // If TinyMCE is active
                            editor.setContent(response.data.description);
                        } else {
                            // If it's a plain textarea
                            descriptionField.val(response.data.description);
                        }
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                },
                error: function () {
                    alert('An unknown error occurred with the AJAX request.');
                },
                complete: function () {
                    // Restore the button to its original state
                    button.text(originalButtonText);
                    button.prop('disabled', false);
                }
            });
        });
    }
});