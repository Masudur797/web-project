<?php
require 'db.php';
require 'helpers.php';
require_login();
$id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name,bio FROM users WHERE id=?");
$stmt->bind_param('i',$id); $stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$name = $user['name']; $bio = $user['bio'] ?? ''; $err='';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']); $bio = trim($_POST['bio']);
    if($name==='') $err = "Name cannot be empty.";
    else {
        $upd = $conn->prepare("UPDATE users SET name=?, bio=? WHERE id=?");
        $upd->bind_param('ssi',$name,$bio,$id);
        if($upd->execute()){ $_SESSION['user_name'] = $name; header('Location: profile.php'); exit; }
        else $err = "Update failed.";
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Profile</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <div class="nav"><a href="profile.php">Back</a></div>
  <h2>Edit Profile</h2>
  <?php if($err) echo '<div class="error">'.e($err).'</div>'; ?>
  <form method="post">
    <label>Name</label>
    <input name="name" value="<?= e($name) ?>">
    <label>Bio</label>
    <textarea name="bio"><?= e($bio) ?></textarea>
    <button type="submit">Save</button>
  </form>
</div>
</body>
</html>

