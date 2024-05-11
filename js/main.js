function confirmFavorite(anchor, event, artId) {
    event.preventDefault();  // Prevent the default anchor click behavior

    // Confirm dialog
    if (confirm('Are you sure you want to add this to your favorites?')) {
        fetch('Favourite/favourite_art.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'https://lam/Favourite/favourite_art.php',
            },
            body: `art_id=${artId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the text of the anchor based on whether it is now favorited or not
                anchor.textContent = data.favorited ? 'Unfavorite' : 'Favorite'; // Toggle text
                alert(data.message);  // Optional: Display a message to the user
            } else {
                alert('Failed to update favorite status.');  // Display error message
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating favorite status.');
        });
    }
}
