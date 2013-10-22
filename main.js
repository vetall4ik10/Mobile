function match () {
	var login = document.form['login'].value;
	var pass1 = document.form['password1'].value;
	var pass2 = document.form['password2'].value;
	var email = document.form['email'].value;
	var preg_email = /[\w]+\@{1}[\w]+\.[\w]/;
	var preg_login = /\W/;
	if (login.match(preg_login)) {
		document.getElementById('login').style.backgroundColor = 'rgba(255, 0, 0, 0.4)';
		document.getElementById('error').innerHTML = "В логіні використанні заборонені символи.(At login using invalid characters.)";
		return false;
	}
	else {
		document.getElementById('login').style.backgroundColor = 'white';
	}
	if ( pass1 != pass2 ) {
		document.getElementById('pass2').style.backgroundColor = 'rgba(255, 0, 0, 0.4)';
		document.getElementById('error').innerHTML = "Паролі не збігаються!(Passwords do not match!)"
		return false;
	}
	else {
		document.getElementById('pass2').style.backgroundColor = 'white';
	}
	if (!email.match(preg_email)) {
		document.getElementById('email').style.backgroundColor = 'rgba(255, 0, 0, 0.4)';
		document.getElementById('error').innerHTML = "Не коректно введений email!(Incorrectly entered email!)"
		return false;
	}
	else {
		document.getElementById('email').style.backgroundColor = 'white';
	}
}
