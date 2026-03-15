<form method="post" action="/login">

<input type="text" name="user" placeholder="User ID" required>

<input type="password" name="sandi" placeholder="Password" required>

<button type="submit">Login</button>

</form>

<?php
if(!empty($_SESSION['error'])){
echo $_SESSION['error'];
unset($_SESSION['error']);
}
?>
