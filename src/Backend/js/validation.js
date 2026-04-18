document.addEventListener("DOMContentLoaded", function () {

    const validation = new JustValidate("#signup", {
        errorFieldCssClass: 'is-invalid',
        errorLabelCssClass: 'is-label-invalid',
    });

    validation
        .addField("#signup_name", [
            {
                rule: "required",
                errorMessage: "Name is required"
            }
        ])
        .addField("#signup_email", [
            {
                rule: "required",
                errorMessage: "Email is required"
            },
            {
                rule: "email",
                errorMessage: "Enter a valid email"
            },
            {
                validator: (value) => () => {
                    return fetch("validate-email.php?email=" + encodeURIComponent(value))
                        .then(res => res.json())
                        .then(json => json.available);
                },
                errorMessage: "This email is already taken"
            }
        ])
        .addField("#signup_password", [
            {
                rule: "required",
                errorMessage: "Password is required"
            },
            {
                rule: "password",
                errorMessage: "Must contain letters & numbers"
            },
            {
                rule: "minLength",
                value: 8,
                errorMessage: "Minimum 8 characters"
            }
        ])
        .addField("#signup_password_confirmation", [
            {
                validator: (value, fields) => {
                    return value === fields["#signup_password"].elem.value;
                },
                errorMessage: "Passwords must match"
            }
        ])
        .onSuccess((event) => {
            event.target.submit();
        });

});