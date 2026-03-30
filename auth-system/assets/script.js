// script.js
function showError(el, msg){
  let err = el.parentElement.querySelector('.error');
  if(!err){ err = document.createElement('span'); err.className='error'; el.parentElement.appendChild(err); }
  err.textContent = msg;
}
function clearError(el){
  let err = el.parentElement.querySelector('.error'); if(err) err.textContent='';
}

// simple name validation (at least two words, letters, dot, dash, start with letter)
function validateName(val){
  if(!val || val.trim()==='') return "Name cannot be empty";
  if(!/^[A-Za-z]/.test(val)) return "Must start with a letter";
  if(!/^[A-Za-z .-]+$/.test(val)) return "Only letters, period, dash and spaces allowed";
  if(val.trim().split(/\s+/).length < 2) return "At least two words required";
  return '';
}

// validate email
function validateEmail(val){
  if(!val || val.trim()==='') return "Email cannot be empty";
  // simple regex
  let re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if(!re.test(val)) return "Invalid email format";
  return '';
}
