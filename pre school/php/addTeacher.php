<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Manage Teachers</title>
  <link rel="stylesheet" href="../CSS/Principal_Dashboard.css">
  <link rel="stylesheet" href="../CSS/Add_Teacher.css">
</head>

<body>
  <header class="topbar">
    <div class="brand">
      <a href="Principal_Dashboard.html" class="logo-link"><img src="../images/logo.png" class="logo-img"
          alt="Logo"></a>
      <span class="brand-title">Manage Teachers</span>
    </div>
    <div class="top-actions">
      <button class="btn" onclick="location.href='Principal_Dashboard.html'">Back</button>
    </div>
  </header>

  <main class="main-container">
    <section class="panel">
      <h2>Add Teacher</h2>
      <form id="teacherForm" action="../PHP/add_teacher.php" method="post">
        <label>Full Name</label>
  <input type="text" name="fullname" id="fullname" class="form-control">

        <label>Age</label>
        <input type="number" name="age" id="age" min="18" class="form-control">

        <label>Phone</label>
        <input type="text" name="phone" id="phone" class="form-control">

        <label>Username</label>
  <input type="text" name="username" id="username" class="form-control">

        <label>Password</label>
  <input type="password" name="password" id="password" class="form-control">

        <label>Email</label>
  <input type="email" name="email" id="email" class="form-control">

        <label>Qualification</label>
        <input type="text" name="qualification" id="qualification" class="form-control">

        <label>Salary</label>
        <input type="number" step="0.01" name="salary" id="salary" class="form-control">

        <label>Address</label>
        <textarea name="address" id="address" rows="3" class="form-control"></textarea>

        <label>Gender</label>
        <select name="gender" id="gender" class="form-control">
          <option value="">Select gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>

  <button type="submit" class="btn btn-top">Add Teacher</button>
      </form>
    </section>

    <section class="panel panel-margin-top">
      <h2>Existing Teachers</h2>
      <table id="teacherTable" class="table-full">
        <thead>
          <tr class="table-row">
            <th>Name</th>
            <th>Department</th>
            <th>Email</th>
            <th></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </section>
  </main>

  <script src="../JS/admin_manage.js"></script>
  <script>initTeachers()</script>
  <script>
    // Client-side validation: fullname, username, password, email
    (function(){
      var form = document.getElementById('teacherForm');
      if (!form) return;

  function showError(msg){
        var existing = document.getElementById('formErrorBox');
        if (!existing){
          existing = document.createElement('div');
          existing.id = 'formErrorBox';
          existing.className = 'msg error-msg';
          form.parentNode.insertBefore(existing, form);
        }
        existing.textContent = msg;
      }

      function clearError(){
        var existing = document.getElementById('formErrorBox');
        if (existing) existing.parentNode.removeChild(existing);
      }

      form.addEventListener('submit', function(e){
        e.preventDefault();
        clearError();
        var fullname = document.getElementById('fullname').value.trim();
        var age = document.getElementById('age').value.trim();
        var phone = document.getElementById('phone').value.trim();
        var username = document.getElementById('username').value.trim();
        var password = document.getElementById('password').value;
        var email = document.getElementById('email').value.trim();
        var qualification = document.getElementById('qualification').value.trim();
        var salary = document.getElementById('salary').value.trim();
        var address = document.getElementById('address').value.trim();
        var gender = document.getElementById('gender').value;

        if (!fullname) { showError('Full name is required'); e.preventDefault(); return; }
        if (!age || isNaN(age) || parseInt(age) < 18) { showError('Valid age (>=18) is required'); e.preventDefault(); return; }
        if (!phone) { showError('Phone is required'); e.preventDefault(); return; }
        if (!username) { showError('Username is required'); e.preventDefault(); return; }
        if (!password || password.length < 6) { showError('Password is required (min 6 chars)'); e.preventDefault(); return; }
        if (!email) { showError('Email is required'); e.preventDefault(); return; }
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!re.test(email)) { showError('Enter a valid email address'); e.preventDefault(); return; }
        if (!qualification) { showError('Qualification is required'); e.preventDefault(); return; }
        if (salary === '' || isNaN(salary)) { showError('Valid salary is required'); e.preventDefault(); return; }
        if (!address) { showError('Address is required'); e.preventDefault(); return; }
        if (!gender) { showError('Please select gender'); return; }

        // submit via fetch
        var formData = new FormData(form);
        fetch('../PHP/add_teacher.php', {
          method: 'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          body: formData
        }).then(function(resp){
          return resp.json();
        }).then(function(data){
          if (data.success) {
            clearError();
            var ok = document.createElement('div');
            ok.className = 'msg success-msg';
            ok.textContent = data.message || 'Teacher added successfully.';
            form.parentNode.insertBefore(ok, form);
            form.reset();
            if (typeof initTeachers === 'function') initTeachers();
          } else {
            showError(data.message || 'Error adding teacher');
          }
        }).catch(function(err){
          showError('Network error');
          console.error(err);
        });
        return;
      });
    })();
  </script>
</body>

</html>