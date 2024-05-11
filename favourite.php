<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favourite Page</title>
</head>
<body>
    <h1>Choose Your Favourite Item</h1>
    <form action="favourite.php" method="post">
        <label for="favourite">Select your favourite:</label>
        <select name="favourite" id="favourite">
            <option value="Chocolate">Chocolate</option>
            <option value="Vanilla">Vanilla</option>
            <option value="Strawberry">Strawberry</option>
            <option value="Mint">Mint</option>
        </select>
        <button type="submit">Submit</button>
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $favourite = $_POST['favourite'];
            echo "<h2>Your favourite flavour is $favourite!</h2>";
        }
    ?>
</body>
</html>
