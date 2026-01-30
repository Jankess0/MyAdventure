const form = document.querySelector("form");

const emailInput = form.querySelector('input[name="email"]');
const passwordInput = form.querySelector('input[name="password"], input[name="password"]');
const confirmedPasswordInput = form.querySelector('input[name="password2"]');
const firstNameInput = form.querySelector('input[name="firstName"]');
const lastNameInput = form.querySelector('input[name="lastName"]');

function isEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}

function isPasswordStrong(password) {
    // Regex oznacza:
    // (?=.*[a-z])    -> co najmniej jedna mała litera
    // (?=.*[A-Z])    -> co najmniej jedna DUŻA litera
    // (?=.*\d)       -> co najmniej jedna cyfra
    // (?=.*[\W_])    -> co najmniej jeden znak specjalny
    // .{8,}          -> minimum 8 znaków długości
    
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    
    return regex.test(password);
}

function arePasswordsSame(password, confirmedPassword) {
    return password === confirmedPassword;
}

function isNameValid(name) {
    const regex = /^[a-zA-ZąęćłńóśźżĄĘĆŁŃÓŚŹŻ\s-]{2,}$/;
    return regex.test(name);
}


function markValidation(element, condition) {
    if (!condition) {
        element.classList.add('no-valid');
    } else {
        element.classList.remove('no-valid');
    }
}

let timeouts = {
    email: null,
    password: null,
    confirmedPassword: null,
    firstName: null,
    lastName: null
};

const delay = 1000;

emailInput.addEventListener('keyup', function() {
    clearTimeout(timeouts.email); 
    
timeouts.email = setTimeout(function() {
        const email = emailInput.value;
        const isValidSyntax = isEmail(email);
        
        if (!isValidSyntax) {
            markValidation(emailInput, false);
            return;
        }

        fetch('/check_email_exists', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                alert("Ten email jest już zajęty!");
                markValidation(emailInput, false);
            } else {
                markValidation(emailInput, true);
            }
        })
        .catch(error => console.error('Error:', error));
    }, delay);
});

passwordInput.addEventListener('keyup', function() {
    clearTimeout(timeouts.password);
    
    timeouts.password = setTimeout(function() {
        const isValid = isPasswordStrong(passwordInput.value);
        markValidation(passwordInput, isValid);
        
        if (confirmedPasswordInput.value.length > 0) {
            const areSame = arePasswordsSame(passwordInput.value, confirmedPasswordInput.value);
            markValidation(confirmedPasswordInput, areSame);
        }
    }, delay);
});

confirmedPasswordInput.addEventListener('keyup', function() {
    clearTimeout(timeouts.confirmedPassword);

    timeouts.confirmedPassword = setTimeout(function() {
        const isValid = arePasswordsSame(passwordInput.value, confirmedPasswordInput.value);
        markValidation(confirmedPasswordInput, isValid);
    }, delay);
});

firstNameInput.addEventListener('keyup', function() {
    clearTimeout(timeouts.firstName);

    timeouts.firstName = setTimeout(function() {
        const isValid = isNameValid(firstNameInput.value);
        markValidation(firstNameInput, isValid);
    }, delay);
});

lastNameInput.addEventListener('keyup', function() {
    clearTimeout(timeouts.lastName);

    timeouts.lastName = setTimeout(function() {
        const isValid = isNameValid(lastNameInput.value);
        markValidation(lastNameInput, isValid);
    }, delay);
});