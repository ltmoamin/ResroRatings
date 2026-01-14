$(document).ready(function () {
    // Wrap your code inside a self-invoking function to avoid global scope pollution
    (function() {
        function performUserSearch() {
            var value = $("#searchUserForm input[name='q']").val();
            var searchUrl = $("#searchUserForm").data('search-url');
            var usersTable = $("#usersTable");

            // Use asynchronous AJAX request
            $.ajax({
                url: searchUrl,
                type: 'GET',
                data: {'q': value},
                success: function (html) {
                    // Update the content of the users table with the search results HTML
                    usersTable.html(html);
                },
                error: function(xhr, status, error) {
                    console.error("Error during user search:", status, error);
                }
            });
        }

        // Use 'input' event for real-time search as the user types
        $("#searchUserForm input[name='q']").on('input', function () {
            performUserSearch();
        });

        // Use 'submit' event to handle form submission
        $("#searchUserForm").submit(function (e) {
            e.preventDefault();
            performUserSearch();
        });
    })();
});