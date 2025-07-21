document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
        const name = document.getElementById("name").value.trim();
        const age = document.getElementById("age").value.trim();
        const gender = document.getElementById("gender").value.trim();
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const desc = document.getElementById("desc").value.trim();

        let errors = [];

        // Validate Name: letters and spaces, minimum 2 characters
        if (name === "" || !/^[a-zA-Z\s]{2,}$/.test(name)) {
            errors.push("Name must contain only letters and be at least 2 characters.");
        }

        // Validate Age: number, between 1 and 120
        const ageVal = Number(age);
        if (age === "" || isNaN(ageVal) || ageVal < 1 || ageVal > 120) {
            errors.push("Please enter a valid age between 1 and 120.");
        }

        // Validate Gender: one of the valid options
        const validGenders = ["Male", "Female", "Other"];
        if (gender === "" || !validGenders.includes(gender)) {
            errors.push("Please select a valid gender: Male, Female, or Other.");
        }

        // Validate Email: basic pattern
        if (email === "" || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.push("Please enter a valid email address.");
        }

        // Validate Phone: 10-digit number starting with 6-9 (Indian format)
        if (phone === "" || !/^[6-9]\d{9}$/.test(phone)) {
            errors.push("Phone number must be 10 digits and start with 6, 7, 8, or 9.");
        }

        // Validate Description: at least 5 characters, max 300 optional
        if (desc.length < 5 || desc.length > 300) {
            errors.push("Description must be between 5 and 300 characters.");
        }

        // Prevent form if any errors
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join("\n"));
        }
    });
});
